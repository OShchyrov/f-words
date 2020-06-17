<?php
	session_start();
	
	include "../mysql/mysql_connect.php";
	
	$login = $_SESSION["login"];
	$res = mysqli_query($mysql, "SELECT * FROM $TABLE_ACCOUNTS WHERE `login` = '$login'");
	$id = mysqli_fetch_assoc($res)["id"];
	
	$res = mysqli_query($mysql, "SELECT * FROM $TABLE_CONTROL_TESTS WHERE u_id = $id AND test_type = 1") or die(mysqli_error($mysql));
	
	if ($res == 0 || mysqli_num_rows($res) == 0) {
		echo "<script>window.location = \"/check/words.php\";</script>";
		exit;
	}
	
	$result = mysqli_query($mysql, "SELECT param_value FROM $TABLE_SETTINGS WHERE param_key = 'simple_test_pwd'");
	$simple_test_pwd = mysqli_fetch_assoc($result)['param_value'];
	
	$result = mysqli_query($mysql, "SELECT * FROM `$TABLE_ACCOUNTS` WHERE `login` = '$login'");
	$adm = mysqli_fetch_assoc($result);
	$admin = $adm["admin"];
	
	if (isset($_REQUEST["unsuccess_attempts"]) && $_REQUEST['unsuccess_attempts'] >= 2) {
		header("Content-Type: text/html; charset=utf-8");
		include '../header.php';
		echo "<div style='padding-top: 30px;'><h1>Виявлено спробу взлому тесту!</h1></div>";
		echo "<div style='color: #FF1100; padding-top: 30px;'><h1>ВАШ АККАУНТ ЗАБЛОКОВАНО!</h1></div>";
		if ($admin)
			exit;
		$ban_date = getdate();
		$ban_date = $ban_date["year"] . "-" . ($ban_date["mon"] + 1) . "-" . $ban_date["mday"];
		mysqli_query($mysql, "UPDATE $TABLE_ACCOUNTS SET ban = '$ban_date' WHERE login = '$login'");
		echo "<script>
			var bd = document.getElementsByTagName('body')[0];
			var audio = document.createElement('audio');
			audio.src = '../warning.mp3';
			audio.autoplay = true;
			bd.appendChild(audio);
		</script>";
		session_destroy();
		exit;
	}
	
	if (!isset($_POST["access"]) || $_POST["access_pwd"] != $simple_test_pwd) {
		session_start();
		header("Content-Type: text/html; charset=utf-8");
		include '../header.php';
		$unsuccess_attempts = isset($_REQUEST["unsuccess_attempts"]) ? $_REQUEST["unsuccess_attempts"] : 0;
		if (isset($_POST["access_pwd"]) && $_POST["access_pwd"] != "" && $_POST["access_pwd"] != $verbs_test_pwd)
			$unsuccess_attempts++;
		echo "<div id=main><form method='post'>
		<h2>Словниковий диктант #".$_REQUEST['block_id']."</h2>
		<input placeholder='Пароль для тест-контролю' style='margin-top:100px;' type='text' name='access_pwd' autocomplete='off'/><br/>
		<input type='submit' name='access' value='СТАРТ!' />
		<input type='hidden' name='TEST_MODE' value='1' />
		<a href='/'><div id='info'>На головну</div></a>
		<input type='hidden' name='unsuccess_attempts' value='$unsuccess_attempts' />
		</form></div>";
		exit;
	}
	
	include "check.php";
	echo "<script>document.getElementsByClassName('test_mode')[0].getElementsByTagName('h1')[0].innerHTML = 'ТЕСТ-КОНТРОЛЬ - Словниковий Диктант #".$_REQUEST['block_id']."';</script>";
?>