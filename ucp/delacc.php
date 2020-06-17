<?php
	session_start();

	include '../header.php';
	include "check_admin.php";
	checkAdmin();
	echo "<div id='main'>";
	include "../mysql/mysql_connect.php";
	$login = $_SESSION["login"];
	$result = mysqli_query($mysql, "SELECT * FROM `$TABLE_ACCOUNTS` WHERE `login` = '$login'");
	$adm = mysqli_fetch_assoc($result);
	$admin = $adm["admin"];
	if(!$admin) changeLocation("../index.php");
	
	if(!isset($_POST["delacc"])) {
		echo "<h2>Видалити користувача</h2>
				<form class='del_form' method='post' action='delacc.php'>
				<table align='center'> <tr>
				<td> <span id='show_words'>Логін:</span></td> <td><input type='text' name='del_name' /></td></tr>
				<tr> <td align='center' colspan=2><input type='submit' name='delacc' value='Видалити' /></td></tr></table></form>";
				echo "<div class='mainucp'><a href='ucp.php'>Повернутись в UCP-панель</a></div><br>";
	} else {
		if($_POST["del_name"] != '') {
			$name = $_POST["del_name"];
			$a = mysqli_query($mysql, "DELETE FROM `$TABLE_ACCOUNTS` WHERE login = '$name'");
			if($a) echo "<p style='font-size: 24px; margin: 0; color: green;'>Користувача $name успішно видалено!</p>";
			else echo "<p style='font-size: 24px; margin: 0; color: red;'>Помилка видалення користувача $name!</p>";
			echo "<div class='mainucp'><a href='delacc.php'>Видалити ще одного користувача</a><br>";
			echo "<a href='ucp.php'>Повернутись в UCP-панель</a><br></div>";
		} else {
			echo "<h2>Видалити користувача</h2>
				<form class='del_form' method='post' action='delacc.php'>
				<table align='center'> <tr>
				<td> <span id='show_words'>Логін:</span></td> <td><input type='text' name='del_name' /></td></tr>
				<tr> <td align='center' colspan=2><input type='submit' name='delacc' value='Видалити' /></td>
				</tr></table></form>";
				echo "<div class='mainucp'><a href='ucp.php'>Повернутись в UCP-панель</a></div><br>";
		}
	}
	echo "<a href='../index.php'><div id='ucp'>На головну</div></a>";
	echo "<a href='../logout.php'><div id='logout'>Вихід</div></a>";
	mysqli_close($mysql);
?>
</div>
</body>
</html>