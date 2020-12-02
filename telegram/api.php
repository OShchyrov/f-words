<?php
	define("TABLE_MONEY", "telegram_table_money");
	define("TABLE_RECEIPT", "telegram_table_receipt");
	
	$data = json_decode(file_get_contents('php://input'), true);

	$message = $data["message"]["text"];
	$to = $data["message"]["from"]["id"];

	if (preg_match("/\/start/iu", $message)) {
		sendMessage($to, "Привіт! Вітаю тебе у себе вдома!\nБот використовується для різних цілей.\nСписок команд:\n- /stats - Статистика витрат\n- /m [сума] [опис] - додати витрату\n\n<b>Дякую що приєднались!</b>");
	} else if (preg_match("/\/stats/iu", $message)) {
		include_once "../mysql/mysql_connect.php";
		$result = mysqli_query($mysql, "SELECT * FROM " . TABLE_MONEY . " WHERE u_id = '$to'") or die(mysql_error($mysql));
		if (!$result || mysqli_num_rows($result) == 0) {
			sendMessage($to, "У вас ще немає ніяких витрат");
		} else {
			$msg = "Статистика витрат:\n";
			$sum = 0;
			while($row = mysqli_fetch_assoc($result)) {
				$msg .= "-> <b>" . $row["amount"] . "грн</b> - <b>" . $row["description"] . "</b> <i>[" . $row["dt"] . "]</i>\n";
				$sum += $row["amount"];
			}
			$msg .= "\nЗагальна сума, яку ви витратили: <b>$sum</b>грн";
			sendMessage($to, $msg);
		}
	} else if (preg_match("/\/m/iu", $message)) {
		preg_match("/\/m (\d+) (.*)/iu", $message, $matches);
		include_once "../mysql/mysql_connect.php";
		if (count($matches) < 3) {
			sendMessage($to, "Введіть: /m [сума] [опис витрати]");
		} else {
			$amount = $matches[1];
			$description = $matches[2];
			$dt = date("Y-m-d", time() + 3*3600);
			mysqli_query($mysql, "INSERT INTO " . TABLE_MONEY . " (u_id, amount, description, dt) VALUES ('$to', '$amount', '$description', '$dt')") or die(mysqli_error($mysql));
			sendMessage($to, "Додана витрата: $amount грн - <b>$description</b> - <i>$dt</i>");
		}
	} else if (preg_match("/\/fw/iu", $message)) {
		preg_match("/\/fw (.+) (.+)/iu", $message, $matches);
		include_once "../mysql/mysql_connect.php";
		if (count($matches) < 3) {
			sendMessage($to, "Введіть: /fw [ім'я] [пароль]");
		} else {
			$nikname = $matches[1];
			$password = $matches[2];
			$result = mysqli_query($mysql, "SELECT * FROM words_accounts WHERE login = '$nikname' AND password = '$password'") or die(mysqli_error($mysql));
			if (mysqli_num_rows($result) > 0) {
				mysqli_query($mysql, "UPDATE words_accounts SET telegram_id = $to WHERE login = '$nikname'") or die(mysqli_error($mysql));
				sendMessage($to, "<i>Ви прив'язали аккаунт Telegram.</i>");
			} else {
				sendMessage($to, "<b>Виникла помилка!</b> Неправильний логін/пароль!");
			}
		}
	} else if (preg_match("/\/addreceipt/iu", $message)) {
		preg_match("/\/addreceipt (.*)/iu", $message, $matches);
		include_once "../mysql/mysql_connect.php";
		if (count($matches) < 2) {
			sendMessage($to, "Введіть: /addreceipt [назва]");
		} else {
			$receipt = $matches[1];
			
			$result = mysqli_query($mysql, "SELECT * FROM " . TABLE_RECEIPT . " WHERE u_id = '$to' AND receipt = '$receipt'");
			if (mysqli_num_rows($result) == 0) {
				mysqli_query($mysql, "INSERT INTO " . TABLE_RECEIPT . " (u_id, receipt, ingredients, description) VALUES('$to', '$receipt', '', '')") or die(mysqli_error($mysql));
				sendMessage($to, "<i>Рецепт \"$receipt\" додано.</i>");
			} else {
				sendMessage($to, "Рецепт \"$receipt\" вже існує!");
			}
		}
	} else if (preg_match("/\/setreceipt/iu", $message)) {
		preg_match("/\/setreceipt (\w+) (\w*) (.*)/iu", $message, $matches);
		include_once "../mysql/mysql_connect.php";
		if (count($matches) < 4) {
			sendMessage($to, "Введіть: /setreceipt [назва] [ing | desc] [текст]");
		} else {
			$receipt = $matches[1];
			$type = $matches[2];
			$text = $matches[3];
			
			$result = mysqli_query($mysql, "SELECT * FROM " . TABLE_RECEIPT . " WHERE u_id = '$to' AND receipt = '$receipt'");
			if (!$result || mysqli_num_rows($result) < 1) {
				sendMessage($to, "Рецепт \"$receipt\" не знайдено!</i>");
			} else {
				$text = str_replace("'", "''", $text);
				if ($type == "ing") {
					mysqli_query($mysql, "UPDATE " . TABLE_RECEIPT . " SET ingredients = '$text' WHERE receipt = '$receipt' AND u_id = '$to'") or die(mysqli_error($mysql));
					sendMessage($to, "<i>Інгредієнти рецепту \"$receipt\" змінено.</i>");
				} else if ($type == "desc") {
					mysqli_query($mysql, "UPDATE " . TABLE_RECEIPT . " SET description = '$text' WHERE receipt = '$receipt' AND u_id = '$to'") or die(mysqli_error($mysql));
					sendMessage($to, "<i>Інформація рецепту \"$receipt\" змінено.</i>");
				} else {
					sendMessage($to, "Такий тип не знайдено!");
				}
			}
		}
	} else if (preg_match("/\/receipts/iu", $message)) {
		include_once "../mysql/mysql_connect.php";
		$result = mysqli_query($mysql, "SELECT * FROM " . TABLE_RECEIPT . " WHERE u_id = '$to'") or die(mysqli_error($mysql));
		$text = "<b>Ваші рецепти:</b>\n";
		while ($data = mysqli_fetch_assoc($result)) {
			$text .= $data["receipt"] . "\n";
		}
		if (strlen($text) == 0) {
			sendMessage($to, "У вас ще немає рецептів!");
		} else {
			sendMessage($to, $text);
		}
	} else if (preg_match("/\/receipt/iu", $message)) {
		preg_match("/\/receipt (.+)/iu", $message, $matches);
		include_once "../mysql/mysql_connect.php";
		if (count($matches) < 2) {
			sendMessage($to, "Введіть: /receipt [назва]");
		} else {
			$receipt = $matches[1];
			
			$result = mysqli_query($mysql, "SELECT * FROM " . TABLE_RECEIPT . " WHERE u_id = '$to' AND receipt = '$receipt'");
			if (mysqli_num_rows($result) > 0) {
				$data = mysqli_fetch_assoc($result);
				$text = "<b>Рецепт \"$receipt\":</b>\n";
				$ingredients = str_replace("\\n", "\n", $data["ingredients"]);
				$text .= "Інгредієнти: \n" . $ingredients . "\n";
				$description = str_replace("\\n", "\n", $data["description"]);
				$text .= "Рецепт: " . $description;
				sendMessage($to, $text);
			} else {
				sendMessage($to, "Рецепт \"$receipt\" не знайдено!");
			}
		}
	}	
	
	echo "true";
	
	function sendMessage($to, $text) {
		$text = urlencode($text);
		file_get_contents("http://" . $_SERVER['SERVER_NAME'] . "/telegram/telegrambot.php?to=$to&message=$text");
	}
?>