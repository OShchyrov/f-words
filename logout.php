<?php
	session_start();
	include "mysql/mysql_connect.php";
	$login = $_SESSION["login"];
	mysqli_query($mysql, "UPDATE `$TABLE_ACCOUNTS` SET `online` = '0' WHERE `login` = '$login'");
	unset($_SESSION["login"]);
	mysqli_close($mysql);
	echo "<script>window.location = \"index.php\";</script>";
?>