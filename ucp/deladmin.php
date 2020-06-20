<?php
	include '../header.php';
	include "check_admin.php";
	echo "<div id='main'>";
	$login = $_SESSION["login"];
	checkAdmin();
	
	if(!isset($_POST["deladmin"])) {
		echo "<h2>Зняти адміністратора</h2>
				<form class='del_form' method='post' action='deladmin.php'> <table align='center'> <tr>
				<td><span id='show_words'>Логін:</span></td> <td><input type='text' name='del_adm' /></td></tr>
				<tr><td colspan=2 align='center'><input type='submit' class='mui-btn mui-btn--primary mui-btn--raised' name='deladmin' value='Зняти' /></td></tr></table>";
				echo "<div class='mainucp'><a href='ucp.php'>Повернутись в UCP-панель</a></div><br>";
	} else {
		if($_POST["del_adm"] != '') {
			$name = $_POST["del_adm"];
			$a = mysqli_query($mysql, "UPDATE `$TABLE_ACCOUNTS` SET `admin` = '0' WHERE login = '$name'");
			if($a) echo "<p style='font-size: 24px; margin: 0; color: green;'>Знято адміністратора $name!</p>";
			else echo "<p style='font-size: 24px; margin: 0; color: red;'>Помилка зняття адміністратора $name!</p>";
			echo "<div class='mainucp'><a href='deladmin.php'>Зняти ще одного адміністратора</a><br>";
			echo "<a href='ucp.php'>Повернутись в UCP-панель</a><br></div>";
		} else {
			echo "<h2>Зняти адміністратора</h2>
				<form class='del_form' method='post' action='deladmin.php'> <table align='center'> <tr>
				<td><span id='show_words'>Логін:</span></td> <td><input type='text' name='del_adm' /></td></tr>
				<tr><td colspan=2 align='center'><input type='submit' name='deladmin' value='Зняти' /></td></tr></table>";
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