<?php
// ajax/booking_submit.php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../connect/db_connect.php';

$input = json_decode(file_get_contents('php://input'), true);
if (!$input) {
    echo json_encode(['success'=>false,'message'=>'Nevar nolasīt pieprasījumu (JSON).']);
    exit;
}

// Required
$required = ['first_name','last_name','phone','email','start_date','end_date','products'];
foreach($required as $r){
    if(!isset($input[$r]) || $input[$r] === ''){
        echo json_encode(['success'=>false,'message'=>"Trūkst lauka: $r"]);
        exit;
    }
}

$first_name = trim($input['first_name']);
$last_name  = trim($input['last_name']);
$phone      = trim($input['phone']);
$email      = trim($input['email']);
$start_date = $input['start_date'];
$end_date   = $input['end_date'];
$products   = $input['products'];

if (!is_array($products) || count($products) === 0){
    echo json_encode(['success'=>false,'message'=>'Izvēlies vismaz vienu produktu.']);
    exit;
}

// convert date to MySQL DATETIME range: start 00:00:00, end 23:59:59
function toMysqlStart($d){
    // expects yyyy-mm-dd
    return $d . ' 00:00:00';
}
function toMysqlEnd($d){
    return $d . ' 23:59:59';
}
$start_mysql = toMysqlStart($start_date);
$end_mysql   = toMysqlEnd($end_date);

// days
$start_ts = strtotime($start_mysql);
$end_ts   = strtotime($end_mysql);
$days = 1;
if ($start_ts && $end_ts && $end_ts > $start_ts) {
    $days = (int) ceil(($end_ts - $start_ts) / (24*60*60));
    if ($days < 1) $days = 1;
}

