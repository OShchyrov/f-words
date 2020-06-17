<?php
	$verbs = array(
		array("eat", "ate", "eaten", "їсти"),
		array("sleep", "slept", "slept", "спати"),
		array("drink", "drank", "drunk", "пити"),
		array("can", "could", "been able to", "могти"),
		array("see", "saw", "seen", "бачити"),
		array("understand", "understood", "understood", "розуміти"),
		array("give", "gave", "given", "давати"),
		array("take", "took", "taken", "брати"),
		array("speak", "spoke", "spoken", "говорити"),
		array("come", "came", "come", "прийти"),
		array("lie", "lay", "lain", "лежати"),
		array("sit", "sat", "sat", "сидіти"),
		array('have', 'had', 'had', 'мати'),
		array("go", "went", "gone", "йти")
	);
	$u_id = 0;
	session_start();
	header("Content-Type: text/html; charset=utf-8");
	include "mysql_connect.php";
	mysqli_query($mysql, "DELETE FROM `$TABLE_IRREGULAR_VERBS` WHERE u_id = $u_id") or die(mysqli_error($mysql));
	for($i = 0; $i < count($verbs); $i++)
	{
		$VALUES = "VALUES ('$u_id', '". $verbs[$i][0] . "', '". $verbs[$i][1] ."', '". $verbs[$i][2] ."', '". $verbs[$i][3] ."')";
		mysqli_query($mysql, "INSERT INTO `$TABLE_IRREGULAR_VERBS` (u_id, verb_inf, verb_2, verb_3, verb_translate) $VALUES") or die(mysqli_error($mysql));
	}
	mysqli_close($mysql);
?>