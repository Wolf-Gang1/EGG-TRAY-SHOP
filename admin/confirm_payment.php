<?php
// admin/confirm_payment.php
require_once __DIR__ . '/../src/db.php';
require_once __DIR__ . '/../src/functions.php';
$orderRef = $_POST['order_ref'] ?? '';
if (!$orderRef) {
die('Missing order reference');
}
// Mark last matching order as paid
$stmt = $pdo->prepare('UPDATE orders SET status = ? WHERE order_ref = ?');
$stmt->execute(['paid', $orderRef]);
// append another CSV row to mark paid event (or update previous) — simple
approach: append a paid entry
append_transaction_csv($orderRef, 0, 'paid', date('Y-m-d H:i:s'));
// Redirect to a simple confirmation page
header('Location: dashboard.php?msg=paid');
exit;
