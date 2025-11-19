<?php
$basePath = '/ievziedi_kempings/';
?>
<header>
  <div class="navbar">
    <div class="logo">
      <a href="<?php echo $basePath; ?>index.php">
        <img src="<?php echo $basePath; ?>images/logo/logo.jpg" alt="Ievziedi Kempings logo">
      </a>
    </div>

    <nav class="main-menu">
      <ul>
        <li><a href="<?php echo $basePath; ?>index.php">Sākums</a></li>
        <li class="dropdown">
          <a href="#">Piedāvājumi</a>
          <ul class="dropdown-menu">
            <?php
            $mainCats = $conn->query("SELECT * FROM categories WHERE parent_id IS NULL");
            while ($main = $mainCats->fetch_assoc()) {
                echo "<li class='category-group'><span class='category-title'>" . htmlspecialchars($main['name']) . "</span>";

                $subs = $conn->query("SELECT * FROM categories WHERE parent_id = " . $main['id']);
                if ($subs->num_rows > 0) {
                    echo "<ul class='sub-menu'>";
                    while ($sub = $subs->fetch_assoc()) {
                        $prod = $conn->query("SELECT id FROM products WHERE category_id = " . $sub['id'] . " LIMIT 1");
                        if ($prod && $prod->num_rows > 0) {
                            $p = $prod->fetch_assoc();
                            echo "<li><a href='" . $basePath . "pages/product_detail.php?id=" . $p['id'] . "'>" . htmlspecialchars($sub['name']) . "</a></li>";
                        } else {
                            echo "<li><span class='disabled-link'>" . htmlspecialchars($sub['name']) . "</span></li>";
                        }
                    }
                    echo "</ul>";
                }
                echo "</li>";
            }
            ?>
          </ul>
        </li>
        <li><a href="<?php echo $basePath; ?>pages/booking.php">Rezervācija</a></li>
        <li><a href="<?php echo $basePath; ?>pages/contact.php">Kontakti</a></li>
      </ul>
    </nav>
  </div>
</header>
