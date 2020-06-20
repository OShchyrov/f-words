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
		<input name='edit_ban' class='mui-btn mui-btn--primary mui-btn--raised' type='submit' value='Редагувати блокування'/>
	</form>
</div>
<?php
} 
else if(isset($_POST['ban'])) {
	$ban_name = $_POST['ban_name'];
	$ban_date = $_POST['ban_date'];
	mysqli_query($mysql, "UPDATE $TABLE_ACCOUNTS SET ban = '$ban_date' WHERE login = '$ban_name'");
?> 
	<font color='red' size=5>Користувач <?php echo $ban_name; ?> заблокований до <?php echo $ban_date; ?></font>
	<div class='mainucp'><a href='ban.php'>Редагувати блокування ще одного користувача</a><br>
	<a href='ucp.php'>Повернутись в UCP-панель</a><br></div>
<?php
}
else if(isset($_POST['unban'])) {
	$ban_name = $_POST['ban_name'];
	$date = date('Y-m-d');
	mysqli_query($mysql, "UPDATE $TABLE_ACCOUNTS SET ban = '$date' WHERE login = '$ban_name'");
	?>
	<font color='green' size=5 margin=3>Користувач <?php echo $ban_name; ?> розблокований</font>
	<div class='mainucp'><a href='ban.php'>Редагувати блокування ще одного користувача</a><br>
	<a href='ucp.php'>Повернутись в UCP-панель</a><br></div>
<?php
}
else { 
?>
	<div id='unknows'>
		<form action='ban.php' method='post'>
			<h2>Заблокувати користувача</h2>
			<input type='hidden' value='<?php echo $_POST['ban_name']; ?>' name='ban_name'>
			<input type='date' name='ban_date' /><br>
			<input name='ban' type='submit' class='mui-btn mui-btn--primary mui-btn--raised' value='Заблокувати'/>
		</form>
	</div>
	<div id='knows'>
		<form action='ban.php' method='post'>
			<input type='hidden' value='<?php echo $_POST['ban_name']; ?>' name='ban_name'>
			<h2>Розблокувати користувача</h2>
			<input name='unban' type='submit' class='mui-btn mui-btn--primary mui-btn--raised' value='Розблокувати'/>
		</form>
	</div>
<?php 
}
?>
	<a href='../index.php'><div id='ucp'>На головну</div></a>
	<a href='../logout.php'><div id='logout'>Вихід</div></a>
<?php
	mysqli_close($mysql);
?>