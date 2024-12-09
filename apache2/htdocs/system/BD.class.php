<?php
class BD{
	private static $conn;
	public function __construct(){}

	public function conn(){

		if(is_null(self::$conn)){
			self::$conn = new PDO('mysql:host='.HOST.';dbname='.BD.'',''.USER.'', ''.PASS.'',
				array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); // all the responses from database to UTF-8
		}
		return self::$conn;
	}
}
