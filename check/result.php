<?php
	function updateWord($word) {
		$word = str_replace("do not", "don't", $word);
		$word = str_replace("does not", "doesn't", $word);
		$word = str_replace("will not", "won't", $word);
		$word = str_replace("did not", "didn't", $word);
		$word = str_replace("is not", "isn't", $word);
		$word = str_replace("are not", "aren't", $word);
		$word = str_replace("I am", "I'm", $word);
		$word = str_replace("You are", "You're", $word);
		$word = str_replace("He is", "He's", $word);
		$word = str_replace("it is", "it's", $word);
		$word = ucfirst($word);
		return $word;
	}
	
	include '../header.php';
	$login = $_SESSION["login"];
	echo "<div id='main'>";
	
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
		echo "<script>
			var bd = document.getElementsByTagName('body')[0];
			var audio = document.createElement('audio');
			audio.src = '../warning.mp3';
			audio.autoplay = true;
			bd.appendChild(audio);
		</script>";
		session_destroy();
		exit;
	}
	
	echo "<div id='mark'></div>";
	echo "<div id='mainp'><a href='../check/check.php?block_id=$block_id'>Ще раз</a></div><br/>";
	echo "<div id='mainp'><a href='../index.php'>На головну</a></div><br/><br/>";
	$aresult = mysqli_query($mysql, "SELECT id FROM `$TABLE_ACCOUNTS` WHERE `login` = '$login'");
	$aid = mysqli_fetch_assoc($aresult);
	$id = $aid["id"];
	$block_id = $_POST['block_id'];
	$wordsres = mysqli_query($mysql, "SELECT * FROM `$TABLE_WORDS` WHERE `u_id` = $id AND block_id = '$block_id'");
	$cont = 1;
	$wordsnum = 0;
	$wordscorrectnum = 0;
	$mistakes_incorrect = "";
	$mistakes = "";
	for($i = 1; $i < 50; $i++) {
		if($words = mysqli_fetch_assoc($wordsres)) {
			$word = $words["word"];
			$w_word = $_POST["w_$i"];
			
			$word = updateWord($word);
			$w_word = updateWord($w_word);
			
			if(trim($w_word) != trim($word)) {
				echo "<span class='show_words'>".$words["translate"]." - " . $_POST["w_$i"] . "</span><span class='lie'> - Неправильно!</span>";
				echo "<span class='show_words'> Відповідь: ".$words["word"]."<br>";
				$cont = 0;
				$word = str_replace("'", "''", $word);
				$mistakes_incorrect .= $_POST["w_$i"] . ",";
				$mistakes .= $word . ",";
			} else {
				echo "<span class='show_words'>".$words["translate"]." - ".$words["word"]."</span><span class='right'> - Правильно!</span><br>";
				$wordscorrectnum++;
			}
			$wordsnum++;
		}
		else break;
	}
	echo "<br/><div id='mainp'><a href='../check/check.php?block_id=$block_id'>Ще раз</a></div><br/>";
	echo "<div id='mainp'><a href='../index.php'>На головну</a></div>";
	echo "</div>";
	if($admin) echo "<a href='../ucp/ucp.php'><div id='ucp'>UCP-панель</div></a>";
	echo "<a href='../logout.php'><div id='logout'>Вихід</div></a>";
	echo "<a href='../info.php'><div id='info'>Інформація</div></a>";
	$mark = $wordscorrectnum * 12 / $wordsnum;
	$mark = floor($mark);
	$datetime = date("Y-m-d H:i:s");
	$type = $_REQUEST["test_control"] == 1 ? "1" : "3";
	
	$mistakes_incorrect = str_replace("'", "''", $mistakes_incorrect);
	$mistakes = str_replace("'", "''", $mistakes);
	
	mysqli_query($mysql, "INSERT INTO $TABLE_TEST_RESULTS (u_id, date_time, test_type, block_id, mark, mistakes_incorrect, mistakes, test_unique_id) VALUES ('$id', '$datetime', '$type', '$block_id', '$mark', '$mistakes_incorrect', '$mistakes', '$test_unique_id')") or die(mysqli_error($mysql));
	mysqli_close($mysql);
	
	echo "<script>document.getElementById('mark').innerHTML = '$mark балів!';</script>";
	
	if($cont == 1) {
		$src = '../good.mp3';
	}
	else {
		$src = '../bad.mp3';
	}
	echo "<script>
		var bd = document.getElementsByTagName('body')[0];
		var audio = document.createElement('audio');
		audio.src = '$src';
		audio.autoplay = true;
		bd.appendChild(audio);
	</script>";
	
?>