<?php
	include '../header.php';
	include "check_admin.php";
	$login = $_SESSION["login"];
	checkAdmin();
	
	echo "<a href='../index.php'><div id='ucp'>На головну</div></a>";
	echo "<a href='../logout.php'><div id='logout'>Вихід</div></a>";
	echo "<div class='mainucp'><a href='register.php'>Додати користувача</a><br>";
	echo "<a href='delacc.php'>Видалити користувача</a><br>";
	echo "<a href='makeadmin.php'>Назначити адміністратора</a><br>";
	echo "<a href='deladmin.php'>Зняти адміністратора</a><br>";
	echo "<a href='ban.php'>Блокування</a><br>";
	echo "<a href='userinfo.php?show_users=1'>Інформація про користувачів</a><br>";
	echo "<a href='transfer_block.php'>Передати блок слів</a><br>";
	echo "<a href='control_mng.php'>Управління контрольними тестами</a><br>";
	echo "<a href='user_actions.php'>Дії користувачів</a><br>";
	echo "<a href='userplan.php'>Календарний план</a><br>";
	echo "<a href='words_editor.php'>Редактор словників</a><br></div></div>";
	mysqli_close($mysql);
?>
</div>
</body>
</html>