<?php
session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $qty = (int)$_POST['quantity'];
    if ($qty > 0) {
        $_SESSION['cart'][$id] = ($_SESSION['cart'][$id] ?? 0) + $qty;
    }
}

$products = $db->query("SELECT * FROM products")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="styles.css">
    <title>Товари</title>
</head>
<body>
<header><h1>Інтернет-магазин Весна</h1></header>
<nav class="menu">
    <a href="home.php">Головна</a>
    <a href="index.php">Товари</a>
    <a href="cart.php">Кошик</a>
</nav>

<main>
<?php foreach ($products as $product): ?>
    <form method="POST">
        <h3><?= htmlspecialchars($product['name']) ?> — <?= $product['price'] ?> грн</h3>
        <input type="hidden" name="id" value="<?= $product['id'] ?>">
        Кількість: <input type="number" name="quantity" value="1" min="1">
        <button type="submit">Купити</button>
    </form>
    <hr>
<?php endforeach; ?>
<a href="cart.php">Перейти до кошика</a>
</main>

<footer style="color:white;"><p><a href="home.php" style="color:white;">Головна</a> | <a href="index.php" style="color:white;">Товари</a> | <a href="cart.php" style="color:white;">Кошик</a></p></footer>

</body>
</html>
