<?php

/**
 * Database class
 * @author Valentin Bourgoin
 *
 * Usage : Database::getInstance->fetch($sql_query)
 * Returns an associative mysql_fetch_array
 * TODO : Switch to PDO
 *
 **/
class Database { 
 	private static $instance;
	private $queries;
	private $db;

	private $host;
	private $user;
	private $pass;
	private $base;
 
	/**
	 * Private constructor
	 *
	 **/
	private function __construct() {
		$this->host = (defined(DB_HOST)) ? DB_HOST : 'localhost';
		$this->user = (defined(DB_USER)) ? DB_USER : 'root';
		$this->pass = (defined(DB_PASS)) ? DB_PASS : '';
		$this->pass = (defined(DB_BASE)) ? DB_BASE : '';
		try {
            $this->db = mysql_connect($this->host, $this->user, $this->pass);
			mysql_select_db($this->base, $this->db);
        } catch(Exception $e) {
            die("Erreur de connexion à la base de données. Ré-essayez plus tard.");
        }
    }
		
	/**
	 * Destructor
	 *
	 **/
	public function __destruct() {
		mysql_close($this->db);
		self::$instance = null;
	}
		
	/**
	 * Singleton
	 *
	 **/
	static function getInstance() {
		if(is_null(self::$instance)) {
			self::$instance = new Database;
		}
		return self::$instance;
	}
	
	/**
	 * Fetch method : get results for SQL query
	 * @param $query String SQL Query
	 * @return Mixed array
	 *
	 **/
	public function fetch($sql) {
		$begin = microtime(true);
		mysql_query('SET NAMES UTF8');
		$result = mysql_query($sql);	
		$results = array() ;
		while ($ligne = @mysql_fetch_array($result)) array_push($results,$ligne);
		@mysql_free_result($result) ;
		$this->queries[] = $sql;
		return $results ;
	}
	
	/**
	 * Exec method 
	 * @param $query String SQL Query
	 * @return Boolean result
	 *
	 **/
	public function exec($sql) {
		mysql_query('SET NAMES UTF8');
		$result = mysql_query($sql);	
		@mysql_free_result($result) ;
		$this->queries[] = $sql;
		return $result ;
	}

	/**
	 * Get done queries
	 *
	 **/
	public function getQueries() {
		return $this->queries;
	}
	
	/**
	 * Get last inserted ID
	 *
	 **/
	public function getInsertedId() {
		return mysql_insert_id();
	}

}
