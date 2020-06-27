<?php
	function getPathName($path) {
		if (strpos($path, "/check/words.php") !== false) {
			return "Переглядає головну сторінку";
		} else if (strpos($path, "/check/plan.php") !== false) {
			return "Переглядає календарний план";
		} else if (strpos($path, "/check/words_add.php") !== false) {
			return "Додає слова у блок";
		} else if (strpos($path, "/check/words_edit.php") !== false) {
			return "Редагує блок слів";
		} else if (strpos($path, "/check/close_block.php") !== false) {
			return "Закриває блок слів";
		} else if (strpos($path, "/check/block_mng.php") !== false) {
			return "Управляє блоками слів";
		} else if (strpos($path, "/check/my_results.php") !== false) {
			return "Переглядає свої результати";
		} else if (strpos($path, "/check/check.php") !== false) {
			return "Проходить тест";
		} else if (strpos($path, "/check/result.php") !== false) {
			return "Переглядає результат тестування";
		} else if (strpos($path, "/info.php") !== false) {
			return "Переглядає Info";
		} else if (strpos($path, "/chat.php") !== false) {
			return "Приймає участь у переписці";
		} else if (strpos($path, "/ucp") !== false) {
			return "Переглядає адміністративну панель";
		} else {
			return "Невідомо";
		}
	}
?>