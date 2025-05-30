<header>
    <h1>🌸 Интернет-магазин <strong>Весна</strong></h1>
    <a href="/">
        <span class="nav-icon">🏠</span> Головна
    </a>
    <a href="/index.php">
        <span class="nav-icon">📦</span> Продукти
    </a>
    <?php if (isset($_SESSION['username'])) : ?>
    <a href="/cart.php">
        <span class="nav-icon">🛒</span> Кошик
    </a>
    <a href="/myprofile.php">
        <span class="nav-icon">👤</span> Профіль
    </a>
    <a href="/login.php">
        <span class="nav-icon">🔓</span> Вихід
    </a>
    <?php else : ?>
    <a href="/login">
        <span class="nav-icon">🔐</span> Логін
    </a>
    <?php endif; ?>
</header>
