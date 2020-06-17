<?php
	session_start();
	include "mysql/mysql_connect.php";
	$login = $_POST["login"];
	$result = mysqli_query($mysql, "SELECT * FROM `$TABLE_ACCOUNTS` WHERE `login` = '$login'");
	if(!$result) echo "<script>window.location = \"index.php\";</script>";
	else $pass = mysqli_fetch_assoc($result);
	$apass = $pass['password'];
	if($login != '' & $_POST["password"] != '') {
		if($_POST["password"] == $pass["password"]) {
			$_SESSION["login"] = $login;
			mysqli_query($mysql, "UPDATE $TABLE_ACCOUNTS SET online = '1' WHERE login = '$login'");
			echo "<script>window.location = \"index.php?action=3\";</script>";
		} else {
			echo "<script>window.location = \"index.php?action=1\";</script>";
		} 
	} else echo "<script>window.location = \"index.php?action=2\";</script>";
	mysqli_close($mysql);
?>
