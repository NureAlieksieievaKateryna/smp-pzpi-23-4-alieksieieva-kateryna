<?php
require 'db.php';

$query = $db->query("SELECT * FROM products");

echo "<h2>Список продуктов:</h2>";
echo "<ul>";
foreach ($query as $row) {
    echo "<li>" . htmlspecialchars($row['name']) . " — $" . htmlspecialchars($row['price']) . "</li>";
}
echo "</ul>";
?>
