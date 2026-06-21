<?php
require_once __DIR__ . '/config.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'checkout') {
    $basket = $_SESSION['basket'] ?? [];
    if (!$basket) { $_SESSION['flash']='Корзина пуста.'; redirect('basket.php'); }
    $ids = array_keys($basket); $placeholders = implode(',', array_fill(0,count($ids),'?'));
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id IN ($placeholders)"); $stmt->execute($ids); $items = $stmt->fetchAll();
    $total = 0; foreach($items as $item){ $total += (float)$item['price'] * $basket[$item['id']]; }
    $user = current_user(); $number = (string)random_int(100000000,999999999);
    $stmt=$pdo->prepare('INSERT INTO orders (order_number,user_id,customer_name,customer_email,customer_phone,delivery_address,payment_method,status,total,order_date,delivery_date) VALUES (?,?,?,?,?,?,?,?,?,CURDATE(),DATE_ADD(CURDATE(), INTERVAL 14 DAY))');
    $stmt->execute([$number,$user['id']??null,trim($_POST['name']),trim($_POST['email']),trim($_POST['phone']),trim($_POST['address']),trim($_POST['payment_method']),'open',$total]);
    $orderId=(int)$pdo->lastInsertId(); $itemStmt=$pdo->prepare('INSERT INTO order_items (order_id,product_id,quantity,price) VALUES (?,?,?,?)');
    foreach($items as $item){ $itemStmt->execute([$orderId,$item['id'],$basket[$item['id']],$item['price']]); }
    unset($_SESSION['basket']); redirect('order.php?id='.$orderId);
}
$basket = $_SESSION['basket'] ?? []; $products=[]; $total=0;
if ($basket) { $ids=array_keys($basket); $stmt=$pdo->prepare('SELECT * FROM products WHERE id IN ('.implode(',',array_fill(0,count($ids),'?')).')'); $stmt->execute($ids); $products=$stmt->fetchAll(); foreach($products as $p){$total+=(float)$p['price']*$basket[$p['id']];} }
$user=current_user(); site_header('Корзина — COMFY');
?>
<main class="container"><h1 class="section-title">Корзина</h1><?php if(!empty($_SESSION['flash'])): ?><p class="flash"><?= e($_SESSION['flash']); unset($_SESSION['flash']); ?></p><?php endif; ?><?php foreach($products as $p): ?><div class="basket-row"><img class="thumb" src="<?= e($p['image']) ?>" alt=""><h2><?= e($p['title']) ?></h2><span class="btn btn-dark btn-small" style="margin-left:auto"><?= (int)$p['price'] ?>.тг</span></div><?php endforeach; ?><div class="sum-line"><strong class="btn btn-dark">Общая сумма: <?= (int)$total ?>.тг</strong></div><form class="checkout" method="post"><input type="hidden" name="action" value="checkout"><h2 class="section-title" style="margin-top:0">Отправить заказ</h2><div class="checkout-grid"><label class="field">ФИО:<input name="name" value="<?= e($user['name'] ?? '') ?>" required></label><label class="field">Email:<input type="email" name="email" value="<?= e($user['email'] ?? '') ?>" required></label><label class="field">Телефон:<input name="phone" value="<?= e($user['phone'] ?? '') ?>" required></label><label class="field">Адресс:<input name="address" value="<?= e($user['address'] ?? '') ?>" required></label><label class="field wide">Выберите метод оплаты:<select name="payment_method" required><option value="Visa **56">Visa **56</option><option value="Наличными">Наличными</option></select></label></div><p class="center"><button class="btn-dark">Отправить</button><br><a href="order.php">Подтвердить заказ</a></p></form></main><?php site_footer(); ?>
