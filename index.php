<?php
include 'connect/db_connect.php';
include 'includes/header.php';
?>

<!DOCTYPE html>
<html lang="lv">
<head>
  <meta charset="UTF-8">
  <title>Ievziedi Kempings – Vieta, kur saplūst cilvēks un daba</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>

<section class="about">
  <div class="about-text">
    <h1>Vieta, kur saplūst cilvēks un daba</h1>
    <p>Kempings <strong>“Ievziedi”</strong> atrodas gleznainā vietā Gaujas krastā. Šeit var baudīt neaizmirstamas dabas ainavas, mieru un klusumu. 
       Piedāvājam dažādus atpūtas veidus gan uz ūdens, gan sauszemes – lai katrs atpūtnieks atrastu sev piemērotāko!</p>
  </div>
  <div class="about-image">
    <img src="images/Sakumam/sakuma1.jpg" alt="Ievziedi Kempings">
  </div>
</section>

<section class="products-preview">
  <h2>Mūsu piedāvājumi</h2>
  <div class="product-grid">

  <?php
  $result = $conn->query("SELECT * FROM products LIMIT 6");
  if ($result && $result->num_rows > 0) {
      while ($p = $result->fetch_assoc()) {
          $img = !empty($p['main_image']) ? $p['main_image'] : ( !empty($p['image']) ? $p['image'] : 'images/default.jpg' );
          echo "<div class='product-card'>";
          echo "<img src='" . htmlspecialchars($img) . "' alt='" . htmlspecialchars($p['name']) . "'>";
          echo "<h3>" . htmlspecialchars($p['name']) . "</h3>";
          $short = !empty($p['short_description']) ? $p['short_description'] : mb_strimwidth($p['description'], 0, 120, '...');
          echo "<p>" . htmlspecialchars($short) . "</p>";
          echo "<a href='pages/product_detail.php?id=" . $p['id'] . "' class='btn'>Uzzināt vairāk</a>";
          echo "</div>";
      }
  } else {
      echo "<p>Pašlaik nav pievienotu produktu.</p>";
  }
  ?>
  </div>
</section>

<?php include 'includes/footer.php'; ?>

</body>
</html>
