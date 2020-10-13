<?php
	include '../header.php';
	include "check_admin.php";
	checkAdmin();
	echo "<div id='main'>";

	echo "<div style='padding-top: 20px;'>";
	
	echo "<a href='../ucp/ucp.php'><div id='ucp'>UCP-панель</div></a>";
	
	echo "<a href='../index.php'><div id='info'>Головна</div></a>";
	
	if(!isset($_REQUEST['username'])) {
		
		echo "<h1>Оберіть користувача для управління календарним планом</h1>";
		$result = mysqli_query($mysql, "SELECT * FROM `$TABLE_ACCOUNTS`");
		while($row = mysqli_fetch_assoc($result)) {
			echo "<span class='show_words'><a href='userplan.php?username=".$row["login"]."'> Користувач: ".$row["login"]."</a></span><br>";
		}
	} else if (isset($_REQUEST["username"]) && !isset($_REQUEST["action"])) {
		$username = $_REQUEST["username"];
		echo "<h1>Додати план чи редагувати існуючий? Для користувача $username</h1>";
		echo "<br/><br/>";
		
		echo "<a href='userplan.php?username=$username&action=add'><input class='mui-btn mui-btn--primary mui-btn--raised' type='button' value='Додати новий' /></a>";
		echo "<a style='padding-left: 20px;' href='userplan.php?username=$username&action=update'><input class='mui-btn mui-btn--primary mui-btn--raised' type='button' value='Редагувати існуючий' /></a>";
	} else if (isset($_REQUEST["username"]) && isset($_REQUEST["action"])) {
		$username = $_REQUEST["username"];
		$action = $_REQUEST["action"];
		if ($action == "add") {
			if (isset($_REQUEST["add"])) {
				$dt = $_REQUEST["dt"];
				$description = $_REQUEST["description"];
				$result = mysqli_query($mysql, "SELECT * FROM $TABLE_ACCOUNTS WHERE login = '$username' LIMIT 1");
				$target_id = mysqli_fetch_assoc($result)["id"];
				$dt = $_REQUEST["dt"];
				$description = str_replace("'", "''", $_REQUEST["description"]);
				mysqli_query($mysql, "INSERT INTO $TABLE_USER_PLANS (u_id, dt, description) VALUES ('$target_id', '$dt', '$description')") or die(mysqli_error($mysql));
				
				echo "<h1>Для користувача $username додано новий план!</h1>";
				sendTelegram($mysql, $username, "Для Вас було додано <b>новий календарний план!</b>");
				echo "<a href='ucp.php'>UCP-панель</a>";
			} else {
				echo "<h1>Додати календарний план для $username</h1>";
				echo "<form method='get'>";
				echo "<input type='hidden' name='username' value='$username' />";
				echo "<input type='hidden' name='action' value='$action' />";
				echo "<table align=center>";
				echo "<tr><td><b>Дата:</b></td><td><input type='date' name='dt' /></tr>";
				echo "<tr><td><b>Опис завдання:</b></td><td><textarea rows=20 cols=100 name='description' ></textarea></tr>";
				echo "<tr><td colspan=2 align=center><input type='submit' class='mui-btn mui-btn--primary mui-btn--raised' name='add' value='Відправити' /></td></tr>";
				echo "</table></form>";
			}
		} else if ($action == "update") {
			echo "<h1>Редагувати календарний план $username</h1>";
			$result = mysqli_query($mysql, "SELECT * FROM $TABLE_ACCOUNTS WHERE login = '$username' LIMIT 1");
			$target_id = mysqli_fetch_assoc($result)["id"];
			
			if (!isset($_REQUEST["dt"])) {			
				$result = mysqli_query($mysql, "SELECT * FROM $TABLE_USER_PLANS WHERE u_id = $target_id");
				echo "<h1>Оберіть план для редагування</h1>";
				echo "<table align=center id='table_results'>";
				while ($row = mysqli_fetch_assoc($result)) {
					$dt = $row["dt"];
					$description = $row["description"];
					echo "<tr><td>$dt</td><td>$description</td><td><a href='userplan.php?username=$username&action=$action&dt=$dt'><input type='button' class='mui-btn mui-btn--primary mui-btn--raised' value='Обрати'></a></td></tr>";
				}
				echo "</table>";
			} else {
				$dt = $_REQUEST["dt"];
				if (isset($_REQUEST["delete"])) {
					echo "<h1>Календарний план для $username на $dt видалено!</h1>";
					mysqli_query($mysql, "DELETE FROM $TABLE_USER_PLANS WHERE u_id = $target_id AND dt = '$dt'");
					
					echo "<a href='ucp.php'>UCP-панель</a>";
				}
				else if (!isset($_REQUEST["update"])) {
					$result = mysqli_query($mysql, "SELECT * FROM $TABLE_USER_PLANS WHERE u_id = $target_id AND dt = '$dt'");
					$row = mysqli_fetch_assoc($result);
					$description = $row["description"];
					echo "<a href='userplan.php?username=$username&action=$action&dt=$dt&delete=1'><input class='mui-btn mui-btn--primary mui-btn--raised' type='button' value='Видалити даний календар'/></a>";
					echo "<br/><br/>";
					echo "<form method='get'>";
					echo "<input type='hidden' name='username' value='$username' />";
					echo "<input type='hidden' name='action' value='$action' />";
					echo "<input type='hidden' name='dt' value='$dt' />";
					echo "<table align=center>";
					echo "<tr><td><b>Дата:</b></td><td><input type='date' value='$dt' disabled /></tr>";
					echo "<tr><td><b>Опис завдання:</b></td><td><textarea rows=20 cols=100 name='description' >$description</textarea></tr>";
					echo "<tr><td colspan=2 align=center><input class='mui-btn mui-btn--primary mui-btn--raised' type='submit' name='update' value='Відправити' /></td></tr>";
					echo "</table></form>";
				} else {
					$description = str_replace("'", "''", $_REQUEST["description"]);
					mysqli_query($mysql, "UPDATE $TABLE_USER_PLANS SET description = '$description' WHERE u_id = $target_id AND dt = '$dt'") or die(mysqli_error($mysql));
				
					echo "<h1>Для користувача $username відредаговано план на $dt!</h1>";
					echo "<a href='ucp.php'>UCP-панель</a>";
				}
			}
		}
	}
	echo "</div>";
?>
</div>