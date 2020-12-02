<?php
	session_start();
	date_default_timezone_set('Europe/Kiev');
	include "mysql/mysql_connect.php";
	$login = $_POST["login"];
	$result = mysqli_query($mysql, "SELECT * FROM `$TABLE_ACCOUNTS` WHERE `login` = BINARY '$login'");
	if(!$result) echo "<script>window.location = \"index.php\";</script>";
	else $pass = mysqli_fetch_assoc($result);
	$apass = $pass['password'];
	$id = $pass["id"];
	$tgid = $pass["telegram_id"];
	if($login != '' & $_POST["password"] != '') {
		if($_POST["password"] == $pass["password"]) {
			$_SESSION["login"] = $login;
			$_SESSION["uid"] = $id;
			mysqli_query($mysql, "UPDATE $TABLE_ACCOUNTS SET online = '".time()."' WHERE login = '$login'");
			
			$msg = "Користувач <b>$login</b> увійшов на сайт\n\n" . date("Y-m-d H:i:s");
			file_get_contents("http://" . $_SERVER['SERVER_NAME'] . "/telegram/telegrambot.php?message=" . urlencode($msg));
			
			$msg = "<b>$login</b>\nУспішна авторизація на сайті f-words.tk\n" . date("Y-m-d H:i:s");
			file_get_contents("http://" . $_SERVER['SERVER_NAME'] . "/telegram/telegrambot.php?to=$tgid&message=" . urlencode($msg));
			
			echo "<script>window.location = \"index.php?action=3\";</script>";
		} else {
			echo "<script>window.location = \"index.php?action=1\";</script>";
		} 
	} else echo "<script>window.location = \"index.php?action=2\";</script>";
	mysqli_close($mysql);
?>