try {
    $conn->begin_transaction();

    // client find / create
    $client_id = null;
    $stmt = $conn->prepare("SELECT id FROM clients WHERE email = ? LIMIT 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($row = $res->fetch_assoc()) {
        $client_id = (int)$row['id'];
        // update name/phone
        $stmt_up = $conn->prepare("UPDATE clients SET name = ?, phone = ? WHERE id = ?");
        $fullname = $first_name . ' ' . $last_name;
        $stmt_up->bind_param("ssi", $fullname, $phone, $client_id);
        $stmt_up->execute();
    } else {
        $stmtIns = $conn->prepare("INSERT INTO clients (name, email, phone, created_at, status_id) VALUES (?, ?, ?, NOW(), ?)");
        $fullname = $first_name . ' ' . $last_name;
        $status_active = 7;
        $stmtIns->bind_param("sssi", $fullname, $email, $phone, $status_active);
        $stmtIns->execute();
        $client_id = $stmtIns->insert_id;
    }

    // Prepare statements
    $productStmt = $conn->prepare("SELECT id, name, price_per_day, is_routable, available_units FROM products WHERE id = ? LIMIT 1");
    $routeStmt   = $conn->prepare("SELECT id, price FROM routes WHERE id = ? AND product_id = ? LIMIT 1");

    $total = 0.0;
    $bookingItems = [];

    // statuses considered active/reserved for overlap check
    $activeStatuses = [4,9,10]; // rezervēts, gaida apstipr., apmaksāts

    foreach($products as $it) {
        $pid = intval($it['product_id']);
        $qty = isset($it['quantity']) ? intval($it['quantity']) : 1;
        if ($qty < 1) $qty = 1;
        $route_id = isset($it['route_id']) && $it['route_id'] !== '' ? intval($it['route_id']) : null;

        // load product
        $productStmt->bind_param("i", $pid);
        $productStmt->execute();
        $pres = $productStmt->get_result();
        if(!$pres || $pres->num_rows === 0){
            throw new Exception("Produktu ar id $pid nevar atrast.");
        }
        $prow = $pres->fetch_assoc();

        // check availability
        $available = (int)$prow['available_units'];
        if($available > 0){
            // compute already booked for overlapping bookings
            $sql = "SELECT COALESCE(SUM(bp.quantity),0) AS booked
                    FROM booking_products bp
                    JOIN bookings b ON bp.booking_id = b.id
                    WHERE bp.product_id = ?
                      AND b.status_id IN (" . implode(',', $activeStatuses) . ")
                      AND NOT (b.end_datetime <= ? OR b.start_datetime >= ?)";
            $chk = $conn->prepare($sql);
            $chk->bind_param("iss", $pid, $start_mysql, $end_mysql);
            $chk->execute();
            $cres = $chk->get_result();
            $already = 0;
            if($crow = $cres->fetch_assoc()) $already = (int)$crow['booked'];
            $free = $available - $already;
            if($qty > $free){
                throw new Exception("Nav pietiekami daudz produktu \"{$prow['name']}\" izvēlētajiem datumiem. Pieejami: $free.");
            }
        }
        // price calculation
        $unit_price = 0.0;
        if ((int)$prow['is_routable'] === 1) {
            if (!$route_id) throw new Exception("Jāizvēlas maršruts priekš produkta: {$prow['name']}.");
            $routeStmt->bind_param("ii", $route_id, $pid);
            $routeStmt->execute();
            $rr = $routeStmt->get_result();
            if(!$rr || $rr->num_rows === 0) throw new Exception("Maršruts nav atrasts vai nepieder produktam {$prow['name']}.");
            $rrow = $rr->fetch_assoc();
            $unit_price = (float)$rrow['price'];
            $total += $unit_price * $qty;
        } else {
            $pday = (float)$prow['price_per_day'];
            $unit_price = $pday;
            $total += $unit_price * $days * $qty;
        }

        $bookingItems[] = [
            'product_id'=>$pid,
            'route_id'=>$route_id,
            'unit_price'=>$unit_price,
            'name'=>$prow['name'],
            'quantity'=>$qty
        ];
    }

    // insert booking
    $firstProductId = $bookingItems[0]['product_id'];
    $firstRouteId = $bookingItems[0]['route_id'] ?? null;
    $status_booked = 4;

    $stmtBooking = $conn->prepare("INSERT INTO bookings (client_id, product_id, route_id, start_datetime, end_datetime, total_price, status_id, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), NOW())");
    // allow null route_id -> bind as integer or null via dynamic
    // We'll bind route as integer or null using s placeholders for datetimes, i - integers, d - double
    // types: i i i s s d i  => "iiissdi"
    $stmtBooking->bind_param("iiissdi", $client_id, $firstProductId, $firstRouteId, $start_mysql, $end_mysql, $total, $status_booked);
    $stmtBooking->execute();
    $booking_id = $stmtBooking->insert_id;

    // insert booking_products
    $stmtBP = $conn->prepare("INSERT INTO booking_products (booking_id, product_id, quantity, product_price_at_booking, product_name_snapshot, subtotal) VALUES (?, ?, ?, ?, ?, ?)");
    foreach($bookingItems as $it){
        $sub = $it['unit_price'] * $it['quantity'] * ((isset($it['route_id']) && $it['route_id']) ? 1 : $days);
        $stmtBP->bind_param("iiidsd", $booking_id, $it['product_id'], $it['quantity'], $it['unit_price'], $it['name'], $sub);
        $stmtBP->execute();
    }

    // prepayment
    $prepayment = round($total * 0.5, 2);
    $status_waiting = 9;
    $stmtPre = $conn->prepare("INSERT INTO prepayments (booking_id, amount, payment_date, status_id) VALUES (?, ?, NOW(), ?)");
    $stmtPre->bind_param("idi", $booking_id, $prepayment, $status_waiting);
    $stmtPre->execute();

    $conn->commit();

    echo json_encode([
        'success'=>true,
        'message'=>'Rezervācija veikta. Pārbaudi e-pastu — drīzumā saņemsi apstiprinājumu.',
        'booking_id'=>$booking_id,
        'total'=>number_format($total,2,'.',''),
        'prepayment'=>number_format($prepayment,2,'.','')
    ]);
    exit;

} catch (Exception $ex){
    $conn->rollback();
    echo json_encode(['success'=>false,'message'=>'Kļūda: '.$ex->getMessage()]);
    exit;
}
