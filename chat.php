<?php 
	include "header.php";
	
	$login = $_SESSION["login"];
	$id = $_SESSION["uid"];
	if($login == "") changeLocation("../index.php");

	$result = mysqli_query($mysql, "SELECT * FROM `$TABLE_ACCOUNTS` WHERE `login` = '$login'");
	$adm = mysqli_fetch_array($result);
	$admin = $adm['admin'];
	if($admin) echo "<a href='../ucp/ucp.php'><div id='ucp'>UCP-панель</div></a>";

	$result = mysqli_query($mysql, "SELECT * FROM $TABLE_HELP_REQUESTS WHERE u_id = $id AND status = 1") or die(mysqli_error($mysql));
	if (!$result || mysqli_num_rows($result) == 0) {
		changeLocation("/");
		exit;
	} else {
		$request_id = mysqli_fetch_assoc($result)["id"];
	}
	echo "<h2>Online-допомога від адміністрації</h2>";
	include "chat_head.php";

	echo "<script>
	
		var form = document.getElementById('chat-form');
		form.request_id.value = $request_id;
	
	</script>";
?>