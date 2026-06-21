<?php
declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

const DB_HOST = '127.0.0.1';
const DB_NAME = 'comfy_shop';
const DB_USER = 'root';
const DB_PASS = '';

try {
    $pdo = new PDO(
        'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );
} catch (PDOException $e) {
    http_response_code(500);
    exit('Ошибка подключения к базе данных. Проверьте config.php и импорт schema.sql.');
}

function e(?string $value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function current_user(): ?array
{
    return $_SESSION['user'] ?? null;
}

function redirect(string $url): never
{
    header('Location: ' . $url);
    exit;
}

function basket_count(): int
{
    return array_sum($_SESSION['basket'] ?? []);
}

function site_header(string $title = 'COMFY'): void
{
    $user = current_user();
    ?>
    <!doctype html>
    <html lang="ru">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?= e($title) ?></title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;700;800&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="assets/styles.css">
    </head>
    <body>
    <header class="site-header">
        <a class="logo" href="index.php">COMFY.</a>
        <nav class="main-nav" aria-label="Главная навигация">
            <a href="index.php">Главная</a>
            <a href="index.php#about">Описание</a>
            <a href="product_list.php">Категории</a>
            <a href="basket.php">Корзина</a>
        </nav>
        <div class="header-actions">
            <a class="btn btn-dark" href="basket.php">Корзина<?= basket_count() ? ' (' . basket_count() . ')' : '' ?></a>
            <?php if ($user): ?>
                <a class="user-link" href="profile.php"><?= e($user['name'] ?: $user['email']) ?></a>
            <?php else: ?>
                <a class="btn btn-dark" href="login.php">Войти</a>
            <?php endif; ?>
        </div>
    </header>
    <?php
}

function site_footer(): void
{
    ?>
    <footer class="site-footer">
        <div class="socials"><a>Facebook</a><a>Instagram</a><a>Pinterest</a><a>Telegram</a></div>
        <strong>+1(111)111-11-11</strong>
    </footer>
    </body></html>
    <?php
}
