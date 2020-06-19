<?php
	include '../header.php';
	$login = $_SESSION["login"];
?>
	
	<script>
		function playMusic(url) {
			var bd = document.getElementsByTagName('body')[0];
			var audio = document.createElement('audio');
			audio.src = url;
			audio.autoplay = true;
			bd.appendChild(audio);
		}
	</script>
	
	<?php
	
	checkLogin();
	
	$result = mysqli_query($mysql, "SELECT * FROM `$TABLE_ACCOUNTS` WHERE `login` = '$login'");
	$adm = mysqli_fetch_assoc($result);
	$admin = $adm["admin"];
	
	$test_unique_id = $_REQUEST["test_unique_id"];
	$result = mysqli_query($mysql, "SELECT u_id FROM $TABLE_TEST_RESULTS WHERE test_unique_id = '$test_unique_id'") or die(mysqli_error($mysql));
	if (mysqli_num_rows($result) > 0 && !$admin) {
		echo "<div style='padding-top: 30px;'><h1>Виявлено спробу взлому тесту!</h1></div>";
		echo "<div style='color: #FF1100; padding-top: 30px;'><h1>ВАШ АККАУНТ ЗАБЛОКОВАНО!</h1></div>";
		$ban_date = getdate();
		$ban_date = $ban_date["year"] . "-" . ($ban_date["mon"] + 1) . "-" . $ban_date["mday"];
		mysqli_query($mysql, "UPDATE $TABLE_ACCOUNTS SET ban = '$ban_date' WHERE login = '$login'");
		echo "<script> playMusic('../warning.mp3');</script>";
		session_destroy();
		exit;
	}
	
	echo "<div id='mark'></div>";
	$id = $_REQUEST["block_id"];
	$wordsres = mysqli_query($mysql, "SELECT * FROM `$TABLE_IRREGULAR_VERBS` WHERE `u_id` = $id") or die(mysqli_error($mysql));
	$aresult = mysqli_query($mysql, "SELECT id FROM `$TABLE_ACCOUNTS` WHERE `login` = '$login'");
	$aid = mysqli_fetch_assoc($aresult);
	$id = $aid["id"];
	$cont = -1;
	$wordsnum = 0;
	$wordscorrectnum = 0;
	$mistakes_incorrect = "";
	$mistakes = "";
	for($i = 1; $i < 50; $i++) {
		if($words = mysqli_fetch_assoc($wordsres)) {
			$inf = $_POST["verb_inf_$i"];
			$v2 = $_POST["verb_2_$i"];
			$v3 = $_POST["verb_3_$i"];
			
			if(trim($inf) != trim($words["verb_inf"]) || trim($v2) != trim($words["verb_2"]) || trim($v3) != trim($words["verb_3"])) {
				echo "<span class='show_words'>" . $words["verb_translate"] . " - ";
				
				if (trim($inf) != trim($words["verb_inf"])) {
					echo "<span class='lie'>$inf</span> - ";
					$mistakes_incorrect .= $inf . ",";
					$mistakes .= $words["verb_inf"] . ",";
				} else {
					echo "$inf - ";
					$wordscorrectnum++;
				}
				if (trim($v2) != trim($words["verb_2"])) {
					echo "<span class='lie'>$v2</span> - ";
					$mistakes_incorrect .= $v2 . ",";
					$mistakes .= $words["verb_2"] . ",";
				} else {
					echo "$v2 - ";
					$wordscorrectnum++;
				}
				if (trim($v3) != trim($words["verb_3"])) {
					echo "<span class='lie'>$v3</span>";
					$mistakes_incorrect .= $v3 . ",";
					$mistakes .= $words["verb_3"] . ",";
				} else {
					echo "$v3";
					$wordscorrectnum++;
				}
				echo "</span>";
				echo "<span class='lie'> - Неправильно!</span>";
				echo "<span class='show_words'> Відповідь: ";
				if (trim($inf) != trim($words["verb_inf"])) {
					echo "<span class='right'>". $words["verb_inf"] ."</span> - ";
				} else {
					echo $words["verb_inf"] ." - ";
				}
				if (trim($v2) != trim($words["verb_2"])) {
					echo "<span class='right'>". $words["verb_2"] ."</span> - ";
				} else {
					echo $words["verb_2"] ." - ";
				}
				if (trim($v3) != trim($words["verb_3"])) {
					echo "<span class='right'>". $words["verb_3"] ."</span>";
				} else {
					echo $words["verb_3"];
				}
				echo "<br/>";
				if($cont == -1 || $cont == 1) $cont = 0;
			} else {
				echo "<span class='show_words'>" . $words["verb_translate"] . " - $inf - $v2 - $v3</span>";
				echo "<span class='right'> - Правильно!</span><br>";
				if($cont == -1) $cont = 1;
				$wordscorrectnum+=3;
			}
			$wordsnum += 3;
		} else break;
	}
	echo "<div id='mainp'><a href='../check/check_verbs.php'>Ще раз</a></div>";
	echo "<div id='mainp'><a href='../index.php'>На головну</a></div>";
	echo "</div>";
	$result = mysqli_query($mysql, "SELECT * FROM `$TABLE_ACCOUNTS` WHERE `login` = '$login'");
	$adm = mysqli_fetch_assoc($result);
	$admin = $adm["admin"];
	if($admin) echo "<a href='../ucp/ucp.php'><div id='ucp'>UCP-панель</div></a>";
	echo "<a href='../logout.php'><div id='logout'>Вихід</div></a>";
	echo "<a href='../info.php'><div id='info'>Інформація</div></a>";
	$mark = $wordscorrectnum * 12 / $wordsnum;
	$mark = floor($mark);
	$datetime = date("Y-m-d H:i:s");
	
	$mistakes_incorrect = str_replace("'", "''", $mistakes_incorrect);
	$mistakes = str_replace("'", "''", $mistakes);
	
	mysqli_query($mysql, "INSERT INTO $TABLE_TEST_RESULTS (u_id, date_time, test_type, mark, mistakes_incorrect, mistakes, test_unique_id) VALUES ('$id', '$datetime', '2', '$mark', '$mistakes_incorrect', '$mistakes', '$test_unique_id')") or die(mysqli_error($mysql));
	mysqli_close($mysql);
	
	echo "<script>document.getElementById('mark').innerHTML = '$mark балів!';</script>";
	
	if($cont == 1) {
		$src = '../good.mp3';
	}
	else {
		$src = '../bad.mp3';
	}
	echo "<script> playMusic('$src');</script>";
	
?>