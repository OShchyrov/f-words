<?php
	function checkAdmin() {
		include "../mysql/mysql_connect.php";
		$login = $_SESSION["login"];
		$result = mysqli_query($mysql, "SELECT * FROM `$TABLE_ACCOUNTS` WHERE `login` = '$login'");
		$adm = mysqli_fetch_assoc($result);
		$admin = $adm["admin"];
		if(!$admin) {
			echo "<script>window.location = \"/index.php\";</script>";
			return 0;
		}
		return 1;
	}
?>