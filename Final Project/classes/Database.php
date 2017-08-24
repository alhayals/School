<?php

class Database
{
   private static $instance = null;
   private static $connection = null;
   private static $database;
   private static $password;
   private static $server;
   private static $user;
   private $magic_quotes_active;
   private $real_escape_string_exists;


   // any other class variables follow...

   // constructor must be private
   private function __construct()
   {
		self::$database = "final-project";
		self::$password = "";
		self::$server = "localhost";
		self::$user = "root";
		
		$this->magic_quotes_active = get_magic_quotes_gpc();
       $this->real_escape_string_exists = function_exists( "mysql_real_escape_string" );
   }

   // must be public and static:
   public static function getInstance()
   {
      if (!isset(self::$instance))
      {
         $c = __CLASS__;
         self::$instance = new $c;
      }
      return self::$instance;
   }

	public function conn()
	{
		if (!isset(self::$connection))
      	{
			if((self::$connection = mysqli_connect(self::$server, self::$user, self::$password, self::$database)) == null)
			{
				throw new Exception("Failed to connect to database.");
			}
		}
		//return($this->connection);
    }

	public static function dconn()
	{
		if(self::$connection != null)
		{
			mysql_close(self::$connection);
			self::$connection = null;
		}
	}


	public function query($query)
	{
		try
		{
			if(!self::$connection->select_db(self::$database))
				throw new Exception("Failed to select database.");

			return(mysqli_query(self::$connection, $query));
		}

		catch(Exception $e)
		{
			throw($e);
		}
	}
	
	public function escape_value($value ) {
		if( $this->real_escape_string_exists ) { // PHP v4.3.0 or higher
			// undo any magic quote effects so mysql_real_escape_string can do the work
			if( $this->magic_quotes_active ) { $value = stripslashes( $value ); }
			$value = mysqli_real_escape_string(self::$connection, $value );
		} 
		return $value;
	}

	public function fetch_array($result_set)
	{
		return mysqli_fetch_array($result_set);
	}

	public function affected_rows() {
		return mysqli_affected_rows($this->connection);
	}

   // prevent users from cloning this instance:
   public function __clone()
   {
      throw new Exception('Clone is not allowed.');
   }

}
$db = Database::getInstance();
$db->conn();

?>