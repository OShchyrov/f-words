<?php
	session_start();
	header("Content-Type: text/html; charset=utf-8");
	include "../mysql/mysql_connect.php";
	mysqli_query($mysql, "SET NAMES utf8");
	$login = $_SESSION["login"];
	$id = $_SESSION["uid"];
	$resis = mysqli_query($mysql, "SELECT * FROM `$TABLE_WORDS` WHERE `u_id` = '$id'");
	if($resis) mysqli_query($mysql, "DELETE FROM $TABLE_WORDS WHERE u_id = '$id'") or die(mysqli_error($mysql));
	mysqli_close($mysql);
	header("Location: words.php");
?>