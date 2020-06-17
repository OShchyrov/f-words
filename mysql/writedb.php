<?php
	session_start();
	header("Content-Type: text/html; charset=utf-8");
	include "mysql_connect.php";
	mysqli_query($mysql, "SET NAMES utf8");
	$login = $_SESSION["login"];
	$res = mysqli_query($mysql, "SELECT id FROM `$TABLE_ACCOUNTS` WHERE `login` = '$login'");
	$id = mysqli_fetch_assoc($res)["id"];

	$result = mysqli_query($mysql, "SELECT block_id FROM $TABLE_BLOCKS WHERE u_id = '$id' AND block_id < 100 AND status = 0 ORDER BY block_id DESC LIMIT 1");
	$block_id = mysqli_fetch_array($result)[0];
	
	for($i = 1; $i < 51; $i++)
	{
		$word = $_POST[$i];
		$translate = $_POST[-$i];
		if($word && $translate) {
			$word = str_replace("'", "''", $word);
			$translate = str_replace("'", "''", $translate);
			mysqli_query($mysql, "INSERT INTO `$TABLE_WORDS` (u_id, block_id, word, translate) VALUES ('$id', '$block_id', '$word', '$translate')") or die(mysqli_error($mysql));
		}
	}
	mysqli_close($mysql);
	echo "<script>if (!alert('Слова додано!')) {
		window.location = \"../check/words.php\";
	}</script>";
?>