<?php
	include 'header.php';
	
	echo "<div id='main'>";
	$login = isset($_SESSION["login"]) ? $_SESSION["login"] : "";
	$result = mysqli_query($mysql, "SELECT * FROM `$TABLE_ACCOUNTS` WHERE `login` = '$login'");
	$isban = mysqli_fetch_assoc($result)['ban'];
	if($isban > date('Y-m-d') && $_GET['action'] != 2):  
		echo "<h1 style='margin:0'>Ваш аккаунт заблоковано!</h1>
		<font color=red size=6>Дата розблоування: $isban</font><br>
		<font color='blue' size=5><a href='index.php?action=1'>На головну</a></font>";
		exit; 
	else:
	if($_GET['action'] == 3) changeLocation("check/words.php");

	mysqli_close($mysql);
	if ($_GET["tui"] == 1) {
		$_SESSION["test_unique_id"] = "";
		changeLocation("/");
	}
	if($_GET["action"] == 1) echo "<script>alert(\"Вы ввели неверный пароль!".$line["password"]."\");</script>";
	else if($_GET["action"] == 2) echo "<script>alert(\"Заполните все поля!\");</script>";
	if(isset($_SESSION['login']) == 1) changeLocation("check/words.php");
	else {
		 echo "	<div id='login'>
			<h2>Авторизація</h2>
			<form method='post' action='login.php'>
			<table align='center'> <tr>
				<td><span>Логін: </span></td><td><input type='text' name='login' class='input' /></td></tr>
				<tr><td><span>Пароль: </span></td><td><input type='password' name='password' class='input' /></td></tr>
				<tr><td align='center' colspan=2><br/><input class='mui-btn mui-btn--primary mui-btn--raised' type='submit' value='Вхід' /></td></tr>
			</table>
			</form>
		</div>
		<a href='info.php'><div id='ucp'>Інформація</div></a></div>";
	}
	endif;
?>