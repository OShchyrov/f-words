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
			$core_msg = "<h2>Шановний(а) $username!</h2><h3>На вчорашній день вам було встановлено календарний план, проте вас не було в мережі!</h3>\
			<h3>Ми надсилаємо вам пункти які ви маєте виконати:</h3><br/><br/>";
			$found_date = date("Y-m-d", time());// - 24*3600);
			$result_plans = mysqli_query($mysql, "SELECT * FROM $TABLE_USER_PLANS WHERE u_id = $id AND dt = '$found_date'");
			$plan = mysqli_fetch_assoc($result_plans);
			if ((time() - $online) > 24*3600) {
				$request_params = array(
					"email" => $account["email"],
					"message" => $core_msg . $plan["description"]
				);
				file_get_contents("http://".$_SERVER["SERVER_NAME"]."/mailer.php?" . http_build_query($request_params));
				$count++;
			}
		}
		echo $count;
	}
?>