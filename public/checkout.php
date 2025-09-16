<?php
session_start();
require_once __DIR__ . '/../src/db.php';
require_once __DIR__ . '/../src/functions.php';
if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
$action = $_POST['action'] ?? '';
$pid = (int)($_POST['product_id'] ?? 0);
$qty = (int)($_POST['qty'] ?? 1);
if ($action === 'add' && $pid > 0) {
if (isset($_SESSION['cart'][$pid])) {

$_SESSION['cart'][$pid] += $qty;
} else {
$_SESSION['cart'][$pid] = $qty;
}
header('Location: cart.php'); exit;
}
}
$cartItems = [];
$total = 0;
if (!empty($_SESSION['cart'])) {
$ids = implode(',', array_map('intval', array_keys($_SESSION['cart'])));
$stmt = $pdo->query("SELECT * FROM products WHERE id IN ($ids)");
$rows = $stmt->fetchAll();
foreach ($rows as $r) {
$qty = $_SESSION['cart'][$r['id']];
$line = $r;
$line['qty'] = $qty;
$line['line_total'] = $qty * $r['price'];
$total += $line['line_total'];
$cartItems[] = $line;
}
}
?>
<!doctype html>
<html>
<head>
 <meta charset="utf-8">
 <title>Cart — Egg Tray Shop</title>
 <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
 <main>
 <h1>Your Cart</h1>
<?php if (empty($cartItems)): ?>
 <p>Cart is empty — <a href="index.php">shop now</a></p>
<?php else: ?>
 <table>
 <thead><tr><th>Product</th><th>Qty</th><th>Price</th><th>Line</th></
tr></thead>
 <tbody>
<?php foreach ($cartItems as $c): ?>
 <tr>
 <td><?= htmlspecialchars($c['name']) ?></td>
 <td><?= $c['qty'] ?></td>
 <td>₱<?= format_money($c['price']) ?></td>
 <td>₱<?= format_money($c['line_total']) ?></td>

 </tr>
<?php endforeach; ?>
 </tbody>
 </table>
 <p><strong>Total: ₱<?= format_money($total) ?></strong></p>
 <a href="checkout.php">Proceed to checkout</a>
<?php endif; ?>
 </main>
</body>
</html>
