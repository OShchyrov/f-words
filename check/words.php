<?php include "../header.php" ?>
			<div style='padding: 50px;'></div>
			<div class='show_words'><a href='plan.php'>Календарний план.</a></div>
			<div class='show_words'><a href='words_add.php'>Додати слова в поточний блок.</a></div>
			<div class='show_words'><a href='check.php'>Перевірити по останнім словам.</a></div>
			<div class='show_words'><a href='words_edit.php'>Редагувати словник.</a></div>
			<div class='show_words'><a href='close_block.php'>Закрити блок слів.</a></div>
			<div class='show_words'><a href='block_mng.php'>Управління словами та тестами.</a></div>
			<div class='show_words'><a href='my_results.php'>Мої результати.</a></div>
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
			mysqli_close($mysql);
		?>
		<a href='../logout.php'><div id='logout'>Вихід</div></a>
		<a href='../info.php'><div id='info'>Інформація</div></a>
		</div>
	</body>
</html>