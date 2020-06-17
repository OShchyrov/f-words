<?php
	$MAX_SECONDS_COUNTDOWN_TIMER = 60;
	
	session_start();
	header("Content-Type: text/html; charset=utf-8");

	include "../mysql/mysql_connect.php";
	mysqli_query($mysql, "SET NAMES utf8");
	include '../header.php';
	$login = $_SESSION["login"];
	
	checkLogin();
	
	$res = mysqli_query($mysql, "SELECT * FROM $TABLE_ACCOUNTS WHERE `login` = '$login'");
	$u_id = mysqli_fetch_assoc($res)["id"];
	
	$res = mysqli_query($mysql, "SELECT * FROM $TABLE_CONTROL_TESTS WHERE u_id = $u_id AND test_type = 0") or die(mysqli_error($mysql));
	
	if ($res == 0 || mysqli_num_rows($res) == 0) {
		changeLocation("/check/words.php");
		exit;
	}
	
	$result = mysqli_query($mysql, "SELECT param_value FROM $TABLE_SETTINGS WHERE param_key = 'verbs_test_pwd'");
	$verbs_test_pwd = mysqli_fetch_assoc($result)['param_value'];
	
	$result = mysqli_query($mysql, "SELECT * FROM `$TABLE_ACCOUNTS` WHERE `login` = '$login'");
	$adm = mysqli_fetch_assoc($result);
	$admin = $adm["admin"];
	
	if (isset($_REQUEST["unsuccess_attempts"]) && $_REQUEST['unsuccess_attempts'] >= 2) {
		echo "<div style='padding-top: 30px;'><h1>Виявлено спробу взлому тесту!</h1></div>";
		echo "<div style='color: #FF1100; padding-top: 30px;'><h1>ВАШ АККАУНТ ЗАБЛОКОВАНО!</h1></div>";
		if ($admin)
			exit;
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
	
	if (!isset($_POST["access"]) || $_POST["access_pwd"] != $verbs_test_pwd) {
		$unsuccess_attempts = isset($_REQUEST["unsuccess_attempts"]) ? $_REQUEST["unsuccess_attempts"] : 0;
		if (isset($_POST["access_pwd"]) && $_POST["access_pwd"] != "" && $_POST["access_pwd"] != $verbs_test_pwd)
			$unsuccess_attempts++;
		echo "<div id=main><form method='post'>
		<h2>Диктант по неправильним дієсловам</h2>
		<input placeholder='Пароль для тест-контролю' style='margin-top:100px;' type='text' name='access_pwd' autocomplete='off'/><br/>
		<input type='submit' name='access' value='СТАРТ!' />
		<input type='hidden' name='TEST_MODE' value='1' />
		<a href='/'><div id='info'>Головна</div></a>
		<input type='hidden' name='unsuccess_attempts' value='$unsuccess_attempts' />
		</form></div>";
		exit;
	}
	
	echo "<script>document.getElementsByClassName('test_mode')[0].getElementsByTagName('h1')[0].innerHTML = 'ТЕСТ-КОНТРОЛЬ - Неправильні дієслова';</script>";
	echo "<div id='main'>";
	echo "<span class='test_number'></span>";
	echo "<div id='alll' style='padding-top:10px;'><span class='countdown'></span><table class='words_table'><form action='result_verb.php' method='post'>";
	
	$num_of_words = 1;
	$id = $_REQUEST["block_id"];
	$resis = mysqli_num_rows(mysqli_query($mysql, "SELECT * FROM `$TABLE_IRREGULAR_VERBS` WHERE `u_id` = '$id'"));
	if($resis == 0) {
		echo "<h1 style=\"color:red;\">No words found!</h1>";
		exit;
	}
	echo "<tr><td class='caption'>Переклад</td><td class='caption'>Неозначена форма</td><td class='caption'>Минулий час</td><td class='caption'>Дієпр. минулого часу</td></tr>";
	$result = mysqli_query($mysql, "SELECT * FROM `$TABLE_IRREGULAR_VERBS` WHERE `u_id` = '$id'");
	for($i = 1; $word = mysqli_fetch_assoc($result);$i++) {
		$aword = $word["verb_translate"];
		if($aword != '') {
			echo "<tr class='verbs_list'><td><span class='show_words'>".$aword."</span></td>"."<td><input onpaste='return false;' autocomplete='off' type='text' name='verb_inf_$i'></td>"."<td><input onpaste='return false;' autocomplete='off' type='text' name='verb_2_$i'></td>"."<td><input onpaste='return false;' autocomplete='off' type='text' name='verb_3_$i'></td><td><input type='button' value='Далі' onClick='showRandomRow()' class='nextClick' /></td></tr>";
			$num_of_words++;
		}
	}
	$test_unique_id = sha1(strval(microtime()));
	echo "<input type='hidden' name='test_unique_id' value='$test_unique_id'/>";
	echo "<input type='hidden' name='block_id' value='$id'/>";
	//echo "<input style='cursor: pointer;' type='submit' value='Перевірити мене'>";
	echo "</form></table></div></div>";
	$result = mysqli_query($mysql, "SELECT * FROM `$TABLE_ACCOUNTS` WHERE `login` = '$login'");
	$adm = mysqli_fetch_assoc($result);
	$admin = $adm["admin"];
	if($admin)
		echo "<a href='../ucp/ucp.php'><div id='ucp'>UCP-панель</div></a>";
	?>
	<a href='../logout.php'><div id='logout'>Вихід</div></a>
	<script>
		function checkSubmit(form) {
			return window.confirm('Перевірити вас?');
		}
		var showed = [0];
		var words = document.getElementsByClassName('words_table')[0].getElementsByTagName('tr');
		var test_number = document.getElementsByClassName('test_number')[0];
		var currentRow = -1;
		var countdown = document.getElementsByClassName('countdown')[0];
		hideRows();
		function showRow(index) {
			hideRow(currentRow);
			words[index].className = 'verbs_list verbs_show';
			showed.push(index);
			currentRow = index;
			startTimer();
			words[index].getElementsByTagName('input')[0].focus();
			test_number.innerText = (showed.length-1) + ' / ' + (words.length-1);
		}
		function hideRow(index) {
			if (index == -1) return;

			words[index].className = 'verbs_list verbs_hide';
		}
		function hideRows() {
			for (var i = 1; i < words.length; i++) {
				words[i].className = 'verbs_list verbs_hide';
			}	
		}
		function showAllRows() {
			for (var i = 0; i < words.length; i++) {
				words[i].style.display = 'table-row';
			}
		}
		function hideAllRows() {
			for (var i = 0; i < words.length; i++) {
				if (i != currentRow)
					words[i].style.display = 'none';
			}
		}
		function showRandomRow() {
			if (showed.length == words.length) {
				document.getElementsByTagName('form')[0].submit();
				return;
			}
			var index;
			do {
				index = Math.floor(Math.random() * words.length);
			} while(showed.includes(index));
			showRow(index);
		}
		function startTimer() {
			countdown.innerText = <?php echo $MAX_SECONDS_COUNTDOWN_TIMER; ?>;
			clearInterval(checkTimer);
			checkTimer = new Timer(function() {
				countdown.innerText = countdown.innerText-1;
				timer.resume();
				if (countdown.innerText == '0') {
					clearInterval(checkTimer);
					words[currentRow].getElementsByTagName('input')[3].click();
				}
			}, 1000);
		}
		showRandomRow();
		
		var inputs = document.getElementsByClassName("verbs_list");

		for (var i = 0; i < inputs.length; i++) {
			inputs[i].addEventListener("keyup", function(event) {
			  if (event.keyCode === 13) {
				event.preventDefault();
				showRandomRow();
			  }
			});
		}
	</script>
	<?php
	
	$block_id = $_REQUEST["block_id"];
	mysqli_query($mysql, "DELETE FROM $TABLE_CONTROL_TESTS WHERE u_id = $u_id AND test_type = 0 AND block_id = $block_id LIMIT 1") or die(mysqli_error($mysql));

	mysqli_close($mysql);
?>