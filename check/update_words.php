<?php
	session_start();

	include "../mysql/mysql_connect.php";
	mysqli_query($mysql, "SET NAMES utf8");
	$login = $_SESSION["login"];
	
	$count = $_REQUEST["count"];
	for ($i = 0; $i < $count; $i++) {
		$translate = $_REQUEST["tr_$i"];
		$word = $_REQUEST["w_$i"];
		$id = $_REQUEST["i_$i"];
		$word = str_replace("'", "''", $word);
		$translate = str_replace("'", "''", $translate);
		mysqli_query($mysql, "UPDATE $TABLE_WORDS SET translate = '$translate', word = '$word' WHERE id = '$id'") or die(mysqli_error($mysql));
	}
	header("Location: words_edit.php");
?>