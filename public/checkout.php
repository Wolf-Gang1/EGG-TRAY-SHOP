<?php
session_start();
require_once __DIR__ . '/../src/db.php';
require_once __DIR__ . '/../src/functions.php';
if (empty($_SESSION['cart'])) {
header('Location: index.php'); exit;
}
// Build cart items again
$ids = implode(',', array_map('intval', array_keys($_SESSION['cart'])));
$stmt = $pdo->query("SELECT * FROM products WHERE id IN ($ids)");
$rows = $stmt->fetchAll();
$total = 0;
foreach ($rows as $r) {
$qty = $_SESSION['cart'][$r['id']];
$total += $qty * $r['price'];
}
// Create order
$orderRef = 'ORD' . time() . rand(100,999);
$pdo->beginTransaction();
try {
$ins = $pdo->prepare('INSERT INTO orders (order_ref, total, status) VALUES
(?, ?, ?)');
$ins->execute([$orderRef, $total, 'pending']);
$orderId = $pdo->lastInsertId();
$insItem = $pdo->prepare('INSERT INTO order_items (order_id, product_id,
qty, price) VALUES (?, ?, ?, ?)');
foreach ($rows as $r) {
$qty = $_SESSION['cart'][$r['id']];
$insItem->execute([$orderId, $r['id'], $qty, $r['price']]);
}
$pdo->commit();
// append to CSV log for reports (status pending)
append_transaction_csv($orderRef, $total, 'pending', date('Y-m-d H:i:s'));
// clear cart
unset($_SESSION['cart']);
} catch (Exception $e) {
$pdo->rollBack();
die('Order failed: ' . $e->getMessage());
}
// Path to your QR image - put the QR image into public/assets/img/
payment_qr.png
$qrImage = 'assets/img/payment_qr.png';
?>
<!doctype html>
<html>
<head>
 <meta charset="utf-8">
 <title>Checkout — Egg Tray Shop</title>
 <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
 <main>
 <h1>Checkout</h1>
 <p>Order reference: <strong><?= htmlspecialchars($orderRef) ?></strong></p>
 <p>Total amount: <strong>₱<?= format_money($total) ?></strong></p>
 <section>
 <h2>Pay using QR</h2>
 <p>Scan this QR with your banking app to pay. After paying, click the
button "I have paid" so we can confirm the payment.</p>
 <img src="<?= $qrImage ?>" alt="Payment QR" style="max-width:260px;">
 <form action="../admin/confirm_payment.php" method="post">
 <input type="hidden" name="order_ref" value="<?=
htmlspecialchars($orderRef) ?>">
 <button type="submit">I have paid — notify seller</button>
 </form>
 </section>
 </main>
</body>
</html>
