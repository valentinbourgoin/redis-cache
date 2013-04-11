<?php

set_time_limit(0);

define('DB_SERVER', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_BASE', '12nete03');

// Load Predis lib
require 'lib/Predis/Autoloader.php';
Predis\Autoloader::register();

// Load DB
require 'lib/Database.php';

// Load Redis Cache lib
require 'lib/Redis-cache/Redis-cache.php';

// Test query
$sql = "SELECT * FROM wp_posts";
$max = 100;

// Compare perf : mysql
$begin = microtime(true);
for($i=0; $i<=$max; $i++) {
	Database::getInstance()->fetch($sql);
}
echo "MySQL : " . (microtime(true) - $begin) . 'ms';
echo '<br />';

// Compare perf : redis
$begin = microtime(true);
for($i=0; $i<=$max; $i++) {
	Cache::getInstance()->fetch($sql);
}
echo "Redis : " . (microtime(true) - $begin) . 'ms';

?>