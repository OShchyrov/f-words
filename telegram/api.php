<?php
	$data = json_decode(file_get_contents('php://input'), true);

	$message = $data["message"]["text"];
	$to = $data["message"]["from"]["id"];

	if (preg_match("/(\/start)/iu", $message)) {
		sendMessage($to, "Привіт! Вітаю. Тут ви будете отримувати повідомлення від мене.");
	}
	echo "true";
	
	function sendMessage($to, $text) {
		$text = urlencode($text);
		file_get_contents("http://" . $_SERVER['SERVER_NAME'] . "/telegram/telegrambot.php?to=$to&message=$text");
	}
?>