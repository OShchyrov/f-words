<?php
	
	if ($_SERVER['SERVER_NAME'] != "l-words.000webhostapp.com") {
		$host = "192.168.0.22";
		$user = "root";
		$password = "";
		$database = "f_words";
	} else {
		$host = "localhost";
		$user = "id14120234_root";
		$password = "13579Sasha!!";
		$database = "id14120234_dbase";
	}
	$TABLE_ACCOUNTS = 'words_accounts';
	$TABLE_WORDS = 'words_user_words';
	$TABLE_IRREGULAR_VERBS = 'words_irregular_verbs';
	$TABLE_TEST_RESULTS = 'words_test_results';
	$TABLE_BLOCKS = 'words_blocks';
	$TABLE_CONTROL_TESTS = 'words_control_test';
	$TABLE_SETTINGS = 'words_settings';
	$TABLE_USER_ACTIONS = "words_user_actions";
	$TABLE_USER_PLANS = "words_user_plans";
	$TABLE_HELP_REQUESTS = "words_user_help_requests";
	$TABLE_CHAT = "words_user_chat";

	global $mysql;
	$mysql = mysqli_connect($host, $user, $password);
	if(!$mysql) echo "Error connecting to DATABASE<br>";

	$a = mysqli_select_db($mysql, $database) or die(mysqli_error($mysql));
	/*mysqli_query($mysql, "DROP TABLE $TABLE_ACCOUNTS");
	mysqli_query($mysql, "CREATE TABLE $TABLE_ACCOUNTS (
	id INT(10) AUTO_INCREMENT PRIMARY KEY,
	login VARCHAR(30) CHARACTER SET cp1251 COLLATE cp1251_ukrainian_ci NOT NULL,
	password VARCHAR(30) CHARACTER SET cp1251 COLLATE cp1251_ukrainian_ci NOT NULL,
	email VARCHAR(32) CHARACTER SET cp1251 COLLATE cp1251_ukrainian_ci NOT NULL,
	admin INT(1) NOT NULL,
	online INT(13) NOT NULL,
	ban VARCHAR(32)
	)") or die(mysqli_error($mysql));
	mysqli_query($mysql, "INSERT INTO `$TABLE_ACCOUNTS` (`login`, `password`, `admin`) VALUES ('admin', '123456', '1')");
	mysqli_query($mysql, "DROP TABLE $TABLE_WORDS");
	mysqli_query($mysql, "CREATE TABLE $TABLE_WORDS (
	id INT(10) AUTO_INCREMENT PRIMARY KEY,
	u_id INT(10) NOT NULL,
	block_id INT(10) NOT NULL,
	word VARCHAR(30) CHARACTER SET cp1251 COLLATE cp1251_ukrainian_ci NOT NULL,
	translate VARCHAR(30) CHARACTER SET cp1251 COLLATE cp1251_ukrainian_ci NOT NULL
	)") or die(mysqli_error($mysql));
	mysqli_query($mysql, "DROP TABLE $TABLE_IRREGULAR_VERBS");
	mysqli_query($mysql, "CREATE TABLE $TABLE_IRREGULAR_VERBS (
	id INT(10) AUTO_INCREMENT PRIMARY KEY,
	u_id INT(10) NOT NULL,
	verb_inf VARCHAR(30) CHARACTER SET cp1251 COLLATE cp1251_ukrainian_ci NOT NULL,
	verb_2 VARCHAR(30) CHARACTER SET cp1251 COLLATE cp1251_ukrainian_ci NOT NULL,
	verb_3 VARCHAR(30) CHARACTER SET cp1251 COLLATE cp1251_ukrainian_ci NOT NULL,
	verb_translate VARCHAR(30) CHARACTER SET cp1251 COLLATE cp1251_ukrainian_ci NOT NULL
	)") or die(mysqli_error($mysql));
	mysqli_query($mysql, "DROP TABLE $TABLE_TEST_RESULTS");
	mysqli_query($mysql, "CREATE TABLE $TABLE_TEST_RESULTS (
	id INT(10) AUTO_INCREMENT PRIMARY KEY,
	u_id INT(10) NOT NULL,
	date_time VARCHAR(32) CHARACTER SET cp1251 COLLATE cp1251_ukrainian_ci NOT NULL,
	test_type INT(10),
	block_id INT(10) DEFAULT 1,
	mark INT(10),
	mistakes_incorrect VARCHAR(1024) CHARACTER SET cp1251 COLLATE cp1251_ukrainian_ci NOT NULL,
	mistakes VARCHAR(1024) CHARACTER SET cp1251 COLLATE cp1251_ukrainian_ci NOT NULL,
	test_unique_id VARCHAR(128) CHARACTER SET cp1251 COLLATE cp1251_ukrainian_ci NOT NULL
	)") or die(mysqli_error($mysql));
	mysqli_query($mysql, "DROP TABLE $TABLE_BLOCKS");
	mysqli_query($mysql, "CREATE TABLE $TABLE_BLOCKS (
	u_id INT(10) NOT NULL,
	block_id INT(11),
	status INT(10),
	PRIMARY KEY(u_id, block_id)
	)") or die(mysqli_error($mysql));
	mysqli_query($mysql, "DROP TABLE $TABLE_CONTROL_TESTS");
	mysqli_query($mysql, "CREATE TABLE $TABLE_CONTROL_TESTS (
	id INT(10) AUTO_INCREMENT PRIMARY KEY,
	u_id INT(10) NOT NULL,
	test_type INT(10) NOT NULL,
	block_id INT(10) NOT NULL
	)") or die(mysqli_error($mysql));
	mysqli_query($mysql, "DROP TABLE $TABLE_SETTINGS");
	mysqli_query($mysql, "CREATE TABLE $TABLE_SETTINGS (
	param_key VARCHAR(32) CHARACTER SET cp1251 COLLATE cp1251_ukrainian_ci NOT NULL PRIMARY KEY,
	param_value VARCHAR(32) CHARACTER SET cp1251 COLLATE cp1251_ukrainian_ci NOT NULL
	)") or die(mysqli_error($mysql));
	mysqli_query($mysql, "INSERT INTO $TABLE_SETTINGS VALUES ('simple_test_pwd', ''), ('verbs_test_pwd', '')");
	mysqli_query($mysql, "DROP TABLE $TABLE_USER_ACTIONS");
	mysqli_query($mysql, "CREATE TABLE $TABLE_USER_ACTIONS (
	u_id INT(10) NOT NULL,
	action_name VARCHAR(32) CHARACTER SET cp1251 COLLATE cp1251_ukrainian_ci NOT NULL,
	action_data VARCHAR(256) CHARACTER SET cp1251 COLLATE cp1251_ukrainian_ci NOT NULL
	)") or die(mysqli_error($mysql));
	mysqli_query($mysql, "DROP TABLE $TABLE_USER_PLANS");
	mysqli_query($mysql, "CREATE TABLE $TABLE_USER_PLANS (
	u_id INT(10) NOT NULL,
	dt DATE NOT NULL,
	description VARCHAR(2048) CHARACTER SET cp1251 COLLATE cp1251_ukrainian_ci NOT NULL,
	PRIMARY KEY(u_id, dt)
	)") or die(mysqli_error($mysql));
	mysqli_query($mysql, "DROP TABLE $TABLE_HELP_REQUESTS");
	mysqli_query($mysql, "CREATE TABLE $TABLE_HELP_REQUESTS (
	id INT(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
	u_id INT(10) NOT NULL,
	dt DATETIME NOT NULL,
	admin INT(10) NOT NULL,
	status INT(1) NOT NULL
	)") or die(mysqli_error($mysql));
	mysqli_query($mysql, "DROP TABLE $TABLE_CHAT");
	mysqli_query($mysql, "CREATE TABLE $TABLE_CHAT (
	id INT(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
	request_id INT(11) NOT NULL,
	u_id INT(10) NOT NULL,
	dt DATETIME NOT NULL,
	message VARCHAR(1024) CHARACTER SET cp1251 COLLATE cp1251_ukrainian_ci NOT NULL
	)") or die(mysqli_error($mysql));
	*/
?>