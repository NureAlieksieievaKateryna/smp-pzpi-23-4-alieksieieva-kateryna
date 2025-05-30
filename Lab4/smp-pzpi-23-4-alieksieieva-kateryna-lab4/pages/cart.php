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
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Кошик - Інтернет-магазин Весна</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php include 'header.php'; ?>

    <main class="cart-container">
        <?php if (empty($cart)): ?>
            <div class="empty-cart">
                <p>Ваш кошик порожній</p>
                <a href="index.php" class="continue-shopping">Повернутися до товарів</a>
            </div>
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
                        <th>Назва товару</th>
                        <th>Ціна</th>
                        <th>Кількість</th>
                        <th>Сума</th>
                        <th>Дії</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($products as $product):
                    $qty = $cart[$product['id']];
                    $sum = $qty * $product['price'];
                    $total += $sum;
                ?>
                    <tr>
                        <td><?= htmlspecialchars($product['name']) ?></td>
                        <td><?= $product['price'] ?> грн</td>
                        <td><?= $qty ?></td>
                        <td><?= $sum ?> грн</td>
                        <td><a href="?remove=<?= $product['id'] ?>" class="remove">Видалити</a></td>
                    </tr>
                <?php endforeach; ?>
                <tr class="total-row">
                    <td colspan="3" style="text-align: right;"><strong>Загальна сума:</strong></td>
                    <td colspan="2"><strong><?= $total ?> грн</strong></td>
                </tr>
                </tbody>
            </table>
            
            <div class="cart-actions">
                <a href="index.php" class="continue-shopping">Продовжити покупки</a>
                <?php if (isset($_SESSION['username'])): ?>
                    <a href="checkout.php" class="continue-shopping" style="background-color: #28a745; margin-left: 10px;">Оформити замовлення</a>
                <?php else: ?>
                    <a href="login.php" class="continue-shopping" style="background-color: #ffc107; margin-left: 10px;">Увійти для оформлення</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </main>

    <?php include 'footer.php'; ?>
</body>
</html>
