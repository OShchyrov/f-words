<?php
	define("API_KEY", "1160258916:AAHAuisaTCZlBkZc8wjgSOFPXMQvx5DDK_g");
	define("API_ENDPOINT", "https://api.telegram.org/bot" . API_KEY);
	
	$msg = "";
	if (isset($_REQUEST["message"])) {
		$msg = $_REQUEST["message"];
	}
	$to = 349554089;
	
	if (isset($_REQUEST["to"])) {
		$to = $_REQUEST["to"];
	}
	
	sendMessage($to, $msg);
	
	function sendMessage($to, $text) {
		$url = API_ENDPOINT . "/sendMessage";
		$request_params = array(
			"chat_id" => $to,
			"text" => $text,
			"parse_mode" => "HTML"
		);
		file_get_contents($url . "?" . http_build_query($request_params));
	}
?>