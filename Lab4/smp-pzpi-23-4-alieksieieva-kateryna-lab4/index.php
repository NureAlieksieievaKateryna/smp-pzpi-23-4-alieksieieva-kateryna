<?php
session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = $_POST['id'];
    $qty = (int)$_POST['quantity'];
    if ($qty > 0) {
        $_SESSION['cart'][$id] = ($_SESSION['cart'][$id] ?? 0) + $qty;
    }
}

$products = $db->query("SELECT * FROM products")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Інтернет-магазин Весна - Товари</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php include 'header.php'; ?>

    <main class="container">
        <h1>Наші товари</h1>
        
        <?php if (!empty($products)): ?>
            <div class="products-list">
                <?php foreach ($products as $product): ?>
                    <div class="product-card">
                        <h3><?= htmlspecialchars($product['name']) ?></h3>
                        <p>Ціна: <?= $product['price'] ?> грн</p>
                        
                        <form method="POST" class="product-form">
                            <input type="hidden" name="id" value="<?= $product['id'] ?>">
                            <label>Кількість:
                                <input type="number" name="quantity" value="1" min="1">
                            </label>
                            <button type="submit">Додати до кошика</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <a href="cart.php" class="cart-link">Перейти до кошика (<?= array_sum($_SESSION['cart'] ?? []) ?>)</a>
        <?php else: ?>
            <p>Наразі товарів немає в наявності.</p>
        <?php endif; ?>
    </main>

    <?php include 'footer.php'; ?>
</body>
</html>
