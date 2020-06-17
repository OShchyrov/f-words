<?php
	session_start();
	header("Content-Type: text/html; charset=utf-8");
	include "../mysql/mysql_connect.php";
	mysqli_query($mysql, "SET NAMES utf8");
	$login = $_SESSION["login"];
	$res = mysqli_query($mysql, "SELECT id FROM `$TABLE_ACCOUNTS` WHERE `login` = '$login'");
	$id = mysqli_fetch_assoc($res)["id"];
	$resis = mysqli_query($mysql, "SELECT * FROM `$TABLE_WORDS` WHERE `u_id` = '$id'");
	if($resis) mysqli_query($mysql, "DELETE FROM $TABLE_WORDS WHERE u_id = '$id'") or die(mysqli_error($mysql));
	mysqli_close($mysql);
	header("Location: words.php");
?>