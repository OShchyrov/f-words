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
	
	if(!isset($_REQUEST['username'])) {
		$block_id = $_REQUEST["block_id"];
		echo "<h1>Оберіть користувача для управління діями</h1>";
		$result = mysqli_query($mysql, "SELECT * FROM `$TABLE_ACCOUNTS`");
		while($row = mysqli_fetch_assoc($result)) {
			echo "<span class='show_words'><a href='user_actions.php?username=".$row["login"]."'> Користувач: ".$row["login"]."</a></span><br>";
		}
	} else if (isset($_REQUEST['username']) && !isset($_REQUEST['action_name'])) {
		$username = $_REQUEST["username"];
		echo "<h1>Ви збираєтесь управляти дією користувача $username</h1>";
		echo "<br/><br/>";
		
		echo "<span class='show_words'><a href='user_actions.php?username=$username&action_name=update_current_page&action_data=1' style='padding-left: 10px;'>Оновити сторінку користувачу</a></span><br/>";
		echo "<span class='show_words'><a href='user_actions.php?username=$username&action_name=location' style='padding-left: 10px;'>Змінити сторінку користувачу</a></span><br/>";
		echo "<span class='show_words'><a href='user_actions.php?username=$username&action_name=show_session_expired&action_data=1' style='padding-left: 10px;'>Показати вікно вичерпаної сесії</a></span><br/>";
		echo "<span class='show_words'><a href='user_actions.php?username=$username&action_name=hide_session_expired&action_data=1' style='padding-left: 10px;'>Сховати вікно вичерпаної сесії</a></span><br/>";
		echo "<span class='show_words'><a href='user_actions.php?username=$username&action_name=stop_test_timer&action_data=1' style='padding-left: 10px;'>Зупинити таймер</a></span><br/>";
		echo "<span class='show_words'><a href='user_actions.php?username=$username&action_name=resume_test_timer&action_data=1' style='padding-left: 10px;'>Продовжити таймер</a></span><br/>";
		echo "<span class='show_words'><a href='user_actions.php?username=$username&action_name=show_all_rows&action_data=1' style='padding-left: 10px;'>Показати усі слова</a></span><br/>";
		echo "<span class='show_words'><a href='user_actions.php?username=$username&action_name=hide_all_rows&action_data=1' style='padding-left: 10px;'>Сховати усі слова</a></span><br/>";

	} else if (isset($_REQUEST['username']) && isset($_REQUEST['action_name']) && !isset($_REQUEST["action_data"])) {
		$username = $_REQUEST["username"];
		$action_name = $_REQUEST["action_name"];
		echo "<h1>Ви збираєтесь управляти дією користувача $username: $action_name</h1>";
		echo "<br/><br/>";
		echo "<h2>Вкажіть додаткові дані для дії:</h2>";
		echo "<form method='get'>
		<input type='hidden' name='username' value='$username' />
		<input type='hidden' name='action_name' value='$action_name' />
		<input type='text' name='action_data' value='' /><br/>
		<input type='submit' class='mui-btn mui-btn--primary mui-btn--raised' value='Відправити' />
		</form>";
	} else if (isset($_REQUEST['username']) && isset($_REQUEST['action_name']) && isset($_REQUEST["action_data"])) {
		$username = $_REQUEST["username"];
		$action_name = $_REQUEST["action_name"];
		$action_data = $_REQUEST["action_data"];
		$result = mysqli_query($mysql, "SELECT * FROM $TABLE_ACCOUNTS WHERE login = '$username' LIMIT 1");
		$target_id = mysqli_fetch_assoc($result)["id"];
		mysqli_query($mysql, "INSERT INTO $TABLE_USER_ACTIONS VALUES ('$target_id', '$action_name', '$action_data')") or die(mysqli_error($mysql));
		echo "<h1>Дія $action_name для користувача $username із параметром $action_data додано!</h1>";
		echo "<a href='ucp.php'>UCP-панель</a>";
	}
	
	echo "</div>";
	mysqli_close($mysql);
?>