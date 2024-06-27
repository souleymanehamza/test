<?php 
// date_default_timezone_set("Africa/Niamey");


	

	try{
		//parametres de connexion
		$hostname = "localhost";
		$user_name = "db_user";
		$password = "qZVKNb5Y:qv9r?Dm";
		$bd_name = "stm_app_db";
		
		$connStr = "mysql:host=".$hostname.";dbname=".$bd_name; 
		$arrExtraParam= array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8");
                $pdo = new PDO($connStr, $user_name, $password, $arrExtraParam); 
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		
		$pdo->query("SET NAMES 'utf8'"); //au cas o� MYSQL_ATTR_INIT_COMMAND ne marche pas          
                $GLOBALS['connexion'] = $pdo;
	}
	catch(PDOException $e) {
		$msg = 'ERREUR PDO dans ' . $e->getFile() . ' L.' . $e->getLine() . ' : ' . $e->getMessage();
		die($msg);
	}
 

// petitapetitloiseaufaitsonnid





