<?php
	checkCalendarPlans();
	
	
	function checkCalendarPlans() {
		include_once "mysql/mysql_connect.php";
		$result = mysqli_query($mysql, "SELECT * FROM $TABLE_ACCOUNTS");
		$count = 0;
		while($account = mysqli_fetch_assoc($result)) {
			$id = $account["id"];
			$username = $account["login"];
			$online = $account["online"];
			$core_msg = "<b>Шановний(а) $username!</b>\nНа вчорашній день вам було встановлено календарний план, проте вас не було в мережі!\n
			Ми надсилаємо вам пункти які ви маєте виконати:\n\n";
			$found_date = date("Y-m-d", time());// - 24*3600);
			$result_plans = mysqli_query($mysql, "SELECT * FROM $TABLE_USER_PLANS WHERE u_id = $id AND dt = '$found_date'");
			$plan = mysqli_fetch_assoc($result_plans);
			$core_msg .= $plan["description"];
			if ((time() - $online) > 24*3600) {
				$uid = $account["telegram_id"];
				if ($uid != 0) {
					//file_get_contents("http://" . $_SERVER['SERVER_NAME'] . "/telegram/telegrambot.php?to=$uid&message=" . urlencode($text));
				}
				$count++;
			}
		}
		echo $count;
	}
?>