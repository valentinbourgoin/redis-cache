<?php

class Database { 
 	private static $instance;
	private $queries;
	private $db;
 
	/* Constructeur privé */
	private function __construct() {	
		 try {
            $this->db = mysql_connect(DB_SERVER, DB_USER, DB_PASS);
			mysql_select_db(DB_BASE, $this->db);
        } catch(Exception $e) {
            die("Erreur de connexion à la base de données. Ré-essayez plus tard.");
        }
    }
		
	public function __destruct() {
		mysql_close($this->db);
		self::$instance = null;
	}
		
	/* Singleton */
	static function getInstance() {
		if(is_null(self::$instance)) {
			self::$instance = new Database;
		}
		return self::$instance;
	}
	
	/* Requête de retour */
	public function fetch($sql) {
		$begin = microtime(true);
		mysql_query('SET NAMES UTF8');
		$result = mysql_query($sql);	
		$results = array() ;
		while ($ligne = @mysql_fetch_array($result)) array_push($results,$ligne);
		@mysql_free_result($result) ;
		$this->queries[] = $sql;
		echo "MySQL : " . $sql . " : " . (microtime(true) - $begin) . "ms <br />";
		return $results ;
	}
	
	/* Requête d'éxecution */
	public function exec($sql) {
		mysql_query('SET NAMES UTF8');
		$result = mysql_query($sql);	
		@mysql_free_result($result) ;
		$this->queries[] = $sql;
		return $result ;
	}

	public function getQueries() {
		return $this->queries;
	}
	
	/* Dernière ID inserée */
	public function getInsertedId() {
		return mysql_insert_id();
	}

}
