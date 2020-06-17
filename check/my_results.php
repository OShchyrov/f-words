<?php 
	session_start();
	include "../header.php";
	
	$login = $_SESSION["login"];
	if($login == "") changeLocation("../index.php");
	include "../mysql/mysql_connect.php";
	$result = mysqli_query($mysql, "SELECT * FROM `$TABLE_ACCOUNTS` WHERE `login` = '$login'");
	$adm = mysqli_fetch_array($result);
	$admin = $adm['admin'];
	$id = $adm["id"];
	if($admin) echo "<a href='../ucp/ucp.php'><div id='ucp'>UCP-панель</div></a>";
	
	echo "<a href='../index.php'><div id='info'>Головна</div></a>";

	$aw = mysqli_query($mysql, "SELECT * FROM `$TABLE_TEST_RESULTS` WHERE `u_id` = '$id'");
	echo "<div style='padding-top: 20px;'><h1>Мої результати</h1>
	<div id='results_info'></div>
	<table id='table_results' align= center>";
	echo "<tr><td><b><center>Дата та час</center></b></td><td><b><center>Тип тесту</center></b></td><td><b>Блок</b></td><td><b>Оцінка</b></td><td><b><center>Помилки</center></b></td></tr>";
	$numm = 0;
	$middle_mark = 0;
	$highest_mark = 0;
	$lowest_mark = 13;
	$allmarks = 0;
	while($infos = mysqli_fetch_assoc($aw)) {
		$test_type = "";
		
		if ($infos["test_type"] == 3) continue;
		
		switch($infos["test_type"]) {
			case 1: $test_type = "Словниковий диктант"; break;
			case 2: $test_type = "Диктант непр. дієслів"; break;
			case 3: $test_type = "Самооцінювання по словнику"; break;
			default: $test_type = "Невідомо";
		}
		$mistakes = str_replace(",", ", ", $infos["mistakes"]);
		$mistakes = substr($mistakes, 0, strlen($mistakes)-2);
		echo "<tr><td>".$infos["date_time"]."</td><td>$test_type</td><td><center><b>#".$infos['block_id']."</b></center></td><td><b><center>".$infos["mark"]." б.</center></b></td><td style='max-width: 500px;'>$mistakes</td></tr>";
		
		if ($highest_mark < $infos['mark']) {
			$highest_mark = $infos['mark'];
		}
		if ($lowest_mark > $infos['mark']) {
			$lowest_mark = $infos['mark'];
		}
		
		$numm++;
		$allmarks += $infos['mark'];
	}
	
	
	
	if($numm == 0) echo "<tr><td class='td_l' colspan='5'>У Вас немає результатів тестувань!</td></tr>";
	else {
		$middle_mark = $allmarks / $numm;
		$middle_mark = number_format((float)$middle_mark, 2, '.', '');
		echo "<script>
			var tardiv = document.getElementById('results_info');
			tardiv.style = 'padding: 0 50px;';
			tardiv.style.textAlign = 'left';
			var content = '<h3>Середній бал: $middle_mark</h3>';
			content += '<h3>Найвищий бал: $highest_mark</h3>';
			content += '<h3>Найнижчий бал: $lowest_mark</h3>';
			
			tardiv.innerHTML = content;
		</script>";
	}
	
	echo "</table></div>";
?>