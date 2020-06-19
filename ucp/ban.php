<?php
	
	include '../header.php';
	include "check_admin.php";
	checkAdmin();
	$login = $_SESSION["login"];
	$result = mysqli_query($mysql, "SELECT * FROM `$TABLE_ACCOUNTS` WHERE `login` = '$login'");
	$adm = mysqli_fetch_assoc($result);
	$admin = $adm["admin"];
	if(!$admin) changeLocation("../index.php");
	if(!isset($_POST['edit_ban']) && !isset($_POST['ban']) && !isset($_POST['unban'])) {?>
<div style='padding-top:50px;'>
	<form action='ban.php' method='post'>
		<h2>Редагувати блокування користувача</h2>
		<input name='ban_name' type='text'/><br><br>
		<input name='edit_ban' type='submit' value='Редагувати блокування'/>
	</form>
</div>
<?php
} 
else if(isset($_POST['ban'])) {
	$ban_name = $_POST['ban_name'];
	$ban_date = $_POST['ban_date'];
	mysqli_query($mysql, "UPDATE $TABLE_ACCOUNTS SET ban = '$ban_date' WHERE login = '$ban_name'");
	echo "<font color='red' size=5 margin=3>Користувач $ban_name заблокований до $ban_date</font>";
	echo "<div class='mainucp'><a href='ban.php'>Редагувати блокування ще одного користувача</a><br>";
	echo "<a href='ucp.php'>Повернутись в UCP-панель</a><br></div>";
}
else if(isset($_POST['unban'])) {
	$ban_name = $_POST['ban_name'];
	$date = date('Y-m-d');
	mysqli_query($mysql, "UPDATE $TABLE_ACCOUNTS SET ban = '$date' WHERE login = '$ban_name'");
	echo "<font color='green' size=5 margin=3>Користувач $ban_name розблокований</font>";
	echo "<div class='mainucp'><a href='ban.php'>Редагувати блокування ще одного користувача</a><br>";
	echo "<a href='ucp.php'>Повернутись в UCP-панель</a><br></div>";
}
else { ?>
	<div id='unknows'>
		<form action='ban.php' method='post'>
			<h2>Заблокувати користувача</h2>
			<input type='hidden' value='<?php echo $_POST['ban_name']; ?>' name='ban_name'>
			<input type='date' name='ban_date' /><br>
			<input name='ban' type='submit' value='Заблокувати'/>
		</form>
	</div>
	<div id='knows'>
		<form action='ban.php' method='post'>
			<input type='hidden' value='<?php echo $_POST['ban_name']; ?>' name='ban_name'>
			<h2>Розблокувати користувача</h2>
			<input name='unban' type='submit' value='Розблокувати'/>
		</form>
	</div>
<?php }
	echo "<a href='../index.php'><div id='ucp'>На головну</div></a>";
	echo "<a href='../logout.php'><div id='logout'>Вихід</div></a>";
	mysqli_close($mysql);
?>