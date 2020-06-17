<?php
	session_start();

	include '../header.php';
	include "check_admin.php";
	echo "<div id='main'>";
	include "../mysql/mysql_connect.php";

	$login = $_SESSION["login"];
	checkAdmin();
	
	if(!isset($_POST["register_user"])) {
		echo "<h2>Додати користувача</h2>
				<form class='del_form' method='post' action='register.php'>
				<table align='center'>
				<tr> <td> <span id='show_words'>Логін:</span></td> <td><input type='text' name='add_name' /></td></tr>
				<tr> <td> <span id='show_words'>Пароль:</span></td> <td><input type='text' name='add_pass' /></td></tr>
				<tr> <td colspan=2 align='center'><input type='submit' name='register_user' value='Зареєструвати' /></td>
				</tr></table>";
				echo "<div class='mainucp'><a href='ucp.php'>Повернутись в UCP-панель</a></div><br></div>";
	} else {
		if($_POST["add_name"] != '' & $_POST["add_pass"] != '') {
			$name = $_POST["add_name"];
			$pass = $_POST['add_pass'];
			$a = mysqli_query($mysql, "INSERT INTO `$TABLE_ACCOUNTS` (login, password) VALUES ('$name', '$pass')");
			if($a) echo "<p style='font-size: 24px; margin: 0; color: green;'>Аккаунт $name успішно зареєстровано!</p>";
			else echo "<p style='font-size: 24px; margin: 0; color: red;'>Помилка реєстрації аккаунту $name!</p>";
			
			$result = mysqli_query($mysql, "SELECT * FROM $TABLE_ACCOUNTS WHERE login = '$name'");
			$id = mysqli_fetch_assoc($result)["id"];
			
			mysqli_query($mysql, "INSERT INTO $TABLE_BLOCKS (u_id, block_id, status) VALUES ('$id', '1', '0')");
			
			echo "<div class='mainucp'><a href='register.php'>Зареєструвати ще один аккаунт</a><br>";
			echo "<a href='ucp.php'>Повернутись в UCP-панель</a><br></div>";
		} else {
			echo "<h2>Додати користувача</h2>
				<form class='del_form' method='post' action='register.php'>
				<table align='center'>
				<tr> <td> <span id='show_words'>Логін:</span></td> <td><input type='text' name='add_name' /></td></tr>
				<tr> <td> <span id='show_words'>Пароль:</span></td> <td><input type='text' name='add_pass' /></td></tr>
				<tr> <td colspan=2 align='center'><input type='submit' name='register_user' value='Зареєструвати' /></td>
				</tr></table>";
			echo "<div class='mainucp'><a href='ucp.php'>Повернутись в UCP-панель</a></div><br></div>";
		}
	}
	echo "<a href='../index.php'><div id='ucp'>На головну</div></a>";
	echo "<a href='../logout.php'><div id='logout'>Вихід</div></a>";
?>
</div>
</body>
</html>