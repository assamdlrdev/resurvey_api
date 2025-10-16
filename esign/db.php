<?php

class Database
{
	private static $config=null; 
	private static $dbName=null; 
	private static $dbHost=null; 
	private static $dbUsername=null; 
	private static $dbUserPassword=null;
	public static $cont  = null;
	public static $options  = null;
	
	public function __construct() {
		exit('Init function is not allowed');
	}
	
	public static function connect($database)
	{
	   // One connection through whole application
       if ( null == self::$cont )
       {      
        try 
        {
          // $config = parse_ini_file('db_bais.ini');
          // $dbName=$config['db'];
          // $dbHost=$config['ho'];
          // $dbUsername=$config['us'];
          // $dbUserPassword=$config['pa'];

          $dbName = $database;
          $dbHost = 'localhost';
          $dbUsername = 'postgres';
          $dbUserPassword = 'postgres';

          //self::$cont =  new PDO( "mysql:host=".self::$dbHost.";"."dbname=".self::$dbName, self::$dbUsername, self::$dbUserPassword);
          self::$cont = new PDO("pgsql:host={$dbHost};dbname={$dbName}", $dbUsername, $dbUserPassword);
            
        }
        catch(PDOException $e) 
        {
          die($e->getMessage());  
        }
       } 
       return self::$cont;
	}
	
	public static function disconnect()
	{
		self::$cont = null;
	}
	
}

?>