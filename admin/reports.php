<?php
// admin/reports.php
require_once __DIR__ . '/../src/functions.php';
$file = __DIR__ . '/../data/transactions.csv';
$rows = [];
if (($h = fopen($file,'r')) !== false) {
$headers = fgetcsv($h);
while (($r = fgetcsv($h)) !== false) {
$rows[] = array_combine($headers, $r);
}
fclose($h);
}
// Filter by period parameter: daily, weekly, monthly

$period = $_GET['period'] ?? 'daily';
$groups = [];
foreach ($rows as $r) {
$dt = new DateTime($r['created_at']);
if ($period === 'daily') {
$key = $dt->format('Y-m-d');
} elseif ($period === 'weekly') {
$key = $dt->format('o-\W\W') . '-' . $dt->format('W'); // week number
} else {
$key = $dt->format('Y-m');
}
if (!isset($groups[$key])) $groups[$key] = ['count'=>0,'total'=>0.0];
$groups[$key]['count'] += 1;
$groups[$key]['total'] += (float)$r['total'];
}
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Reports</title></head>
<body>
 <h1>Reports (<?= htmlspecialchars($period) ?>)</h1>
 <nav><a href="reports.php?period=daily">Daily</a> | <a href="reports.php?
period=weekly">Weekly</a> | <a href="reports.php?period=monthly">Monthly</a></
nav>
 <table border="1">
 <thead><tr><th>Period</th><th>Orders</th><th>Total</th></tr></thead>
 <tbody>
<?php foreach ($groups as $k => $g): ?>
 <tr>
 <td><?= htmlspecialchars($k) ?></td>
 <td><?= $g['count'] ?></td>
 <td>₱<?= format_money($g['total']) ?></td>
 </tr>
<?php endforeach; ?>
 </tbody>
 </table>
</body>
</html>
