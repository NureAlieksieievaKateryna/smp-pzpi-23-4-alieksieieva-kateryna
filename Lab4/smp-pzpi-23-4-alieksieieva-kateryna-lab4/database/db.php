<?php
// session_start(); ❌ УДАЛИТИ ЦЕЙ РЯДОК

// Подключение к SQLite базе
$db = new PDO('sqlite:users.db');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Функция аутентификации пользователя
function authenticateUser($username, $password) {
    global $db;
    $stmt = $db->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        return $user;
    }
    return false;
}

// Проверка авторизации
function isLoggedIn() {
    return isset($_SESSION['user']);
}
