<?php
// public/index.php
require_once __DIR__ . '/../src/db.php';
$products = $pdo->query('SELECT * FROM products')->fetchAll();
?>
<!doctype html>
<html>
<head>
 <meta charset="utf-8">
 <title>Egg Tray Shop</title>
 <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
 <header><h1>Egg Tray Shop</h1><a href="cart.php">Cart</a></header>
 <main>
<?php foreach ($products as $p): ?>
 <article class="product">
 <img src="<?= htmlspecialchars($p['image']) ?>" alt="<?=
htmlspecialchars($p['name']) ?>">
 <h2><?= htmlspecialchars($p['name']) ?></h2>
 <p><?= htmlspecialchars($p['description']) ?></p>
 <p><strong>₱<?= format_money($p['price']) ?></strong></p>
 <form action="cart.php" method="post">
 <input type="hidden" name="product_id" value="<?= $p['id'] ?>">
 <label>Qty: <input type="number" name="qty" value="1" min="1" max="<?=
$p['stock'] ?>"></label>
 <button type="submit" name="action" value="add">Add to cart</button>
 </form>
 </article>
<?php endforeach; ?>
 </main>
</body>
</html>
