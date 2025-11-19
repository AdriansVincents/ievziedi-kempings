<?php
include '../connect/db_connect.php';
include '../includes/header.php';
?>

<!DOCTYPE html>
<html lang="lv">
<head>
  <meta charset="UTF-8">
  <title>Piedāvājumi – Ievziedi Kempings</title>
  <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<main>
<?php
if (isset($_GET['category_id'])) {
    $catId = (int)$_GET['category_id'];
    $category = $conn->query("SELECT * FROM categories WHERE id = $catId")->fetch_assoc();
    if ($category) {
        echo "<h1>" . htmlspecialchars($category['name']) . "</h1>";
        echo "<p>" . nl2br(htmlspecialchars($category['description'])) . "</p>";

        $products = $conn->query("SELECT * FROM products WHERE category_id = $catId");
        if ($products->num_rows > 0) {
            echo "<div class='product-grid'>";
            while ($p = $products->fetch_assoc()) {
                $img = !empty($p['main_image']) ? $p['main_image'] : ( !empty($p['image']) ? $p['image'] : 'images/default.jpg' );
                echo "<div class='product-card'>";
                echo "<img src='../" . htmlspecialchars($img) . "' alt='" . htmlspecialchars($p['name']) . "'>";
                echo "<h3>" . htmlspecialchars($p['name']) . "</h3>";
                $short = !empty($p['short_description']) ? $p['short_description'] : mb_strimwidth($p['description'],0,120,'...');
                echo "<p>" . htmlspecialchars($short) . "</p>";
                echo "<p><strong>Cena:</strong> " . htmlspecialchars($p['price_per_day']) . " €</p>";
                echo "<a href='product_detail.php?id=" . $p['id'] . "' class='btn'>Uzzināt vairāk</a>";
                echo "</div>";
            }
            echo "</div>";
        } else {
            echo "<p>Šajā kategorijā nav produktu.</p>";
        }
    } else {
        echo "<p>Kategorija nav atrasta.</p>";
    }

} else {
    // ja nav norādīta kategorija - parādām visus vai uzaicinājumu izvēlēties
    echo "<h1>Piedāvājumi</h1>";
    echo "<p>Izvēlies kategoriju no izvēlnes vai apskati visus piedāvājumus.</p>";
}
?>
</main>

<?php include '../includes/footer.php'; ?>

</body>
</html>
