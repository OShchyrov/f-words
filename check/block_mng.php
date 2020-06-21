<?php	
	include "../header.php";
	checkLogin();
	
	$login = $_SESSION["login"];
	$id = $_SESSION["uid"];
	
	$result = mysqli_query($mysql, "SELECT * FROM `$TABLE_ACCOUNTS` WHERE `login` = '$login'");
	$adm = mysqli_fetch_array($result);
	$admin = $adm['admin'];
	if($admin) echo "<a href='../ucp/ucp.php'><div id='ucp'>UCP-панель</div></a>";
	
	echo "<a href='../index.php'><div id='info'>Головна</div></a>";
	
	echo "<div style='padding-top: 10px;'>";
	
	echo "<h1>Мої слова</h1>";
	
	$blocks = mysqli_query($mysql, "SELECT * FROM $TABLE_BLOCKS WHERE u_id = '$id' AND block_id < '100'");
	while ($block = mysqli_fetch_assoc($blocks)) { 
		$block_id = $block['block_id'];
		
		$result = mysqli_query($mysql, "SELECT * FROM $TABLE_WORDS WHERE `u_id` = '$id' AND block_id = '$block_id'");
		$counter = 1;
		echo "<span class='table_word_block'><h2>Блок слів #$block_id</h2><table>";
		while($row = mysqli_fetch_assoc($result)) {
			echo "<tr><td class='words_num'>$counter</td><td class='td_l'>".$row['word']."</td><td class='td_r'>".$row['translate']."</td></tr>";
			$counter++;
		}
		echo "<tr><td colspan=3><a href='check.php?block_id=$block_id'><input type='button' class='mui-btn mui-btn--primary mui-btn--raised' value='Пройти тест' /></a></td></tr>";
		echo "</table></span>";
	}
	
	echo "</div>";
	mysqli_close($mysql);
?>