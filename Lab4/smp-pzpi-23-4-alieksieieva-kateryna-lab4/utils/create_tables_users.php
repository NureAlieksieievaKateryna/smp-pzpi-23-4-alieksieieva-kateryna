<?php
$dbFile = __DIR__ . '/users.db';
$db = new PDO('sqlite:' . $dbFile);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Створити таблицю, якщо ще немає
$db->exec("
    CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        username TEXT NOT NULL UNIQUE,
        password TEXT NOT NULL
    );
");

// Видалити існуючого користувача (якщо був)
$db->prepare("DELETE FROM users WHERE username = ?")->execute(['user']);

// Створити нового користувача з хешованим паролем
$hashedPassword = password_hash('password', PASSWORD_DEFAULT);
$db->prepare("INSERT INTO users (username, password) VALUES (?, ?)")
   ->execute(['user', $hashedPassword]);

echo "Користувач 'user' доданий з паролем 'password'\n";
