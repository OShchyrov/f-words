<?php
	session_start();
	$login = $_SESSION["login"];
	include "mysql/mysql_connect.php";
	$response = array();
	$result = mysqli_query($mysql, "SELECT * FROM $TABLE_ACCOUNTS WHERE login = '$login' LIMIT 1");
	$id = mysqli_fetch_assoc($result)["id"];
	$result = mysqli_query($mysql, "SELECT * FROM $TABLE_USER_ACTIONS WHERE u_id = '$id' LIMIT 1");
	if ($result != 0 && mysqli_num_rows($result) > 0) {
		$row = mysqli_fetch_assoc($result);
		$action_name = $row["action_name"];
		$action_data = $row["action_data"];
		$response["action_name"] = $action_name;
		$response["action_data"] = $action_data;
		mysqli_query($mysql, "DELETE FROM $TABLE_USER_ACTIONS WHERE u_id = '$id' LIMIT 1") or die(mysqli_error($mysql));
	}
	mysqli_close($mysql);
	echo json_encode($response);
?>