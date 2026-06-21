<?php
require_once __DIR__ . '/config.php';
$stmt = $pdo->prepare('SELECT p.*, c.title AS category_title FROM products p JOIN categories c ON c.id=p.category_id ORDER BY p.id LIMIT 6');
$stmt->execute(); $products = $stmt->fetchAll(); site_header('COMFY — магазин сувениров');
?>
<main class="container"><section id="about" class="hero"><h1>Краткая информация</h1><p class="lead">COMFY — интернет-магазин памятных сувениров, авторских подарков и декоративных товаров с быстрой доставкой.</p></section><h2 class="section-title">Категории товаров</h2><section class="products-grid"><?php foreach($products as $p): ?><article class="product-card"><img src="<?= e($p['image']) ?>" alt="<?= e($p['title']) ?>"><h3 class="product-title"><?= e($p['category_title']) ?></h3><div class="product-bottom"><span class="price"><?= (int)$p['price'] ?>.тг</span><form action="controllers/action_add_product.php" method="post"><input type="hidden" name="product_id" value="<?= (int)$p['id'] ?>"><button class="btn-dark btn-small">В корзину</button></form></div></article><?php endforeach; ?></section></main><?php site_footer(); ?>
