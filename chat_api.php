<?php
	session_start();
	include "mysql/mysql_connect.php";
	if (isset($_REQUEST["request"])) {
		$login = $_SESSION["login"];
		$id = $_SESSION["uid"];
		
		$result = mysqli_query($mysql, "SELECT * FROM $TABLE_ACCOUNTS WHERE id = $id");
		$admin = mysqli_fetch_assoc($result)["admin"];
		
		if ($admin) {
			echo "error";
			exit;
		}
		
		$result = mysqli_query($mysql, "SELECT * FROM $TABLE_HELP_REQUESTS WHERE u_id = '$id' AND status = 0");
		if (!$result || mysqli_num_rows($result) == 0) {
			$dt = date("Y-m-d H:i:s");
			mysqli_query($mysql, "INSERT INTO $TABLE_HELP_REQUESTS (u_id, dt, status) VALUES ('$id', '$dt', '0')");
			
			echo "ok";
		} else echo "error";
	} else if (isset($_REQUEST["get"])) {
		$login = $_SESSION["login"];
		$id = $_SESSION["uid"];

		$result = mysqli_query($mysql, "SELECT * FROM $TABLE_HELP_REQUESTS WHERE u_id = '$id' AND (status = '0' OR status = '1')");
		if (!$result || mysqli_num_rows($result) == 0) {
			echo "no";
		} else {
			$result = mysqli_fetch_assoc($result);
			if ($result["status"] == 1) 
				echo "yes";
			else echo "requested";
		};
	} else if (isset($_REQUEST["send"])) {
		$message = $_REQUEST["message"];
		$request_id = $_REQUEST["request_id"];
		$id = $_SESSION["uid"];
		$dt = date("Y-m-d H:i:s");
		
		$result = mysqli_query($mysql, "SELECT * FROM $TABLE_HELP_REQUESTS WHERE id = $request_id");
		$result = mysqli_fetch_assoc($result);
		if ($result["status"] == 1) {
			mysqli_query($mysql, "INSERT INTO $TABLE_CHAT (request_id, u_id, dt, message) VALUES ('$request_id', '$id', '$dt', '$message')");
		}
	} else if (isset($_REQUEST["load"])) {
		$request_id = $_REQUEST["request_id"];
		if ($request_id == -1) {
			echo "Завантаження...";
			exit;
		}
		$user_id = $_SESSION["uid"];
		$result = mysqli_query($mysql, "SELECT acc.login, acc.admin, chat.* FROM $TABLE_CHAT AS chat INNER JOIN $TABLE_ACCOUNTS AS acc ON chat.u_id = acc.id WHERE request_id = $request_id ORDER BY chat.id");
		if (!$result || mysqli_num_rows($result) == 0) {
			echo "Повідомлення відсутні";
		} else {
			while($msg = mysqli_fetch_assoc($result)) {
				$login = $msg["login"];
				$msg_id = $msg["u_id"];
				$message = $msg["message"];
				$dt = $msg["dt"];
				$admin = $msg["admin"];
				if ($message === "chat_closed") {
					echo "<div class='chat__message chat__message_system chat__message_admin'><span>$login</span> закрив чат</div>";
				} else if ($message === "chat_opened") {
					echo "<div class='chat__message chat__message_system chat__message_admin'><span>$login</span> відкрив чат</div>";
				} else {
					if ($admin) {
						echo "<div class='chat__message chat__message_admin ".($user_id == $msg_id ? ("chat__message_current_user") : ("") )."'><span>$login:</span> $message</div>";
					} else {
						echo "<div class='chat__message chat__message_user ".($user_id == $msg_id ? ("chat__message_current_user") : ("") )."'><span>$login:</span> $message</div>";
					}
				}
			}
		}
	} else {
		echo "error";
	}
	mysqli_close($mysql);
?>
