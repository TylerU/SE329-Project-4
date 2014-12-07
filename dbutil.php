<?php

// Constants for storing database credentials
$dbUsername = "u319all";
$dbPassword = "024IjLaMj4dI";
$dbServer = "mysql.cs.iastate.edu"; 
$dbName   = "db319all";

// Local version
$dbUsername = "root";
$dbPassword = "";
$dbServer = "localhost"; 
$dbName   = "329proj4";

class DB
{
	static $conn = null;

	//connection to the database
	function getConnection(){
		if(is_resource(self::$conn))
			return self::$conn;

		global $dbUsername, $dbPassword, $dbServer, $dbName;
		self::$conn = mysqli_connect($dbServer, $dbUsername, $dbPassword, $dbName);
		if(mysqli_connect_errno()){
			die("Failed to connect to MYSQL: " . mysqli_connect_errno());
			return;
		}
		return self::$conn;
	}

	function query($queryStr){
		$conn = self::getConnection();
		$result = mysqli_query($conn, $queryStr);
		return $result;
	}
}

?>


