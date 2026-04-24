<?php
require_once 'config.php';
header('Content-Type: text/plain');

echo "PHP Timezone: " . date_default_timezone_get() . "\n";
echo "PHP Local Time: " . date('Y-m-d H:i:s') . "\n";
echo "PHP UTC Time: " . gmdate('Y-m-d H:i:s') . "\n";

$stmt = $db->query("SELECT NOW() as db_now, @@session.time_zone as session_tz, @@global.time_zone as global_tz");
$row = $stmt->fetch(PDO::FETCH_ASSOC);

echo "MySQL NOW(): " . $row['db_now'] . "\n";
echo "MySQL Session TZ: " . $row['session_tz'] . "\n";
echo "MySQL Global TZ: " . $row['global_tz'] . "\n";

$kickSample = "2026-04-24T04:08:00+00:00";
echo "\nKick Sample (UTC): $kickSample\n";
echo "Parsed with strtotime: " . date('Y-m-d H:i:s', strtotime($kickSample)) . " (Should be local time)\n";
?>
