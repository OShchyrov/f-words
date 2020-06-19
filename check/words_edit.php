<?php 
	include "../header.php";
	
	$login = $_SESSION["login"];
	if($login == "") changeLocation("../index.php");

	$result = mysqli_query($mysql, "SELECT * FROM `$TABLE_ACCOUNTS` WHERE `login` = '$login'");
	$adm = mysqli_fetch_array($result);
	$admin = $adm['admin'];
	$id = $adm['uid'];
	if($admin) echo "<a href='../ucp/ucp.php'><div id='ucp'>UCP-панель</div></a>";
	
	echo "<a href='../index.php'><div id='info'>Головна</div></a>";

	echo "<center><form action='update_words.php' method='post'><table>";
	echo "<tr><td><b><center>Невідомі слова</center></b></td><td><b><center>Переклад</center></b></td></tr>";
	
	$result = mysqli_query($mysql, "SELECT block_id FROM $TABLE_BLOCKS WHERE u_id = '$id' AND status = 0 ORDER BY block_id DESC LIMIT 1");
	$block_id = mysqli_fetch_array($result)[0];
	
	$result = mysqli_query($mysql, "SELECT * FROM $TABLE_WORDS WHERE u_id = $id AND block_id = '$block_id'");

	if ($result == 0 || mysqli_num_rows($result) == 0) {
		changeLocation("words.php");
	} else $count = mysqli_num_rows($result);
	echo "<input type='hidden' name='count' value='$count' />";
	$index = 0;
	while ($row = mysqli_fetch_assoc($result)) {
		$ind = $row["id"];
		$word = $row["word"];
		$translate = $row["translate"];
		echo "<tr><td><input type='text' name='w_$index' value=\"$word\" /></td><td><input type='text' name='tr_$index' value=\"$translate\" /></td><input type='hidden' name='i_$index' value='$ind' /></tr>";
		$index ++;
	}
	echo "</table><input type='submit' name='update' value='ОНОВИТИ' /></form></center>";
?>