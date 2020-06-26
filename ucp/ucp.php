<?php
	include '../header.php';
	include "check_admin.php";
	$login = $_SESSION["login"];
	checkAdmin();
	
?>
	<a href='../index.php'><div id='ucp'>На головну</div></a>
	<a href='../logout.php'><div id='logout'>Вихід</div></a>
	<div class='mainucp'>
		<a href='register.php'>Додати користувача</a><br>
		<a href='delacc.php'>Видалити користувача</a><br>
		<a href='makeadmin.php'>Назначити адміністратора</a><br>
		<a href='deladmin.php'>Зняти адміністратора</a><br>
		<a href='ban.php'>Блокування</a><br>
		<a href='userinfo.php?show_users=1'>Інформація про користувачів</a><br>
		<a href='transfer_block.php'>Передати блок слів</a><br>
		<a href='control_mng.php'>Управління контрольними тестами</a><br>
		<a href='user_actions.php'>Дії користувачів</a><br>
		<a href='userplan.php'>Календарний план</a><br>
		<a href='words_editor.php'>Редактор словників</a><br/>
		<a href='online-help.php'>Online допомога</a><br>
	</div>
</div>
</body>
</html>
<?php
	mysqli_close($mysql);
?>
