<?php
	include '../header.php';
	include "check_admin.php";
	include_once "page_names.php";
	checkAdmin();
	echo "<div id='main'>";
	if(isset($_POST["accname"]))
	{
		mysqli_query($mysql, "UPDATE `$TABLE_ACCOUNTS` SET `login` = ");
	}
	$result = mysqli_query($mysql, "SELECT * FROM `$TABLE_ACCOUNTS`");
	if($_GET['show_users']) {
		while($row = mysqli_fetch_assoc($result)) {
			echo "<span class='show_words'><a href='userinfo.php?show_users=0&username=".$row["login"]."'> Користувач: ".$row["login"]."</a></span><br>";
		}
		echo "<a href='ucp.php'><div id='ucp'>UCP-панель</div></a>";
	} else {
		$user = $_GET["username"];
		$result1 = mysqli_query($mysql, "SELECT * FROM `$TABLE_ACCOUNTS` WHERE `login` = '$user'");
		$info = mysqli_fetch_assoc($result1);
		echo "<span class='user'>Користувач:<b> <span id='change_name'>$user</span></span></b><br>";
		echo "<span id='user'>Пароль:<span class='pass_d'>*******</span><span class='pass'><b>". $info["password"]."</span></span></b><br>";
		$datetime = gmdate("Y-m-d H:i:s", $info["online"]+3*3600);
		$active = getPathName($info["last_path"]);
		echo "<span class='user'>Останній раз на сайті:<b> <span id='change_name'>".$datetime."</span></span></b><br>";
		echo "<span class='user'>Остання активність:<b> <span id='change_name'>".$active."</span></span></b><br>";
		echo "<span class='user_adm'>Адміністратор: <b>";
		if($info["admin"]) echo "Так"; else echo "Ні"; echo "</b></span>";
		$result = mysqli_query($mysql, "SELECT ban FROM $TABLE_ACCOUNTS WHERE login = '$user'");
		$ban = mysqli_fetch_assoc($result)['ban'];
		if($ban > date("Y-m-d")) echo "<br><span class='user'><font color=red>Заблокований до: $ban</font></span>";
		echo "<br><span id='user_words'>Останні введені слова</span>";
		$numm = 0;
		$id = $info["id"];
		$aw = mysqli_query($mysql, "SELECT * FROM `$TABLE_WORDS` WHERE `u_id` = '$id' ORDER BY block_id ASC, id ASC");
		echo "<div class='words_user'>
			<table id='table_words' align= center>";
		
		while($infos = mysqli_fetch_assoc($aw)) {
			if($infos["word"] != '' && $infos["translate"] != '') { $numm++;
			echo "<tr><td class='words_num'>$numm) </td> <td> Блок #".$infos['block_id']." </td> <td class='td_l'> ".$infos["word"]."</td><td class='td_r'> ".$infos["translate"]."</td></tr>"; }
			else { $numm = $i; break; }
		}
		
		if($numm == 0) echo "<tr><td class='td_l' colspan='3'>У користувача немає ні одного слова</td></tr>";
		echo "</table>
		</div>";
		echo "<br><br><span id='user_results'>Результати тестувань</span>";
		$aw = mysqli_query($mysql, "SELECT * FROM `$TABLE_TEST_RESULTS` WHERE `u_id` = '$id'");
		echo "<div class='words_user'>
			<div id='results_info'></div>
			<table id='table_results' align= center>";
		echo "<tr><td><b><center>Дата та час</center></b></td><td><b><center>Тип тесту</center></b></td><td><b>Блок</b></td><td><b>Оцінка</b></td><td><b><center>Помилки (incorrect)</center></b></td><td><b><center>Помилки (correct)</center></b></td></tr>";
		$numm = 0;
		$middle_mark = 0;
		$highest_mark = 0;
		$lowest_mark = 13;
		$allmarks = 0;
		while($infos = mysqli_fetch_assoc($aw)) {
			$test_type = "";
			switch($infos["test_type"]) {
				case 1: $test_type = "Словниковий диктант"; break;
				case 2: $test_type = "Диктант непр. дієслів"; break;
				case 3: $test_type = "Самооцінювання по словнику"; break;
				default: $test_type = "Невідомо";
			}
			$mistakes = str_replace(",", ", ", $infos["mistakes"]);
			$mistakes = substr($mistakes, 0, strlen($mistakes)-2);
			$mistakes_incorrect = str_replace(",", ", ", $infos["mistakes_incorrect"]);
			$mistakes_incorrect = substr($mistakes_incorrect, 0, strlen($mistakes_incorrect)-2);
			echo "<tr><td>".$infos["date_time"]."</td><td>$test_type</td><td><center><b>#".$infos['block_id']."</b></center></td><td><b><center>".$infos["mark"]."</center></b></td><td style='max-width: 300px;'>$mistakes_incorrect</td><td style='max-width: 300px;'>$mistakes</td></tr>";
			
			if ($highest_mark < $infos['mark']) {
				$highest_mark = $infos['mark'];
			}
			if ($lowest_mark > $infos['mark']) {
				$lowest_mark = $infos['mark'];
			}
			
			$numm++;
			$allmarks += $infos['mark'];
		
		}
		
		$middle_mark = $allmarks / $numm;
		$middle_mark = number_format((float)$middle_mark, 2, '.', '');
		
		if($numm == 0) echo "<tr><td class='td_l' colspan='4'>У користувача немає результатів тестувань</td></tr>";
		echo "</table>
		</div>";
		
		echo "<script>
		var tardiv = document.getElementById('results_info');
		tardiv.style = 'padding: 0 50px;';
		tardiv.style.textAlign = 'left';
		var content = '<h3>Середній бал: $middle_mark</h3>';
		content += '<h3>Найвищий бал: $highest_mark</h3>';
		content += '<h3>Найнижчий бал: $lowest_mark</h3>';
		
		tardiv.innerHTML = content;
	</script>";
		
		echo "<a href='ucp.php'><div id='ucp'>UCP-панель</div></a>";
		echo "<a href='../logout.php'><div id='logout'>Вихід</div></a>";
		mysqli_close($mysql);
	}
