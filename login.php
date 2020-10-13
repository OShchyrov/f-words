<?php
	session_start();
	include "mysql/mysql_connect.php";
	$login = $_POST["login"];
	$result = mysqli_query($mysql, "SELECT * FROM `$TABLE_ACCOUNTS` WHERE `login` = '$login'");
	if(!$result) echo "<script>window.location = \"index.php\";</script>";
	else $pass = mysqli_fetch_assoc($result);
	$apass = $pass['password'];
	$id = $pass["id"];
	if($login != '' & $_POST["password"] != '') {
		if($_POST["password"] == $pass["password"]) {
			$_SESSION["login"] = $login;
			$_SESSION["uid"] = $id;
			mysqli_query($mysql, "UPDATE $TABLE_ACCOUNTS SET online = '".time()."' WHERE login = '$login'");
			$msg = "Користувач <b>$login</b> увійшов на сайт\n\n" . date("Y-m-d H:i:s", time() + 3*3600);
			
			file_get_contents("http://" . $_SERVER['SERVER_NAME'] . "/telegram/telegrambot.php?message=" . urlencode($msg));
			
			echo "<script>window.location = \"index.php?action=3\";</script>";
		} else {
			echo "<script>window.location = \"index.php?action=1\";</script>";
		} 
	} else echo "<script>window.location = \"index.php?action=2\";</script>";
	mysqli_close($mysql);
?>
