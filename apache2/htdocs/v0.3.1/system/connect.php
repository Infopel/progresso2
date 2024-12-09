<?php
	$dbhost = 'localhost:3306';
	$dbpass = 'Password';
	$dbuser = 'root';
	$dbname = 'bitnami_redmine';


	$conn = mysqli_connect($dbhost, $dbuser, $dbpass)or die(mysql_error());
	mysql_select_db($dbname, $conn)or die("Nao foi possivel connectar-se a base de dados");
?>