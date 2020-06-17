<?php
	include "ucp/check_admin.php";
	include "mysql/mysql_connect.php";
	$login = $_SESSION["login"];
	$result = mysqli_query($mysql, "SELECT * FROM `$TABLE_ACCOUNTS` WHERE `login` = '$login'");
	$adm = mysqli_fetch_assoc($result);
	$admin = $adm["admin"];
	if(!$admin) {
		http_response_code(403);
	}
	
?>