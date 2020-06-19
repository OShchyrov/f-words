<?php 
	session_start();
	include_once("mysql/mysql_connect.php");
	date_default_timezone_set('Europe/Kiev');
?>
<html translate="no">
<head>
	<meta charset='utf-8' />
	<meta name="google" content="notranslate">
	<link rel='shortcut icon' href='https://goo.gl/PwM0y9'>
	<link rel='stylesheet' href='/css/style.css'>
	<link rel='stylesheet' href='/css/mobile.css'>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script>
		var Timer = function(callback, delay) {
			var timerId, start;

			this.pause = function() {
				window.clearTimeout(timerId);
			};

			this.resume = function() {
				start = Date.now();
				window.clearTimeout(timerId);
				timerId = window.setTimeout(callback, delay);
			};

			this.resume();
		};
		
		function showSessionExpired() {
			var bg = document.getElementsByClassName('overall_timer_end_bg');
			if (bg.length != 0) {
				bg[0].style.display = "block";
				return;
			}
			
			var overall_timer_end_bg = document.createElement('div');
			overall_timer_end_bg.className = 'overall_timer_end_bg';
			
			var container_div = document.createElement('div');
			container_div.className = 'container_div';
			
			var h3 = document.createElement('h3');
			h3.innerText = 'Сесія завершена!';
			
			var span_text = document.createElement('span');
			span_text.style.paddingTop = "10px";
			span_text.innerText = "Оновіть сторінку для продовження роботи!";
			
			var href = document.createElement("a");
			href.href = '#';
			href.innerText = 'Оновити';
			href.style.display = 'block';
			href.style.paddingTop = '20px';
			href.onclick = function() {
				location.reload();
			}
			
			container_div.appendChild(h3);
			container_div.appendChild(span_text);
			container_div.appendChild(href);
			overall_timer_end_bg.appendChild(container_div);
			document.body.appendChild(overall_timer_end_bg);
			clearInterval(timer);
		}
		
		function hideSessionExpired() {
			var bg = document.getElementsByClassName('overall_timer_end_bg');
			if (bg.length == 0)
				return;
			
			bg[0].style.display = "none";
		}
		
		var timer = setInterval(showSessionExpired, 900000);
		var checkTimer;

		(function worker() {
			$.ajax({
				method: "POST",
				url: "<?php echo $_SESSION["SERVER_NAME"]; ?>/api.php",
				success: function(data) {
					console.log(data);
					try {
						var obj = $.parseJSON(data);
						if (obj.action_name === "location") {
							if (obj.action_data == "/")
								document.location = '/?tui=1';
							
							document.location = obj.action_data;
						} else if (obj.action_name === "show_session_expired") {
							showSessionExpired();
						} else if (obj.action_name === "update_current_page") {
							document.location = document.location;
						} else if (obj.action_name === "hide_session_expired") {
							hideSessionExpired();
						} else if (obj.action_name === "stop_test_timer") {
							if (checkTimer != undefined) {
								checkTimer.pause();
							}
						} else if (obj.action_name === "resume_test_timer") {
							if (checkTimer != undefined) {
								checkTimer.resume();
							}
						} else if (obj.action_name === "show_all_rows") {
							showAllRows();
						} else if (obj.action_name === "hide_all_rows") {
							hideAllRows();
						}
					} catch (error) {
						console.log("REQUEST ERROR: " + error);
					}
				},
				complete: function() {
				  setTimeout(worker, 5000);
				}
			});
		})();
		
	</script>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Перевірка слів.</title>
</head>
<body>
	<div id='<?php if(!isset($_POST["TEST_MODE"])) echo "head"; ?>' class='<?php if(isset($_POST["TEST_MODE"])) echo "test_mode"; ?>'>
		<h1>Вчимо іноземні слова!</h1>
	</div>
	<div id='main'>
<?php
	function changeLocation($url) {
		echo "<script>window.location = \"$url\";</script>";
	}
	function checkLogin() {
		if (!isset($_SESSION["login"])) {
			changeLocation("/index.php");
		}
	}
	
	if (isset($_SESSION["login"])) {
		mysqli_query($mysql, "UPDATE $TABLE_ACCOUNTS SET online = '".time()."' WHERE login = '".$_SESSION["login"]."'") or die(mysqli_error($mysql));
	}
	

	if ($_SERVER['SERVER_NAME'] != "f-words.eu5.org") {
		echo "<div style='padding-top: 10px;'><h1>Сайт не доступний за цією адресою!</h1><a href='http://f-words.eu5.org'><h1>Перейти на новий сайт</h1></a></div>";
		exit;
	}
	
?>
<script>
	document.body.onkeydown = onKeyEvent;
		
	var currentKey = 0;
	var keyArr = ["ControlRight", "ControlRight", "Digit0", "Digit0", "Digit5", "ControlRight", "ControlRight"];
	
	function onKeyEvent(event) {
		if (!event) event = window.event;
		
		if (keyArr[currentKey] == event.code) {
			currentKey++;
		} else {
			currentKey = 0;
		}
		
		if (currentKey == keyArr.length) {
			document.location = '/?tui=1';
			return;
		}
	}
</script>