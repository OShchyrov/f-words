<?php
	include '../header.php';
	include "check_admin.php";
	checkAdmin();
	echo "<div id='main'>";

	echo "<div style='padding-top: 20px;'>";
	
	echo "<a href='../ucp/ucp.php'><div id='ucp'>UCP-панель</div></a>";
	
	echo "<a href='../index.php'><div id='info'>Головна</div></a>";
	
	if(!isset($_REQUEST['username']) && !isset($_REQUEST["type"])) {
		echo "<a href='words_editor.php?type=1'><input type='button' name='type' class='mui-btn mui-btn--primary mui-btn--raised' value='Неправильні дієслова' /></a>";
		echo "<h1>Оберіть користувача для редагування словнику</h1>";
		$result = mysqli_query($mysql, "SELECT * FROM `$TABLE_ACCOUNTS`");
		while($row = mysqli_fetch_assoc($result)) {
			echo "<span class='show_words'><a href='words_editor.php?username=".$row["login"]."'> Користувач: ".$row["login"]."</a></span><br>";
		}
	} else if (isset($_REQUEST["type"]) && !isset($_REQUEST["block_id"]) && !isset($_REQUEST["irr_add"])) {
		echo "<a href='words_editor.php?type=1&irr_add=1'><input class='mui-btn mui-btn--primary mui-btn--raised' type='button' value='Додати новий' /></a><br/><br/>";
		$blocks = mysqli_query($mysql, "SELECT DISTINCT u_id FROM $TABLE_IRREGULAR_VERBS");
		while ($block = mysqli_fetch_assoc($blocks)) { 
			$block_id = $block['u_id'];

			$result = mysqli_query($mysql, "SELECT * FROM $TABLE_IRREGULAR_VERBS WHERE u_id = $block_id");
			$counter = 1;
			echo "<span class='table_word_block'><h2>Блок слів #$block_id</h2><table>";
			while($row = mysqli_fetch_assoc($result)) {
				echo "<tr><td class='words_num'>$counter</td><td class='td_l'>".$row['verb_inf']."</td><td class='td_l'>".$row['verb_2']."</td><td class='td_l'>".$row['verb_3']."</td><td class='td_r'>".$row['verb_translate']."</td></tr>";
				$counter++;
			}
			echo "<tr><td colspan=5 style='text-align:center'><a href='words_editor.php?type=1&block_id=$block_id'><input class='mui-btn mui-btn--primary mui-btn--raised' type='button' value='Обрати блок' /></a></td></tr>";
			echo "</table></span>";
		}
	} else if (isset($_REQUEST["type"]) && isset($_REQUEST["irr_add"]) && !isset($_REQUEST["add"])) {
		echo "<h1>Додавання нового блоку неправильних дієслів</h1>";
		$blocks = mysqli_query($mysql, "SELECT u_id FROM $TABLE_IRREGULAR_VERBS ORDER BY u_id DESC LIMIT 1");
		$block_id = mysqli_fetch_assoc($blocks)["u_id"] + 1;
		echo "<span class='table_word_block'><h2>Блок слів #$block_id</h2><form method=post'><table id='irr_table'>";

		echo "<tr><td class='words_num'>1</td>
		<td class='td_l'><input type='text' name='v_inf_0' value='' /></td>
		<td class='td_l'><input type='text' name='v_2_0' value='' /></td>
		<td class='td_l'><input type='text' name='v_3_0' value='' /></td>
		<td class='td_l'><input type='text' name='v_tr_0' value='' /></td></tr>";
		
		echo "<tr><input type='hidden' name='type' value='1' />";
		echo "<tr><input type='hidden' name='verb_count' value='1' />";
		echo "<tr><input type='hidden' name='irr_add' value='1' />";
		echo "<td colspan=5 style='text-align:center'>
		<input id='add_row' class='mui-btn mui-btn--primary mui-btn--raised' type='button' value='+' />
		<input class='mui-btn mui-btn--primary mui-btn--raised' type='submit' name='add' value='Додати блок' /></td></tr>";
		echo "</table></form></span>";
?>
	<script>
		var add_row = document.getElementById('add_row');
		add_row.onclick = function() {
			var irr_table = document.getElementById('irr_table').getElementsByTagName('tbody')[0];
			var tr = document.createElement('tr');
			var __index = document.getElementsByName('verb_count')[0];
			var index = parseInt(__index.value);
			console.log(__index);
			tr.innerHTML = "<td class='words_num'>"+ (index+1) + "</td><td class='td_l'><input type='text' name='v_inf_" + index + "' value='' /></td><td class='td_l'><input type='text' name='v_2_" + index + "' value='' /></td><td class='td_l'><input type='text' name='v_3_" + index + "' value='' /></td><td class='td_l'><input type='text' name='v_tr_" + index + "' value='' /></td>";
			console.log(irr_table.children[irr_table.childElementCount-1]);
			irr_table.insertBefore(tr, irr_table.children[irr_table.childElementCount-1]);
			__index.value = (index+1);
		}
	</script>
	<?php
	} else if (isset($_REQUEST["type"]) && isset($_REQUEST["irr_add"]) && isset($_REQUEST["add"])) {
		$max = $_REQUEST["verb_count"];
		$blocks = mysqli_query($mysql, "SELECT u_id FROM $TABLE_IRREGULAR_VERBS ORDER BY u_id DESC LIMIT 1");
		$block_id = mysqli_fetch_assoc($blocks)["u_id"] + 1;
		for ($i = 0; $i < $max; $i++) {
			$id = $_REQUEST["id_$i"];
			$inf = $_REQUEST["v_inf_$i"];
			$v2 = $_REQUEST["v_2_$i"];
			$v3 = $_REQUEST["v_3_$i"];
			$tr = $_REQUEST["v_tr_$i"];
			mysqli_query($mysql, "INSERT INTO $TABLE_IRREGULAR_VERBS (u_id, verb_inf, verb_2, verb_3, verb_translate) VALUES ('$block_id', '$inf', '$v2', '$v3', '$tr')");
		}
		changeLocation("words_editor.php?type=1&block_id=$block_id");
	} else if (isset($_REQUEST["type"]) && isset($_REQUEST["block_id"]) && !isset($_REQUEST["update"]) && !isset($_REQUEST["delete"]) && !isset($_REQUEST["del_verb"])) {
		$block_id = $_REQUEST["block_id"];
		echo "<a href='words_editor.php?type=1&block_id=$block_id&delete=1'><input type='button' class='mui-btn mui-btn--primary mui-btn--raised mui-btn--danger' value='Видалити блок' /></a><br/><br/>";
		$result = mysqli_query($mysql, "SELECT * FROM $TABLE_IRREGULAR_VERBS WHERE u_id = $block_id");
		if ($result == 0 || mysqli_num_rows($result) == 0) {
			changeLocation("words_editor.php?type=1");
		}
		$index = 0;
		echo "<span class='table_word_block'><h2>Блок слів #$block_id</h2><form method=post'><table id='irr_table'>";
		while($row = mysqli_fetch_assoc($result)) {
			$inf = $row["verb_inf"];
			$v2 = $row["verb_2"];
			$v3 = $row["verb_3"];
			$id = $row["id"];
			$translate = $row["verb_translate"];
			echo "<tr><td class='words_num'>". ($index+1) . "</td>
			<input type='hidden' name='id_$index' value='$id' />
			<td class='td_l'><input type='text' name='v_inf_$index' value='$inf' /></td>
			<td class='td_l'><input type='text' name='v_2_$index' value='$v2' /></td>
			<td class='td_l'><input type='text' name='v_3_$index' value='$v3' /></td>
			<td class='td_l'><input type='text' name='v_tr_$index' value='$translate' /></td>
			<td class='td_l'><input type='button' class='mui-btn mui-btn--primary mui-btn--raised mui-btn--danger remove' onClick='remove(this)' value='X' /></td></tr>";
			$index++;
		}
		echo "<tr><input type='hidden' name='type' value='1' />";
		echo "<tr><input type='hidden' name='block_id' value='$block_id' />";
		echo "<tr><input type='hidden' name='verb_count' value='$index' />";
		echo "<td colspan=5 style='text-align:center'>
		<input id='add_row' class='mui-btn mui-btn--primary mui-btn--raised' type='button' value='+' />
		<input class='mui-btn mui-btn--primary mui-btn--raised' type='submit' name='update' value='Оновити' /></td></tr>";
		echo "</table></form></span>";
?>
	<script>
		var add_row = document.getElementById('add_row');
		add_row.onclick = function() {
			var irr_table = document.getElementById('irr_table').getElementsByTagName('tbody')[0];
			var tr = document.createElement('tr');
			var __index = document.getElementsByName('verb_count')[0];
			var index = parseInt(__index.value);
			console.log(__index);
			tr.innerHTML = "<input type='hidden' name='id_" + index + "' value='new' /><td class='words_num'>"+ (index+1) + "</td><td class='td_l'><input type='text' name='v_inf_" + index + "' value='' /></td><td class='td_l'><input type='text' name='v_2_" + index + "' value='' /></td><td class='td_l'><input type='text' name='v_3_" + index + "' value='' /></td><td class='td_l'><input type='text' name='v_tr_" + index + "' value='' /></td><td class='td_l'><input type='button' value='X' class='mui-btn mui-btn--primary mui-btn--raised mui-btn--danger remove' onClick='remove(this)' /></td>";
			console.log(irr_table.children[irr_table.childElementCount-1]);
			irr_table.insertBefore(tr, irr_table.children[irr_table.childElementCount-1]);
			__index.value = (index+1);
		}
		function remove(node) {
			var removes = document.getElementsByClassName('remove');
			var id = -1;
			for (var i = 0; i < removes.length; i++) {
				if (removes[i] != node)
					continue;
				id = i;
				break;
			}
			var ind = document.getElementsByName('id_' + id)[0].value;
			if (ind == "new") {
				document.location = document.location;
			} else {
				document.location = "words_editor.php?type=1&block_id=<?php echo $block_id ?>&del_verb=" + ind;
			}
		}
	</script>
	<?php
	} else if (isset($_REQUEST["type"]) && isset($_REQUEST["block_id"]) && !isset($_REQUEST["update"]) && !isset($_REQUEST["delete"]) && isset($_REQUEST["del_verb"])) {
		$del_verb = $_REQUEST["del_verb"];
		$block_id = $_REQUEST["block_id"];
		
		mysqli_query($mysql, "DELETE FROM $TABLE_IRREGULAR_VERBS WHERE id = $del_verb");
		
		changeLocation("words_editor.php?type=1&block_id=$block_id");
	} else if (isset($_REQUEST["type"]) && isset($_REQUEST["block_id"]) && isset($_REQUEST["delete"]) && !isset($_REQUEST["approve"])) {
		$block_id = $_REQUEST["block_id"];
		echo "<h1>Дійсно видалити <b>Неправильні дієслова #$block_id</b>?</h1>";
		echo "<a style='margin-right: 20px;' href='words_editor.php?type=1&block_id=$block_id&delete=1&approve=1'/><input type='button' value='Так' class='mui-btn mui-btn--primary mui-btn--raised mui-btn--danger' /></a>";
		echo "<a href='words_editor.php?type=1&block_id=$block_id'/><input type='button' value='Ні' class='mui-btn mui-btn--primary mui-btn--raised' /></a>";
	} else if (isset($_REQUEST["type"]) && isset($_REQUEST["block_id"]) && isset($_REQUEST["delete"]) && isset($_REQUEST["approve"])) {
		$block_id = $_REQUEST["block_id"];
		mysqli_query($mysql, "DELETE FROM $TABLE_IRREGULAR_VERBS WHERE u_id = '$block_id'");
		
		changeLocation("words_editor.php?type=1");
	} else if (isset($_REQUEST["type"]) && isset($_REQUEST["block_id"]) && isset($_REQUEST["update"])) {
		$max = $_REQUEST["verb_count"];
		$block_id = $_REQUEST["block_id"];
		for ($i = 0; $i < $max; $i++) {
			$id = $_REQUEST["id_$i"];
			$inf = $_REQUEST["v_inf_$i"];
			$v2 = $_REQUEST["v_2_$i"];
			$v3 = $_REQUEST["v_3_$i"];
			$tr = $_REQUEST["v_tr_$i"];
			if ($id === "new") {
				mysqli_query($mysql, "INSERT INTO $TABLE_IRREGULAR_VERBS (u_id, verb_inf, verb_2, verb_3, verb_translate) VALUES ('$block_id', '$inf', '$v2', '$v3', '$tr')");
			} else {
				mysqli_query($mysql, "UPDATE $TABLE_IRREGULAR_VERBS SET verb_inf = '$inf', verb_2 = '$v2', verb_3 = '$v3', verb_translate = '$tr' WHERE id = '$id'");
			}
		}
		changeLocation("words_editor.php?type=1&block_id=$block_id");
	}
	else if (isset($_REQUEST["username"]) && !isset($_REQUEST["block_id"])) {
		$username = $_REQUEST["username"];
		$result = mysqli_query($mysql, "SELECT * FROM $TABLE_ACCOUNTS WHERE login = '$username'");
		$target_id = mysqli_fetch_assoc($result)['id'];
		
		echo "<h1>Оберіть блок слів користувача $username для редагування</h1>";
		
		$blocks = mysqli_query($mysql, "SELECT DISTINCT block_id FROM $TABLE_WORDS WHERE u_id = '$target_id' ORDER BY block_id ASC");
		while ($block = mysqli_fetch_assoc($blocks)) { 
			$block_id = $block['block_id'];
			
			$result = mysqli_query($mysql, "SELECT * FROM $TABLE_WORDS WHERE `u_id` = '$target_id' AND block_id = '$block_id'");
			$counter = 1;
			echo "<span class='table_word_block'><h2>Блок слів #$block_id</h2><table>";
			while($row = mysqli_fetch_assoc($result)) {
				echo "<tr><td class='words_num'>$counter</td><td class='td_l'>".$row['word']."</td><td class='td_r'>".$row['translate']."</td></tr>";
				$counter++;
			}
			echo "<tr><td colspan=3><a href='words_editor.php?username=$username&block_id=$block_id'><input type='button' value='Редагувати блок' class='mui-btn mui-btn--primary mui-btn--raised' /></a></td></tr>";
			echo "</table></span>";
		}
	} else if (isset($_REQUEST["username"]) && isset($_REQUEST["block_id"]) && !isset($_REQUEST["edit"])) {
		$username = $_REQUEST["username"];
		$block_id = $_REQUEST["block_id"];
		$result = mysqli_query($mysql, "SELECT * FROM $TABLE_ACCOUNTS WHERE login = '$username'");
		$target_id = mysqli_fetch_assoc($result)['id'];
		
		echo "<h1>Редагування блоку слів #$block_id користувача $username</h1>";
			
		$result = mysqli_query($mysql, "SELECT * FROM $TABLE_WORDS WHERE `u_id` = '$target_id' AND block_id = '$block_id'");
		$count = mysqli_num_rows($result);
		$index = 0;
		echo "<form method=post><span class='table_word_block'><h2>Блок слів #$block_id</h2><table>";
		while($row = mysqli_fetch_assoc($result)) {
			$ind = $row["id"];
			$word = $row["word"];
			$translate = $row["translate"];
			echo "<tr><td><input type='text' name='w_$index' value=\"$word\" /></td><td><input type='text' name='tr_$index' value=\"$translate\" /></td><input type='hidden' name='i_$index' value='$ind' /></tr>";
			$index++;
		}
		echo "<input type='hidden' name='username' value='$username' />";
		echo "<input type='hidden' name='block_id' value='$block_id' />";
		echo "<input type='hidden' name='count' value='$count' />";
		echo "<tr><td colspan=3 align=center><input type='submit' value='Редагувати' name='edit' class='mui-btn mui-btn--primary mui-btn--raised' /></td></tr>";
		echo "</table></span>";
	} else if (isset($_REQUEST["username"]) && isset($_REQUEST["block_id"]) && isset($_REQUEST["edit"])) {
		$username = $_REQUEST["username"];
		$count = $_REQUEST["count"];
		$block_id = $_REQUEST["block_id"];
		for ($i = 0; $i < $count; $i++) {
			$translate = $_REQUEST["tr_$i"];
			$word = $_REQUEST["w_$i"];
			$id = $_REQUEST["i_$i"];
			$word = str_replace("'", "''", $word);
			$translate = str_replace("'", "''", $translate);
			mysqli_query($mysql, "UPDATE $TABLE_WORDS SET translate = '$translate', word = '$word' WHERE id = '$id'") or die(mysqli_error($mysql));
		}
		changeLocation("words_editor.php?username=$username&block_id=$block_id");
	}
	echo "</div>";
?>
</div>