<?php

	$email = $_REQUEST["email"];
	$type = $_REQUEST["type"];
	$subject = "Вивчення англійської мови";
	
	$info = "";
	switch($type) {
		case "control_test": {
			$data = $_REQUEST["data"];
			$test_type = $data["test_type"];
			$block_id = $data["block_id"];
			switch($test_type) {
				case 0: {
					$test_name = "Неправильні дієслова";
					break;
				}
				case 1: {
					$test_name = "Словниковий диктант";
					break;
				}
			}
			$info = "Для вас увімкнено контрольний тест: " . $test_name . " #" . $block_id;
			break;
		}
	}
	
	$message = getMessage($info);
	
	$headers = "From: " . "F-Words" . "\r\n";
	$headers .= "Content-Type: text/html; charset=UTF-8\r\n";
	
	mail($email, $subject, $message, $headers);
	
	
	function getMessage($info) {
		return "<!DOCTYPE html>
	<head>
	<meta charset='utf-8' />
	<meta name='viewport' content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' />
	<title>Перевірка слів.</title>
	<style>
		html {
			height: 100%;
		}
		body {
			background-color: #c2c2c2;
			min-height: 100%;
			margin: 0;
		}
		#head {
			text-align: center;
			background-color: #2B51DB;
		}
		#head h1 {
			color: #27C439;
			font-size: 36px;
			padding-top: 40px;
			padding-bottom: 40px;
			margin: 0;
		}
		#main {
			background-color: #eeeeee;
			height: 100%;
			text-align: center;
		}
		#main h1 {
			color: #27C439;
			padding-bottom: 20px;
		}
		#main h2 {
			color: #62619f;
			padding-top: 20px;
			padding-bottom: 20px;
			margin: 0;
		}
		div a {
			display: inline-block;
			font-size: 24px;
			color: #3B93B8;
			background-color: #DACD6C;
			padding: 10px;
			margin: 30px;
			border-radius: 20px;
			text-decoration: none;
		}
		
		@media (max-width: 600px) {
			#head h1 {
				padding-top: 30px;
				padding-bottom: 30px;
			}
			#main h1 {
				font-size: 24px;
			}
			#main h2 {
				font-size: 18px;
			}
		}

	</style>
</head>
<body>
	<div id='head'>
		<h1>Вчимо іноземні слова!</h1>
	</div>
	<div id='main'>
	<div style='padding-top:20px;'><h1>$info</h1></div><br/>
	<div><h1>Дякуємо, що Ви з нами!</h2></div><br/>
	<div><h2>Команда F-Words.</h2></div><br/>";
	}
	
?>