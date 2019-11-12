<?php

include 'config.php';

function connection()
{
	$host = 'mysql:host=localhost;dbname=todo';
	$user = 'root';
	$password = '';
	try{
		$pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASS);
		return $pdo;
	}catch (PDOException $ex){
		echo "Error: ". $ex->getMessage();
	}
}