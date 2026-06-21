<?php
require_once __DIR__ . '/config.php';
$orderId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if ($orderId) { $stmt=$pdo->prepare('SELECT * FROM orders WHERE id=?'); $stmt->execute([$orderId]); }
else { $stmt=$pdo->prepare("SELECT * FROM orders WHERE order_number=?"); $stmt->execute(['334910264']); }
$order = $stmt->fetch();
if (!$order) { http_response_code(404); exit('Заказ не найден'); }
$stmt=$pdo->prepare('SELECT oi.*, p.title, p.image FROM order_items oi JOIN products p ON p.id=oi.product_id WHERE oi.order_id=?'); $stmt->execute([$order['id']]); $items=$stmt->fetchAll(); site_header('Заказ — COMFY');
?>
<main class="container"><section class="order-meta"><h1>Номер заказа: <?= e($order['order_number']) ?></h1><p>Дата заказа: <i><?= e(date('j F, Y', strtotime($order['order_date']))) ?></i> Дата доставки: <i><?= e(date('j F, Y', strtotime($order['delivery_date']))) ?></i></p></section><section class="order-info"><div><h2>Оплата</h2><p><?= e($order['payment_method']) ?></p></div><div><h2>Доставка</h2><p><?= e($order['customer_name']) ?><br><?= e($order['customer_email']) ?><br><?= e($order['delivery_address']) ?><br><?= e($order['customer_phone']) ?></p></div></section><?php foreach($items as $item): ?><div class="order-item"><img class="thumb" src="<?= e($item['image']) ?>" alt=""><h3><?= e($item['title']) ?></h3><strong style="margin-left:auto"><?= (int)$item['price'] ?></strong></div><?php endforeach; ?><form class="confirm-box" action="controllers/action_add_product.php" method="post"><input type="hidden" name="action" value="confirm_order"><button class="btn-dark">Подтвердить заказ</button></form></main><?php site_footer(); ?>
