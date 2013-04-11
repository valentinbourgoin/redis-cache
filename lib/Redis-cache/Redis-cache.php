<?php

/**
 * Redis Cache for SQL queries
 * @author Valentin Bourgoin
 *
 * Usage : RedisCache::getInstance->fetch($sql_query)
 * Returns an associative array
 *
 **/
class RedisCache {
	private static $instance;
	private $db;

	private $host;
	private $port;

	private $time;
 
	/**
	 * Private constructor
	 *
	 **/
	private function __construct() {
		$this->host = (defined(REDIS_HOST)) ? REDIS_HOST : 'localhost';
		$this->port = (defined(REDIS_PORT)) ? REDIS_PORT : '6397';
		$this->time = (defined(CACHE_TIME)) ? CACHE_TIME : '3600';

		try {			
			$this->db = new Predis\Client(array(
				'host' => $this->host,
				'post' => $this->port,
			));
		} catch(Exception $e) {
			echo $e->getMessage(); 
			exit;
		}
    }
		
	/**
	 * Destructor
	 *
	 **/
	public function __destruct() {
		self::$instance = null;
	}
		
	/**
	 * Singleton
	 *
	 **/
	static function getInstance() {
		if(is_null(self::$instance)) {
			self::$instance = new RedisCache;
		}
		return self::$instance;
	}

	/**
	 * Fetch method : get results for SQL query
	 * with mysql_fetch_array or from Redis cache
	 * @param $query String SQL Query
	 *
	 **/
	public function fetch($query) {
		$key = "Query:" . md5($query);
		$result = $this->db->get($key);
		if(!$result) {
			$result = Database::getInstance()->fetch($query);
			$this->db->set($key, serialize($result));
			$this->db->expire($key, $this->time);
		} else {
			$result = unserialize($result);
		}
		return $result;
	}

}