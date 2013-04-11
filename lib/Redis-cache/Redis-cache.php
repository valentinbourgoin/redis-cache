<?php

class Cache {
	private static $instance;
	private $queries;
	private $db;
 
	/* Constructeur privé */
	private function __construct() {
		try {
			$this->db = new Predis\Client();
		} catch(Exception $e) {
			echo $e->getMessage(); 
			exit;
		}
    }
		
	public function __destruct() {
		self::$instance = null;
	}
		
	/* Singleton */
	static function getInstance() {
		if(is_null(self::$instance)) {
			self::$instance = new Cache;
		}
		return self::$instance;
	}

	public function fetch($query) {
		$begin = microtime(true);
		$result = $this->db->get($query);
		if(!$result) {
			$result = Database::getInstance()->fetch($query);
			$this->db->set($query, serialize($result));
		} else {
			$result = unserialize($result);
		}
		echo "Redis : " . $query . " : " . (microtime(true) - $begin) . "ms <br />";
		return $result;
	}

}