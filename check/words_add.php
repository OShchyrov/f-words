<?php include "../header.php" ?>
			<h2>Введіть кількість слів.</h2>
			<input id='num_of_words' type='number' /><br>
			<span id='submit_num'>Далі</span>
			<script src='../script.js' type='text/javascript'></script>
			<div style='padding-top: 60px;' class='words_data'></div>
		<?php
			$login = $_SESSION["login"];
			if($login == "") changeLocation("../index.php");

			$result = mysqli_query($mysql, "SELECT * FROM `$TABLE_ACCOUNTS` WHERE `login` = '$login'");
			$adm = mysqli_fetch_assoc($result);
			$id = $adm["id"];
			$admin = $adm['admin'];
			if($admin) echo "<a href='../ucp/ucp.php'><div id='ucp'>UCP-панель</div></a>";
			
			$result = mysqli_query($mysql, "SELECT * FROM `$TABLE_ACCOUNTS` WHERE `login` = '$login'");
			$isban = mysqli_fetch_assoc($result)['ban'];
			if($isban > date('Y-m-d')) { 
				echo "<h1 style='margin:0'>Ваш аккаунт заблоковано!</h1>
				<font color=red size=6>Дата розблоування: $isban</font><br>
				<font color='blue' size=5><a href='index.php?action=1'>На главную</a></font>";
				exit;
			}
			
			$control_result = mysqli_query($mysql, "SELECT * FROM $TABLE_CONTROL_TESTS WHERE u_id = $id");
			if ($control_result != 0 && mysqli_num_rows($control_result) > 0) {
				$control_result = mysqli_fetch_assoc($control_result);
				$block_id = $control_result["block_id"];
				$test_type = $control_result["test_type"];
				if ($test_type == 1) {
					changeLocation("check_test.php?block_id=$block_id");
				} else if ($test_type == 0) {
					changeLocation("check_verbs.php?block_id=$block_id");
				}
				
			}
			
			$result = mysqli_query($mysql, "SELECT block_id FROM $TABLE_BLOCKS WHERE u_id = '$id' AND status = 0 ORDER BY block_id DESC LIMIT 1");
			$block_id = mysqli_fetch_array($result)[0];
			
			$result = mysqli_query($mysql, "SELECT * FROM $TABLE_WORDS WHERE u_id = '$id' AND block_id = $block_id");
			$stringData = "";
			
			if ($result == 0 || mysqli_num_rows($result) == 0) {
				$stringData = "<h1>Наразі блок слів пустий</h1>";
			} else {
				$stringData = "<center><h1>У блоці слів зараз знаходяться наступні слова:</h1>";
				$stringData .= "<table><tr><td><b>#</b></td><td><b>Слово</b></td><td><b>Переклад</b></td></tr>";
				$count = 1;
				while ($row = mysqli_fetch_assoc($result)) {
					$stringData .= "<tr><td class='words_num'>$count) </td><td class='td_l'>".$row['word']."</td><td class='td_r'>".$row['translate']."</td></tr>";
					$count ++;
				}
				$stringData .= "</table></center>";
			}
			echo "<script>
				document.getElementsByClassName('words_data')[0].innerHTML = \"$stringData\";
			</script>";
			mysqli_close($mysql);
		?>
		<a href='../logout.php'><div id='logout'>Вихід</div></a>
		<a href='/check/words.php'><div id='info'>Головна</div></a>
		</div>
	</body>
</html>