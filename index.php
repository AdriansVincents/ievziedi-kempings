<?php
include 'connect/db_connect.php';
?>

<!DOCTYPE html>
<html lang="lv">
<head>
  <meta charset="UTF-8">
  <title>Ievziedi Kempings</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>

<header>
  <h1>Laipni lūdzam kempingā “Ievziedi”!</h1>
  <nav>
    <a href="index.php">Sākums</a>
    <a href="pages/products.php">Piedāvājumi</a>
    <a href="pages/booking.php">Rezervācija</a>
    <a href="pages/contact.php">Kontakti</a>
  </nav>
</header>

<main>
  <section class="intro">
    <h2>Par mums</h2>
    <p>“Ievziedi” ir gleznains kempings pie dabas, kur vari baudīt mieru, aktīvu atpūtu un ģimenisku atmosfēru. 
       Piedāvājam mājiņas, pirtis, kubulus, laivu un SUP dēļu nomu, kā arī daudz citas iespējas relaksācijai.</p>
  </section>

  <section class="featured">
    <h2>Mūsu populārākie piedāvājumi</h2>
    <?php
    $result = $conn->query("SELECT id, name, description, image FROM products LIMIT 4");

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<div class='product'>";
            echo "<img src='" . htmlspecialchars($row['image']) . "' alt='" . htmlspecialchars($row['name']) . "'>";
            echo "<h3>" . htmlspecialchars($row['name']) . "</h3>";
            echo "<p>" . htmlspecialchars($row['description']) . "</p>";
            echo "<a class='btn' href='pages/product.php?id=" . $row['id'] . "'>Uzzināt vairāk</a>";
            echo "</div>";
        }
    } else {
        echo "<p>Pašlaik nav pieejamu produktu.</p>";
    }
    ?>
  </section>
</main>


<footer>
  <p>&copy; <?php echo date("Y"); ?> Ievziedi Kempings</p>
</footer>

</body>
</html>
