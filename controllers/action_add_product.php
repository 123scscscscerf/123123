<?php
require_once __DIR__ . '/../config.php';
$action = $_POST['action'] ?? 'add';
if ($action === 'cancel_order') {
    $orderId = filter_input(INPUT_POST, 'order_id', FILTER_VALIDATE_INT);
    $user = current_user();
    if ($orderId && $user) { $stmt=$pdo->prepare("UPDATE orders SET status='cancelled' WHERE id=? AND user_id=? AND status='open'"); $stmt->execute([$orderId,$user['id']]); }
    redirect('../profile.php');
}
if ($action === 'confirm_order') { $_SESSION['flash']='Заказ подтвержден. Спасибо!'; redirect('../profile.php'); }
$productId = filter_input(INPUT_POST, 'product_id', FILTER_VALIDATE_INT);
if ($productId) {
    $stmt = $pdo->prepare('SELECT id FROM products WHERE id = ?'); $stmt->execute([$productId]);
    if ($stmt->fetch()) { $_SESSION['basket'][$productId] = ($_SESSION['basket'][$productId] ?? 0) + 1; }
}
redirect($_SERVER['HTTP_REFERER'] ?? '../basket.php');
