<?php

set_time_limit(0);

// Conf
require 'config.php';

// Load Predis lib
require 'lib/Predis/Autoloader.php';
Predis\Autoloader::register();

// Load Redis Cache lib
require 'lib/Redis-cache/Redis-cache.php';
require 'lib/Redis-cache/Database.php';

// Test query
$sql = "SELECT * FROM ps_category";
$max = 100;

// Compare perf : mysql
$begin = microtime(true);
for($i=0; $i<=$max; $i++) {
	Database::getInstance()->fetch($sql);
}
echo "<b>MySQL total : " . (microtime(true) - $begin) . 's</b>';
echo '<br />';

// Compare perf : redis
$begin = microtime(true);
for($i=0; $i<=$max; $i++) {
	RedisCache::getInstance()->fetch($sql);
}
echo "<b>Redis total : " . (microtime(true) - $begin) . 's</b>';

?>