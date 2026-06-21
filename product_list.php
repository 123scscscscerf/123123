<?php
require_once __DIR__ . '/config.php';
$sort = $_GET['sort'] ?? 'id';
$order = $sort === 'price' ? 'p.price ASC' : ($sort === 'popular' ? 'p.is_popular DESC, p.id ASC' : 'p.id ASC');
$categoryId = filter_input(INPUT_GET, 'category', FILTER_VALIDATE_INT);
$sql = 'SELECT p.*, c.title AS category_title FROM products p JOIN categories c ON c.id=p.category_id'; $params=[];
if ($categoryId) { $sql .= ' WHERE p.category_id = ?'; $params[] = $categoryId; }
$sql .= ' ORDER BY ' . $order;
$stmt = $pdo->prepare($sql); $stmt->execute($params); $products = $stmt->fetchAll(); site_header('Категории товаров — COMFY');
?>
<main class="container"><h1 class="section-title">Категории товаров</h1><div class="filters"><a class="btn btn-dark" href="product_list.php?sort=price<?= $categoryId ? '&category='.(int)$categoryId : '' ?>">По цене</a><a class="btn btn-dark" href="product_list.php?sort=popular<?= $categoryId ? '&category='.(int)$categoryId : '' ?>">По популярности</a></div><section class="products-grid catalog-grid"><?php foreach($products as $p): ?><article class="product-card"><img src="<?= e($p['image']) ?>" alt="<?= e($p['title']) ?>"><h3 class="product-title"><a href="product_list.php?category=<?= (int)$p['category_id'] ?>"><?= e($p['title']) ?></a></h3><div class="product-bottom"><span class="price"><?= (int)$p['price'] ?></span><form action="controllers/action_add_product.php" method="post"><input type="hidden" name="product_id" value="<?= (int)$p['id'] ?>"><button class="btn-dark btn-small">В корзину</button></form></div></article><?php endforeach; ?></section></main><?php site_footer(); ?>
