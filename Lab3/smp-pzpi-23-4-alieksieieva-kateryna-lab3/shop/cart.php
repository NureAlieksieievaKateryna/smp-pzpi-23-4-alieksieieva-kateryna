<?php
session_start();
require 'db.php';

if (isset($_GET['remove'])) {
    $id = $_GET['remove'];
    unset($_SESSION['cart'][$id]);
    header("Location: cart.php");
    exit;
}

$cart = $_SESSION['cart'] ?? [];
?>
<!DOCTYPE html>
<html>
<head>
    <title>Кошик</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<header><h1>Інтернет-магазин Весна</h1></header>
<nav class="menu">
    <a href="home.php">Головна</a>
    <a href="index.php">Товари</a>
    <a href="cart.php">Кошик</a>
</nav>

<main>
<?php if (empty($cart)): ?>
    <p>Кошик порожній. <a href="index.php">Перейти до покупок</a></p>
<?php else:
    $placeholders = implode(',', array_fill(0, count($cart), '?'));
    $stmt = $db->prepare("SELECT * FROM products WHERE id IN ($placeholders)");
    $stmt->execute(array_keys($cart));
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $total = 0;
?>
    <h2>Ваш кошик</h2>
    <table class="cart-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Назва</th>
                <th>Ціна за одиницю</th>
                <th>Кількість</th>
                <th>Сума</th>
                <th>Дія</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($products as $product):
            $qty = $cart[$product['id']];
            $sum = $qty * $product['price'];
            $total += $sum;
        ?>
            <tr>
                <td><?= $product['id'] ?></td>
                <td><?= htmlspecialchars($product['name']) ?></td>
                <td><?= $product['price'] ?> грн</td>
                <td><?= $qty ?></td>
                <td><?= $sum ?> грн</td>
                <td><a href="?remove=<?= $product['id'] ?>" class="remove">Видалити</a></td>
            </tr>
        <?php endforeach; ?>
        <tr class="total-row">
            <td colspan="4" style="text-align: right;"><strong>Загальна сума:</strong></td>
            <td colspan="2"><strong><?= $total ?> грн</strong></td>
        </tr>
        </tbody>
    </table>
    <a href="index.php">Продовжити покупки</a>
<?php endif; ?>
</main>

<footer style="color:white;"><p><a href="home.php" style="color:white;">Головна</a> | <a href="index.php" style="color:white;">Товари</a> | <a href="cart.php" style="color:white;">Кошик</a></p></footer>
</body>
</html>
