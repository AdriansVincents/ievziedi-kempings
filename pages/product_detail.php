<?php
include '../connect/db_connect.php';
include '../includes/header.php';
?>

<!DOCTYPE html>
<html lang="lv">
<head>
  <meta charset="UTF-8">
  <title>Produkts – Ievziedi Kempings</title>
  <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<main class="product-page">

<?php
// Noklusējuma - produkts nav ielādēts
$product = null;

if (!isset($_GET['id'])) {
    echo "<p>Kļūda — produkta ID nav norādīts.</p>";
} else {
    $id = (int)$_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res && $res->num_rows > 0) {
        // Saglabājam produktu globālajā $product mainīgajā, lai to varētu izmantot vēlāk
        $product = $res->fetch_assoc();

        // Attēla izvēle
        $hero = !empty($product['main_image']) ? $product['main_image'] : ( !empty($product['image']) ? $product['image'] : 'images/default.jpg' );

        echo "<section class='product-hero' style='background-image: url(../" . htmlspecialchars($hero) . ");'>";
        echo "<div class='overlay'><h1>" . htmlspecialchars($product['name']) . "</h1></div>";
        echo "</section>";

        echo "<section class='product-info'>";
        echo "<div class='container'>";
        echo "<div class='product-description'>" . nl2br(htmlspecialchars($product['description'])) . "</div>";

        echo "<div class='product-meta'>";
        if (!empty($product['capacity'])) echo "<p><strong>Kapacitāte:</strong> " . htmlspecialchars($product['capacity']) . "</p>";
        if (!empty($product['facilities'])) echo "<p><strong>Ērtības:</strong> " . nl2br(htmlspecialchars($product['facilities'])) . "</p>";
        if (!empty($product['price_per_day'])) echo "<p><strong>Cena:</strong> " . htmlspecialchars($product['price_per_day']) . " € / dienā</p>";
        if (!empty($product['available_units'])) echo "<p><strong>Pieejamība:</strong> " . (int)$product['available_units'] . " vienības</p>";
        echo "</div>";

        echo "<p><a class='btn' href='booking.php?product_id=" . (int)$product['id'] . "'>Rezervēt</a></p>";
        echo "</div>";
        echo "</section>";

        // galerija
        if (!empty($product['gallery_images'])) {
            $images = array_map('trim', explode(',', $product['gallery_images']));
            echo "<section class='product-gallery'><div class='container'>";
            foreach ($images as $img) {
                // ja ceļš nav absolūts, pievienojam ../, ja nepieciešams, bet šeit mēs pieņemam relatīvu ceļu no pages/
                echo "<img src='../" . htmlspecialchars($img) . "' alt=''> ";
            }
            echo "</div></section>";
        }

    } else {
        echo "<p>Produkts nav atrasts.</p>";
    }
    $stmt->close();
}

// Ja produkts ir atrasts, atlasām maršrutus
if ($product !== null) {
    // drošs product_id kā int
    $productId = (int)$product['id'];
    $routes = $conn->query("SELECT * FROM routes WHERE product_id = $productId ORDER BY id ASC");

    if ($routes && $routes->num_rows > 0) {
        echo "<section class='route-section'>";
        echo "<h3>Pieejamie maršruti</h3>";
        echo "<div class='routes-grid'>";
        while ($r = $routes->fetch_assoc()) {
            echo "<div class='route-card'>";
            // izmanto no_location un to_location kolonnas (ja gribi, vari apvienot nosaukumu)
            $from = htmlspecialchars($r['from_location']);
            $to = htmlspecialchars($r['to_location']);
            echo "<div class='route-header'>{$from} – {$to}</div>";
            echo "<div class='route-price'>€ " . htmlspecialchars($r['price']) . "</div>";
            echo "<ul class='route-info'>";
            echo "<li><strong>Attālums:</strong> " . htmlspecialchars($r['distance_km']) . " km</li>";
            // ja duration_min_day un duration_max_day vienādi, rādām vienu dienu
            $minD = (int)$r['duration_min_day'];
            $maxD = (int)$r['duration_max_day'];
            $durText = $minD === $maxD ? $minD . " diena(-s)" : $minD . "–" . $maxD . " dienas";
            echo "<li><strong>Ilgums:</strong> " . $durText . "</li>";
            echo "</ul>";
            echo "</div>";
        }
        echo "</div>";
        echo "</section>";
    } else {
        // nav maršrutu — neizvadīt kļūdu, vienkārši neko nerādīt vai paziņot
        // echo "<p>Nav pieejamu maršrutu šim produktam.</p>";
    }
}
?>

</main>

<?php include '../includes/footer.php'; ?>

</body>
</html>
