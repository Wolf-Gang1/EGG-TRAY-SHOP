<?php
require_once __DIR__ . '/db.php';
function format_money($amount) {
return number_format((float)$amount, 2);
}
function append_transaction_csv($orderRef, $total, $status, $created_at) {
$file = __DIR__ . '/../data/transactions.csv';
$exists = file_exists($file);
$fh = fopen($file, 'a');
if (!$exists) {
fputcsv($fh, ['order_ref','total','status','created_at']);
}
fputcsv($fh, [$orderRef, $total, $status, $created_at]);
fclose($fh);
}
