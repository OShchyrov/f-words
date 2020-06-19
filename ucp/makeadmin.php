<?php
	include '../header.php';
	include "check_admin.php";
	echo "<div id='main'>";

	$login = $_SESSION["login"];
	checkAdmin();
	
	if(!isset($_POST["makeadmin"])) {
		echo "<h2>Додати адміністратора</h2>
				<form class='del_form' method='post' action='makeadmin.php'> <table align='center'> <tr>
				<td><span id='show_words'>Логін:</span></td> <td><input type='text' name='adm_name' /></td></tr>
				<tr><td align='center' colspan=2><input type='submit' name='makeadmin' value='Додати' /></td></tr></table>";
				echo "<div class='mainucp'><a href='ucp.php'>Повернутись в UCP-панель</a></div><br>";
	} else {
		if($_POST["adm_name"] != '') {
			$name = $_POST["adm_name"];
			$a = mysqli_query($mysql, "UPDATE $TABLE_ACCOUNTS SET admin = '1' WHERE login = '$name'");
			if($a) echo "<p style='font-size: 24px; margin: 0; color: green;'>Додано нового адміністратора $name!</p>";
			else echo "<p style='font-size: 24px; margin: 0; color: red;'>Помилка реєстрації адміністратора $name!</p>";
			echo "<div class='mainucp'><a href='makeadmin.php'>Додати ще одного адміністратора</a><br>";
			echo "<a href='ucp.php'>Повернутись в UCP-панель</a><br></div>";
		} else {
			echo "<h2>Додати адміністратора</h2>
				<form class='del_form method='post' action='makeadmin.php'> <table align='center'> <tr>
				<td><span id='show_words'>Логін:</span></td> <td><input type='text' name='adm_name' /></td></tr>
				<tr><td align='center' colspan=2><input type='submit' name='makeadmin' value='Додати' /></td></tr></table>";
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