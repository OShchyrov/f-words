<?php
	include '../header.php';
	include "check_admin.php";
	checkAdmin();
?>
	<div style='padding-top: 10px;'></div>
	<a href='../ucp/ucp.php'><div id='ucp'>UCP-панель</div></a>
	<a href='../index.php'><div id='info'>Головна</div></a>	
<?php
	if (!isset($_REQUEST["userid"]) && !isset($_REQUEST["request_id"]) && !isset($_REQUEST["close"])) {
		$result = mysqli_query($mysql, "SELECT r.login, hr.* FROM $TABLE_HELP_REQUESTS AS hr INNER JOIN $TABLE_ACCOUNTS AS r WHERE hr.u_id = r.id");
		echo "<table align=center id='table_results'>";
		echo "<tr class='caption'><td><b>Користувач</b></td><td><b>Дата</b></td><td><b>Ім'я адміністратора</b></td><td><b>Статус</b></td><td><b>Дія</b></td></tr>";
		while($row = mysqli_fetch_assoc($result)) {
			$request_id = $row["id"];
			$uid = $row["u_id"];
			$login = $row["login"];
			$dt = $row["dt"];
			$status_id = $row["status"];
			$admin_id = $row["admin"];
			if ($admin_id != 0) {
				$res = mysqli_query($mysql, "SELECT login FROM $TABLE_ACCOUNTS WHERE id = $admin_id");
				$admin_name = mysqli_fetch_assoc($res)["login"];
			} else {
				$admin_name = "Немає";
			}
			switch($status_id) {
				case 0: {
					$status = "Не прийнято";
					$connect = "Прийняти";
					break;
				}
				case 1: {
					$status = "Прийнято";
					$connect = "Підключитись";
					break;
				}
				case 2: {
					$status = "Закрито";
					$connect = "Переглянути";
					break;
				}
				default: {
					$status = "Невідомо";
					$connect = "error";
					break;
				}
			}
			echo "<tr><td>$login</td><td>$dt</td><td>$admin_name</td><td>$status</td><td><a href='online-help.php?userid=$uid&request_id=$request_id'><input type='button' value='$connect' class='mui-btn mui-btn--primary mui-btn--raised' /></a></td></tr>";
		}
		echo "</table>";
	} else if (isset($_REQUEST["userid"]) && !isset($_REQUEST["close"]) && !isset($_REQUEST["open"])) {
		$request_id = $_REQUEST["request_id"];
		$id = $_SESSION["uid"];
		$target_id = $_REQUEST["userid"];
		
		$result = mysqli_query($mysql, "SELECT * FROM $TABLE_ACCOUNTS WHERE id = $target_id") or die(mysqli_error($mysql));
		$username = mysqli_fetch_assoc($result)["login"];
		
		$result = mysqli_query($mysql, "SELECT * FROM $TABLE_HELP_REQUESTS WHERE id = $request_id");
		$result = mysqli_fetch_assoc($result);
		if ($result["status"] == 0) {
			mysqli_query($mysql, "UPDATE $TABLE_HELP_REQUESTS SET admin = $id, status = 1 WHERE id = $request_id");
			mysqli_query($mysql, "INSERT INTO $TABLE_USER_ACTIONS (u_id, action_name, action_data) VALUES ($target_id, 'chat_approved', '$request_id')");
			$result["status"] = 1;
		}
		if ($result["status"] == 2) {
			echo "<h1>Чат закритий</h1>";
			include "../chat_head.php";
			$request_id = $_REQUEST["request_id"];
			echo "<script>
			
				var form = document.getElementById('chat-form');
				form.request_id.value = $request_id;
			
			</script>";
			echo "<br/><a href='online-help.php?request_id=$request_id&open=1&userid=$target_id'><input type='button' value='Відкрити чат' class='mui-btn mui-btn--primary mui-btn--raised mui-btn--danger' /></a>";
		}
		if ($result["status"] == 1) {
			echo "<h1>Чат з $username</h1>";
			include "../chat_head.php";
			$request_id = $_REQUEST["request_id"];
			echo "<script>
			
				var form = document.getElementById('chat-form');
				form.request_id.value = $request_id;
			
			</script>";
			echo "<br/><a href='online-help.php?request_id=$request_id&close=1&userid=$target_id'><input type='button' value='Закрити чат' class='mui-btn mui-btn--primary mui-btn--raised mui-btn--danger' /></a>";
		}
	} else if (isset($_REQUEST["request_id"]) && isset($_REQUEST["close"])) {
		$request_id = $_REQUEST["request_id"];
		$id = $_SESSION["uid"];
		$dt = date("Y-m-d H:i:s");
		$userid = $_REQUEST["userid"];
		
		mysqli_query($mysql, "UPDATE $TABLE_HELP_REQUESTS SET status = 2 WHERE id = $request_id") or die(mysql_error($mysql));
		mysqli_query($mysql, "INSERT INTO $TABLE_CHAT (request_id, u_id, dt, message) VALUES ('$request_id', '$id', '$dt', 'chat_closed')") or die(mysql_error($mysql));
		changeLocation("/ucp/online-help.php?userid=$userid&request_id=$request_id");
	} else if (isset($_REQUEST["request_id"]) && isset($_REQUEST["open"])) {
		$request_id = $_REQUEST["request_id"];
		$id = $_SESSION["uid"];
		$dt = date("Y-m-d H:i:s");
		$userid = $_REQUEST["userid"];
		
		mysqli_query($mysql, "UPDATE $TABLE_HELP_REQUESTS SET status = 1 WHERE id = $request_id") or die(mysql_error($mysql));
		mysqli_query($mysql, "INSERT INTO $TABLE_CHAT (request_id, u_id, dt, message) VALUES ('$request_id', '$id', '$dt', 'chat_opened')") or die(mysql_error($mysql));
		changeLocation("/ucp/online-help.php?userid=$userid&request_id=$request_id");
	}
	
?>