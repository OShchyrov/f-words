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
	
	echo "<div style='padding-top: 10px;'>";
	
	if (isset($_REQUEST['act']) && $_REQUEST['act'] = 'delete') {
		$username = $_REQUEST['username'];
		$test_type = $_REQUEST['test_type'];
		$block_id = $_REQUEST['block_id'];
		
		$result = mysqli_query($mysql, "SELECT * FROM $TABLE_ACCOUNTS WHERE `login` = '$username'");
		$target_id = mysqli_fetch_assoc($result)["id"];
		
		mysqli_query($mysql, "DELETE FROM $TABLE_CONTROL_TESTS WHERE u_id = $target_id AND test_type = $test_type AND block_id = $block_id LIMIT 1") or die(mysqli_error($mysql));

		changeLocation("control_mng.php?username=$username");
		exit;
	}
	
	if (isset($_REQUEST['change_pwd'])) {
		
		$data = mysqli_query($mysql, "SELECT * FROM $TABLE_SETTINGS WHERE param_key IN ('simple_test_pwd', 'verbs_test_pwd')");
		while ($row = mysqli_fetch_assoc($data)) {
			if ($row['param_key'] == "simple_test_pwd") {
				$simple_test_pwd = $row['param_value'];
			} else if ($row['param_key'] == "verbs_test_pwd") {
				$verbs_test_pwd = $row['param_value'];
			}
		}
		
		echo "<form method='post' action='control_mng.php'><table id='table_results' align=center>";
		echo "<tr><td><b>Звичайні тести</b></td><td><input type='text' value='$simple_test_pwd' name='simple_test' /></td></tr>";
		echo "<tr><td><b>Тест по неправильним дієсловам</b></td><td><input type='text' value='$verbs_test_pwd' name='verbs_test' /></td></tr>";
		echo "<tr><td colspan=2 align=center><input type='submit' name='submit' value='Зберегти' /></td></tr>";
		echo "<input type='hidden' name='changed_pwd' value='1' />";
		echo "</table></form>";
		echo "<div class='mainucp'><a href='ucp.php'>Повернутись в UCP-панель</a></div><br></div>";
		exit;
	}
	
	if (isset($_REQUEST['changed_pwd'])) {
		
		$simple_test_pwd = $_REQUEST['simple_test'];
		$verbs_test_pwd = $_REQUEST['verbs_test'];
		
		mysqli_query($mysql, "UPDATE $TABLE_SETTINGS SET param_value = '$simple_test_pwd' WHERE param_key = 'simple_test_pwd'") or die(mysqli_error($mysql));
		mysqli_query($mysql, "UPDATE $TABLE_SETTINGS SET param_value = '$verbs_test_pwd' WHERE param_key = 'verbs_test_pwd'") or die(mysqli_error($mysql));
		
		changeLocation("control_mng.php?change_pwd=1");
		exit;
	}
	
	if (!isset($_REQUEST["username"])) {
		echo "<a href='control_mng.php?change_pwd=1'><input type='button' value='Зміна паролів для тестів' /></a><br/><br/>";
		
		$result = mysqli_query($mysql, "SELECT * FROM `$TABLE_ACCOUNTS`");
		while($row = mysqli_fetch_assoc($result)) {
			echo "<span class='show_words'><a href='control_mng.php?username=".$row["login"]."'> Користувач: ".$row["login"]."</a></span><br>";
		}
		echo "<a href='ucp.php'><div id='ucp'>UCP-панель</div></a>";
	} else if (isset($_REQUEST["username"]) && !isset($_REQUEST["test_type"])) {
		$username = $_REQUEST["username"];
		echo "<h1>Ви збираєтесь увімкнути контрольне тестування для користувача $username</h1>";
		echo "<br/><br/>";
		
		$result = mysqli_query($mysql, "SELECT * FROM $TABLE_ACCOUNTS WHERE `login` = '$username'");
		$target_id = mysqli_fetch_assoc($result)["id"];
		
		$result = mysqli_query($mysql, "SELECT * FROM $TABLE_CONTROL_TESTS WHERE u_id = '$target_id'");
		if ($result != 0 && mysqli_num_rows($result) > 0) {
			echo "<h1>У користувача увімкнені контрольні роботи:</h1>";
			echo "<center><table id='table_results'>";
			echo "<tr class='caption'><td>Тип тесту</td><td>Блок слів</td><td>Видалення</td></tr>";
			while($row = mysqli_fetch_assoc($result)) {
				$test_type = $row['test_type'];
				$block_id = $row['block_id'];
				echo "<tr><td>".($test_type == 1 ? "Звичайний" : "Неправильні дієслова")."</td><td>#".$block_id."</td><td><a href='control_mng.php?act=delete&username=$username&test_type=$test_type&block_id=$block_id'><input type='button' value='Видалити' /></a></td></tr>";
			}
			
			echo "</table></center>";
			echo "<br/><br/>";
		}
		
		echo "<h1>Оберіть тип контрольного тестування</h1>";
		$url_core = "control_mng.php?username=$username&test_type";
		echo "<a href='$url_core=1'><input type='button' value='Звичайне' /></a>";
		echo "<a href='$url_core=0' style='padding-left: 10px;'><input type='button' value='Неправильні дієслова' /></a>";
	} else if (isset($_REQUEST["username"]) && isset($_REQUEST["test_type"]) && !isset($_REQUEST["block_id"])) {
		$username = $_REQUEST["username"];
		echo "<h1>Оберіть блок для тестування користувача <b>$username</b></h1>";
		
		$result = mysqli_query($mysql, "SELECT * FROM $TABLE_ACCOUNTS WHERE `login` = '$username'");
		$target_id = mysqli_fetch_assoc($result)["id"];
		
		$test_type = $_REQUEST["test_type"];
		if ($test_type == 1) {
			$blocks = mysqli_query($mysql, "SELECT DISTINCT block_id FROM $TABLE_WORDS WHERE u_id = '$target_id' ORDER BY block_id ASC");
			while ($block = mysqli_fetch_assoc($blocks)) { 
				$block_id = $block['block_id'];

				$result = mysqli_query($mysql, "SELECT * FROM $TABLE_WORDS WHERE `u_id` = '$target_id' AND block_id = '$block_id'");
				$counter = 1;
				echo "<span class='table_word_block'><h2>Блок слів #$block_id</h2><table>";
				while($row = mysqli_fetch_assoc($result)) {
					echo "<tr><td class='words_num'>$counter</td><td class='td_l'>".$row['word']."</td><td class='td_r'>".$row['translate']."</td></tr>";
					$counter++;
				}
				echo "<tr><td colspan=3 style='text-align:center'><a href='control_mng.php?username=$username&test_type=$test_type&block_id=$block_id'><input type='button' value='Обрати блок' /></a></td></tr>";
				echo "</table></span>";
			}
		} else if ($test_type == 0) {
			$blocks = mysqli_query($mysql, "SELECT DISTINCT u_id FROM $TABLE_IRREGULAR_VERBS");
			while ($block = mysqli_fetch_assoc($blocks)) { 
				$block_id = $block['u_id'];

				$result = mysqli_query($mysql, "SELECT * FROM $TABLE_IRREGULAR_VERBS WHERE u_id = $block_id");
				$counter = 1;
				echo "<span class='table_word_block'><h2>Блок слів #$block_id</h2><table>";
				while($row = mysqli_fetch_assoc($result)) {
					echo "<tr><td class='words_num'>$counter</td><td class='td_l'>".$row['verb_inf']."</td><td class='td_l'>".$row['verb_2']."</td><td class='td_l'>".$row['verb_3']."</td><td class='td_r'>".$row['verb_translate']."</td></tr>";
					$counter++;
				}
				echo "<tr><td colspan=5 style='text-align:center'><a href='control_mng.php?username=$username&test_type=$test_type&block_id=$block_id'><input type='button' value='Обрати блок' /></a></td></tr>";
				echo "</table></span>";
			}
		}
	} else if (isset($_REQUEST["username"]) && isset($_REQUEST["test_type"]) && isset($_REQUEST["block_id"])) {
		$username = $_REQUEST["username"];
		$test_type = $_REQUEST["test_type"];
		$block_id = $_REQUEST["block_id"];
		
		$result = mysqli_query($mysql, "SELECT * FROM $TABLE_ACCOUNTS WHERE `login` = '$username'");
		$target_id = mysqli_fetch_assoc($result)["id"];
		
		echo "<h1>Контрольний тест для $username увімкнено ". ($test_type == 1 ? "звичайний" : "неправильні дієслова") ." #$block_id</h1>";
		
		mysqli_query($mysql, "INSERT INTO $TABLE_CONTROL_TESTS (u_id, test_type, block_id) VALUES ('$target_id', '$test_type', '$block_id')") or die(mysqli_error($mysql));
		
		echo "<div class='mainucp'><a href='ucp.php'>Повернутись в UCP-панель</a></div><br></div>";
	}

	echo "</div>";
	mysqli_close($mysql);
?>