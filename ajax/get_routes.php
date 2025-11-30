<?php
require_once "../connect/db_connect.php";

header("Content-Type: application/json; charset=UTF-8");

if (!isset($_GET['product_id'])) {
    echo json_encode([]);
    exit;
}

$product_id = intval($_GET['product_id']);

$stmt = $conn->prepare("SELECT * FROM routes WHERE product_id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();

$routes = [];
while ($row = $result->fetch_assoc()) {
    $routes[] = $row;
}

echo json_encode($routes);
?>
