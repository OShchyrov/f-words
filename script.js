document.getElementById('submit_num').onclick = function() {
	var num_of_words = document.getElementById("num_of_words").value;
	if(num_of_words > 0 && num_of_words < 51) {
		var main = document.getElementById('main');
		var num_of_wordss = document.getElementById('num_of_words');
		var submit_num = document.getElementById('submit_num');
		main.removeChild(num_of_wordss);
		main.removeChild(submit_num);
		
		main.style.overflow = 'hidden';
		
		document.getElementsByTagName('h2')[0].innerHTML = 'Іноземна мова.';
		document.getElementsByTagName('h2')[0].style.paddingTop = '50px';
		document.getElementsByTagName('h2')[0].style.display = 'inline-block';
		
		var form1 = document.createElement("form");
		form1.method = 'post';
		form1.action = '../mysql/writedb.php';
		form1.id = 'form1';
		form1.setAttribute("onSubmit", 'return submitForm(this)');
		document.getElementById('main').appendChild(form1);
		
		var unknows = document.createElement('div');
		unknows.id = 'unknows';
		unknows.innerHTML = '<h3><font color=red>Невідомі слова.</font></h2>';
		document.getElementById('form1').appendChild(unknows);
		
		for(var i = 1; i <= num_of_words; i++) {
			var input = document.createElement("input");
			input.type = 'text';
			input.style.marginTop = '5px';
			input.name = i;
			input.autocomplete = 'off';
			document.getElementById('unknows').appendChild(input);
			document.getElementById('unknows').appendChild(document.createElement('br'));
		}
		
		var knows = document.createElement('div');
		knows.id = 'knows';
		knows.innerHTML = '<h3><font color=green>Переклад слів.</font></h2>';
		document.getElementById('form1').appendChild(knows);
		
		for(var i = 1; i <= num_of_words; i++) {
			var input = document.createElement("input");
			input.type = 'text';
			input.name = -i;
			input.autocomplete = 'off';
			input.style.marginTop = '5px';
			document.getElementById('knows').appendChild(input);
			document.getElementById('knows').appendChild(document.createElement('br'));
		}
		var submit_words = document.createElement('input');
		submit_words.type = 'submit';
		submit_words.value = 'Додати слова';
		form1.appendChild(submit_words);
	}
	else alert("Введіть кількість слів від 1 до 50");
}
function submitForm(form) {
	return confirm("Ви дійсно бажаєте завершити додавання?");
}