?>
</div>
<style>
	a: hover { color: red; }
	a:visited { color: blue; }
	a:link { color: blue; }
	.pass {
		display: none;
	}
	#user:hover .pass { 
		display: inline;
	}
	#user:hover .pass_d {
		display: none;
	}
</style>
<script>
	document.getElementById("user_words").onclick = function() {
		var words = document.getElementsByClassName("words_user")[0];
		if(words.style.display == '' || words.style.display == 'none')
		words.style.display = 'block';
		else if(words.style.display == 'block')
		words.style.display = 'none';
	};
	document.getElementById("user_results").onclick = function() {
		var words = document.getElementsByClassName("words_user")[1];
		if(words.style.display == '' || words.style.display == 'none')
		words.style.display = 'block';
		else if(words.style.display == 'block')
		words.style.display = 'none';
	};
	/*document.getElementById('change_name').onclick = function() {
		var cn = document.getElementById('change_name');
		var rod = document.getElementById("main").getElementsByClassName('user')[0].getElementsByTagName('b')[0];
		rod.removeChild(cn);
		var form = document.createElement('form');
		form.action = 'userinfo.php';
		form.method = 'post';
		rod.appendChild(form);
		var inp = document.createElement('input');
		inp.type= 'text';
		inp.name = 'change_name';
		inp.id= 'change_name';
		inp.value= '<?php echo $user; ?>';
		inp.style.width = '100px';
		form.appendChild(inp);
		
		var subm = document.createElement('input');
		subm.type='submit';
		subm.id= 'change_name';
		subm.value='OK';
		subm.name='accname';
		form.appendChild(subm);
	}*/
</script>
</body>
</html>