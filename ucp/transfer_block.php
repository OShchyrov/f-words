<?php
	include "../header.php";
	
	$login = $_SESSION["login"];
	$id = $_SESSION["uid"];
	if($login == "") changeLocation("../index.php");
	
	$result = mysqli_query($mysql, "SELECT * FROM `$TABLE_ACCOUNTS` WHERE `login` = '$login'");
	$adm = mysqli_fetch_array($result);
	$admin = $adm['admin'];
	if($admin) echo "<a href='../ucp/ucp.php'><div id='ucp'>UCP-панель</div></a>";
	
	echo "<a href='../index.php'><div id='info'>Головна</div></a>";
	
	echo "<div style='padding-top: 10px;'>";
	
	if (!isset($_REQUEST["block_id"]))
	{
		echo "<h1>Мої слова</h1>";
	
		$blocks = mysqli_query($mysql, "SELECT DISTINCT block_id FROM $TABLE_WORDS WHERE u_id = '$id' ORDER BY block_id ASC");
		while ($block = mysqli_fetch_assoc($blocks)) { 
			$block_id = $block['block_id'];
			
			$result = mysqli_query($mysql, "SELECT * FROM $TABLE_WORDS WHERE `u_id` = '$id' AND block_id = '$block_id'");
			$counter = 1;
			echo "<span class='table_word_block'><h2>Блок слів #$block_id</h2><table>";
			while($row = mysqli_fetch_assoc($result)) {
				echo "<tr><td class='words_num'>$counter</td><td class='td_l'>".$row['word']."</td><td class='td_r'>".$row['translate']."</td></tr>";
				$counter++;
			}
			echo "<tr><td colspan=3><a href='transfer_block.php?block_id=$block_id'><input type='button' value='Передати блок' class='mui-btn mui-btn--primary mui-btn--raised' /></a></td></tr>";
			echo "</table></span>";
		}
	} else if(isset($_REQUEST["block_id"]) && !isset($_REQUEST['username'])) {
		$block_id = $_REQUEST["block_id"];
		echo "<h1>Оберіть користувача для передачі блоку #$block_id</h1>";
		$result = mysqli_query($mysql, "SELECT * FROM `$TABLE_ACCOUNTS`");
		while($row = mysqli_fetch_assoc($result)) {
			echo "<span class='show_words'><a href='transfer_block.php?block_id=$block_id&username=".$row["login"]."'> Користувач: ".$row["login"]."</a></span><br>";
		}
	} else if (isset($_REQUEST["block_id"]) && isset($_REQUEST['username']) && !isset($_REQUEST['hidden'])) {
		$block_id = $_REQUEST["block_id"];
		$username = $_REQUEST["username"];
		echo "<h1>Ви збираєтесь передати блок слів #$block_id користувачу $username</h1>";
		echo "<br/><br/>";
		echo "<h1>Блок слів повинен бути скритим?</h1>";
		$url_core = "transfer_block.php?block_id=$block_id&username=$username&hidden";
		echo "<a href='$url_core=1'><input class='mui-btn mui-btn--primary mui-btn--raised' type='button' value='Скритий' /></a>";
		echo "<a href='$url_core=0' style='padding-left: 10px;'><input class='mui-btn mui-btn--primary mui-btn--raised' type='button' value='Відкритий' /></a>";
	} else if (isset($_REQUEST["block_id"]) && isset($_REQUEST['username']) && isset($_REQUEST['hidden']) && !isset($_REQUEST["copy"])) {
		$block_id = $_REQUEST["block_id"];
		$username = $_REQUEST["username"];
		$hidden = $_REQUEST["hidden"];
		echo "<h1>Ви збираєтесь передати блок слів #$block_id користувачу $username як ".($hidden == '1' ? "Скритий" : "Відкритий")."</h1>";
		echo "<br/><br/>";
		echo "<h1>Блок слів копіювати чи перемістити?</h1>";
		$url_core = "transfer_block.php?block_id=$block_id&username=$username&hidden=$hidden&copy";
		echo "<a href='$url_core=1'><input class='mui-btn mui-btn--primary mui-btn--raised' type='button' value='Копіювати' /></a>";
		echo "<a href='$url_core=0' style='padding-left: 10px;'><input class='mui-btn mui-btn--primary mui-btn--raised' type='button' value='Перемістити' /></a>";
	} else if (isset($_REQUEST["block_id"]) && isset($_REQUEST['username']) && isset($_REQUEST['hidden']) && isset($_REQUEST["copy"])) {
		$block_id = $_REQUEST["block_id"];
		$username = $_REQUEST["username"];
		$hidden = $_REQUEST["hidden"];
		$copy = $_REQUEST["copy"];
		
		$result = mysqli_query($mysql, "SELECT * FROM $TABLE_ACCOUNTS WHERE login = '$username'");
		$target_id = mysqli_fetch_assoc($result)['id'];
		
		if ($hidden == '1') {
			$result = mysqli_query($mysql, "SELECT * FROM $TABLE_WORDS WHERE u_id = $target_id AND block_id > 100 AND block_id < 200 ORDER BY block_id DESC LIMIT 1");
			$target_block_id = mysqli_fetch_assoc($result)['block_id'];
		} else {
			$result = mysqli_query($mysql, "SELECT * FROM $TABLE_BLOCKS WHERE u_id = $target_id AND block_id < 100 AND block_id < 200 ORDER BY block_id DESC LIMIT 1");
			$target_block_id = mysqli_fetch_assoc($result)['block_id'];
		}
		$target_block_id ++;

		mysqli_query($mysql, "INSERT INTO $TABLE_WORDS (u_id, block_id, word, translate)
						SELECT '$target_id', '$target_block_id', word, translate FROM $TABLE_WORDS WHERE u_id = '$id' AND block_id = '$block_id'") or die(mysqli_error($mysql));
		
		mysqli_query($mysql, "INSERT INTO $TABLE_BLOCKS VALUES ('$target_id', '$target_block_id', '1')") or die (mysqli_error($mysql));
		
		if ($copy == 0) {
			mysqli_query($mysql, "DELETE FROM $TABLE_WORDS WHERE u_id = $id AND block_id = $block_id");
		}
		
		echo "<h1>Блок слів #$block_id для користувача $username успішно ".($copy == 1 ? "скопійовано" : "переміщено")." як ".($hidden == 1 ? "прихований" : "відкритий")."</h1>";
		echo "<div class='mainucp'><a href='ucp.php'>Повернутись в UCP-панель</a></div><br></div>";
	}
	
	echo "</div>";
	mysqli_close($mysql);
?>