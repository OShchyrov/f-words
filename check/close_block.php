<?php 
	session_start();
	
	$login = $_SESSION["login"];
	if($login == "") changeLocation("../index.php");
	include "../header.php";
	include "../mysql/mysql_connect.php";
	$result = mysqli_query($mysql, "SELECT * FROM $TABLE_ACCOUNTS WHERE `login` = '$login'");
	$id = mysqli_fetch_assoc($result)["id"];
	
	$result = mysqli_query($mysql, "SELECT * FROM `$TABLE_ACCOUNTS` WHERE `login` = '$login'");
	$adm = mysqli_fetch_array($result);
	$admin = $adm['admin'];
	if($admin) echo "<a href='../ucp/ucp.php'><div id='ucp'>UCP-панель</div></a>";
	
	echo "<a href='../index.php'><div id='info'>Головна</div></a>";
	
	$result = mysqli_query($mysql, "SELECT block_id FROM $TABLE_BLOCKS WHERE u_id = '$id' AND status = '0' ORDER BY block_id DESC LIMIT 1");
	$rows = mysqli_num_rows($result);
	if ($rows == 0) {
		mysqli_query($mysql, "INSERT INTO $TABLE_BLOCKS (u_id, block_id, status) VALUES ('$id', '1', '0')");
		$block_id = 1;
	} else {
		$block_id = mysqli_fetch_array($result)[0];
	}
	
	$result = mysqli_query($mysql, "SELECT * FROM $TABLE_WORDS WHERE u_id = '$id' AND block_id = '$block_id'") or die(mysqli_error($mysql));
	
	if (!mysqli_num_rows($result)) {
		echo "<div style='padding-top: 10px;'><h1>У вас немає слів в блоці!</h1></div>";
		echo "<a href='words.php'>На головну</a>";
		exit;
	}
	
	if (isset($_POST['close_block'])) {
		mysqli_query($mysql, "UPDATE $TABLE_BLOCKS SET status = '1' WHERE u_id = '$id' AND block_id = '$block_id'") or die(mysqli_error($mysql));
		$result = mysqli_query($mysql, "SELECT block_id FROM $TABLE_BLOCKS WHERE u_id = '$id' ORDER BY block_id DESC LIMIT 1");
		$block_id = mysqli_fetch_assoc($result)['block_id'];
		mysqli_query($mysql, "INSERT INTO $TABLE_BLOCKS (u_id, block_id, status) VALUES ('$id', '".($block_id+1)."', '0')");
		echo "<div style='padding-top: 10px;'><h1>Блок слів закритий!</h1></div>";
		echo "<a href='words.php'>На головну</a>";
		exit;
	}
	
	echo "<div style='padding-top: 30px;'><h1>Ви дійсно хочете закрити блок слів? Ніякі дії з ними більше не будуть доступними!</h1></div>";
	echo "<form method=post>
	<input type='submit' name='close_block' value='Так' />
	<a href='words.php' style='padding-left: 10px;'><input type='button' value='На головну' /></a>
	</form>";
	echo "<div><table id='table_words' align=center>";
	$counter = 1;
	while ($row = mysqli_fetch_assoc($result)) {
		echo "<tr><td class='words_num'>$counter</td><td class='td_l'>".$row['word']."</td><td class='td_r'>".$row['translate']."</td></tr>";
		$counter++;
	}
	echo "</table></div>";
	mysqli_close($mysql);
?>