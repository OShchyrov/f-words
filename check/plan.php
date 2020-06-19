<?php 
	function getNameOfDayOfWeek($day) {
		switch($day) {
			case 1: return "Понеділок";
			case 2: return "Вівторок";
			case 3: return "Середа";
			case 4: return "Четвер";
			case 5: return "П'ятниця";
			case 6: return "Субота";
			case 0: return "Неділя";
		}
	}

	include "../header.php";

	$login = $_SESSION["login"];
	if($login == "") changeLocation("../index.php");

	$result = mysqli_query($mysql, "SELECT * FROM `$TABLE_ACCOUNTS` WHERE `login` = '$login'");
	$adm = mysqli_fetch_assoc($result);
	$id = $adm["id"];
	$admin = $adm['admin'];
	if($admin) echo "<a href='../ucp/ucp.php'><div id='ucp'>UCP-панель</div></a>";
	
	echo "<div style='padding-top:20px;'>";
	$dt = date("Y-m-d");
	$result = mysqli_query($mysql, "SELECT * FROM $TABLE_USER_PLANS WHERE u_id = '$id' AND dt >= '$dt'");
	if ($result == 0 || mysqli_num_rows($result) == 0) {
		echo "<h1>Календарний план відсутній!</h1>";
		echo "<a href='/'>На головну</a>";
	} else {
	
		echo "<h1>Календарний план</h1>";
		
		echo "<center><table id='table_results'>";
		echo "<tr><td><center><b>Дата</b></center></td><td><center><b>Опис</b></center></td></tr>";
		while($row = mysqli_fetch_assoc($result)) {
			$date = $row["dt"];
			$description = $row["description"];
			$dayofweek = getNameOfDayOfWeek(date('w', strtotime($date)));
			echo "<tr><td style='padding:3px'><center>$dayofweek, $date</center></td><td style='padding:3px'>$description</td></tr>";
		}
		
		echo "</table></center>";
	}
	echo "</div>";
	mysqli_close($mysql);
?>
	<a href='../logout.php'><div id='logout'>Вихід</div></a>
	<a href='/check/words.php'><div id='info'>Головна</div></a>
</div>