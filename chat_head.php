<div style='padding-top: 20px;'></div>
<a href='../index.php'><div id='info'>Головна</div></a>
<div class='chat'>
	<div class='chat-messages'>
		<div class='chat-messages__content' id='messages'>
			Завантаження...
		</div>
	</div>
	<div class='chat-input'>
		<form method='post' onsubmit='return sendMessage(this)' id='chat-form'>
			<input type='text' id='message-text' class='chat-form__input' name='text' placeholder='Введіть повідомлення'>
			<input type='submit' name='submit' class='chat-form__submit' value='>'>
			<input type='hidden' name='request_id' value='-1'>
		</form>
	</div>
</div>
<script>
	function sendMessage(thiss) {
		$.ajax({
			method: "POST",
			url: "<?php echo $_SESSION["SERVER_NAME"]; ?>/chat_api.php",
			data: "send=1&message=" + thiss.text.value + "&request_id=" + thiss.request_id.value
		});
		thiss.text.value = "";
		return false;
	}
	(function worker() {
		var thiss = document.getElementById("chat-form");
		if (thiss.request_id.value != '') {
			$.ajax({
				method: "POST",
				url: "<?php echo $_SESSION["SERVER_NAME"]; ?>/chat_api.php",
				data: "load=1&request_id=" + thiss.request_id.value,
				success: function(data) {
					var dataNow = document.getElementById("messages").innerHTML;
					console.log(dataNow);
					console.log(data);
					if (dataNow.trim() !== data.trim()) {
						document.getElementById("messages").innerHTML = data;
					}
				},
				complete: function() {
				  setTimeout(worker, 1000);
				}
			});
		}
	})();
</script>