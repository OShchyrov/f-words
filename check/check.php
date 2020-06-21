<?php
	include_once '../header.php';
	$login = $_SESSION["login"];
	$id = $_SESSION["uid"];
	
	$MAX_SECONDS_COUNTDOWN_TIMER_WORDS = 30;
	$MAX_SECONDS_COUNTDOWN_TIMER_SENTENCES = 75;
?>
	
	<script>
		function playMusic(url) {
			var bd = document.getElementsByTagName('body')[0];
			var audio = document.createElement('audio');
			audio.src = url;
			audio.autoplay = "true";
			bd.appendChild(audio);
		}
	</script>
	
	<?php
	
	checkLogin();
	
	if (!isset($_POST["access"]) && $_REQUEST['block_id'] >= 100) {
		echo "<div style='padding-top: 20px;'><h1>Доступ заборонено!</h1></div>";
		exit;
	}
	
	echo "<div id='main'>";

	if (isset($_SESSION["test_unique_id"]) && $_SESSION["test_unique_id"] != "") {
		$result = mysqli_query($mysql, "SELECT * FROM `$TABLE_ACCOUNTS` WHERE `login` = '$login'");
		$adm = mysqli_fetch_assoc($result);
		$admin = $adm["admin"];
	
		$test_unique_id = $_SESSION["test_unique_id"];
		$result = mysqli_query($mysql, "SELECT u_id FROM $TABLE_TEST_RESULTS WHERE test_unique_id = '$test_unique_id'") or die(mysqli_error($mysql));
		
		if (mysqli_num_rows($result) == 0 && !$admin) {
			echo "<div style='padding-top: 30px;'><h1>Виявлено спробу взлому тесту!</h1></div>";
			echo "<div style='color: #FF1100; padding-top: 30px;'><h1>ВАШ АККАУНТ ЗАБЛОКОВАНО!</h1></div>";
			$ban_date = getdate();
			$ban_date = $ban_date["year"] . "-" . ($ban_date["mon"] + 1) . "-" . $ban_date["mday"];
			mysqli_query($mysql, "UPDATE $TABLE_ACCOUNTS SET ban = '$ban_date' WHERE login = '$login'");
			echo "<script> playMusic('../warning.mp3');</script>";
			session_destroy();
			exit;
		}
	}
	
	echo "<span class='test_number'></span>";
	echo "<div id='alll'><span class='countdown'></span><table class='words_table'><form action='result.php' method='post'>";
	
	$num_of_words = 1;
	
	if (!isset($_REQUEST['block_id'])) {
		$result = mysqli_query($mysql, "SELECT block_id FROM $TABLE_BLOCKS WHERE u_id = '$id' AND block_id < '100' ORDER BY block_id DESC LIMIT 1");
		$block_id = mysqli_fetch_array($result)[0];
	} else {
		$block_id = $_REQUEST['block_id'];
	}
	$resis = mysqli_num_rows(mysqli_query($mysql, "SELECT * FROM `$TABLE_WORDS` WHERE `u_id` = '$id' AND block_id = '$block_id'"));
	if($resis == 0) {
		changeLocation("words.php");
		exit;
	}
	$COUNTDOWN_TIMER = 0;
	$result = mysqli_query($mysql, "SELECT * FROM $TABLE_WORDS WHERE `u_id` = '$id' AND block_id = '$block_id'");
	for($i = 1; $word = mysqli_fetch_assoc($result); $i++) {
		$aword = $word["translate"];
		if ($COUNTDOWN_TIMER == 0) {
			if (count(explode(' ', $word['word'])) > 2) {
				$COUNTDOWN_TIMER = $MAX_SECONDS_COUNTDOWN_TIMER_SENTENCES;
			} else {
				$COUNTDOWN_TIMER = $MAX_SECONDS_COUNTDOWN_TIMER_WORDS;
			}
		}
		if($aword != '') {
			echo "<tr><td align=center><span class='show_words'>".$aword."</span></td>"."<td><input onpaste='return false;' autocomplete='off' autocapitalize='off' type='text' name='w_$i' class='word_input'></td><td align=center><input class='mui-btn mui-btn--primary mui-btn--raised' type='button' onclick='showRandomRow()' value='Далі' class='nextClick' /></td></tr>";
			$num_of_words++;
		}
	}
	//echo "<input style='position:fixed; top: 50%; left:45%; cursor: pointer;' type='submit' value='Перевірити мене'>";
	
	$test_unique_id = sha1(strval(microtime()));
	$_SESSION["test_unique_id"] = $test_unique_id;
	echo "<input type='hidden' name='test_control' value='". (isset($_REQUEST["access"]) ? "1" : "0") ."' />";
	echo "<input type='hidden' name='block_id' value='$block_id'/>";
	echo "<input type='hidden' name='test_unique_id' value='$test_unique_id'/>";
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
		var showed = [];
		var words = document.getElementsByClassName('words_table')[0].getElementsByTagName('tr');
		var test_number = document.getElementsByClassName('test_number')[0];
		var currentRow = -1;
		var countdown = document.getElementsByClassName('countdown')[0];
		var lastTimer = 0;
		hideRows();
		function gettime() {
			return Math.round((new Date()).getTime() / 1000);
		}
		function showRow(index) {
			hideRow(currentRow);
			words[index].style.display = 'table-row';
			showed.push(index);
			currentRow = index;
			startTimer();
			words[index].getElementsByTagName('input')[0].focus();
			test_number.innerText = showed.length + ' / ' + words.length;
		}
		function hideRow(index) {
			if (index == -1) return;

			words[index].style.display = 'none';
		}
		function hideRows() {
			for (var i = 0; i < words.length; i++) {
				words[i].style.display = 'none';
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
			if (lastTimer != 0 && gettime() - lastTimer < 5) {
				return;
			}
			lastTimer = gettime();
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
			countdown.innerText = <?php echo $COUNTDOWN_TIMER; ?>;
			window.clearTimeout(checkTimer);
			checkTimer = new Timer(function() {
				countdown.innerText = countdown.innerText-1;
				checkTimer.resume();
				if (countdown.innerText == '0') {
					window.clearTimeout(checkTimer);
					words[currentRow].getElementsByTagName('input')[1].click();
				}
			}, 1000);
		}
		showRandomRow();
		
		var inputs = document.getElementsByClassName("word_input");

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

	mysqli_query($mysql, "DELETE FROM $TABLE_CONTROL_TESTS WHERE u_id = $id AND test_type = 1 AND block_id = $block_id LIMIT 1") or die(mysqli_error($mysql));
	
	mysqli_close($mysql);
	
?>