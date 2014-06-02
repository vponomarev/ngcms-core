$(document).ready(function(){
  $("#reg_login").change(function() {
  
  	if ($('#reg_login').val() == '') {
	$("#reg_login").css({
      "display": "table-cell",
      "background": "#f9f9f9",
	  "border": "1px solid #e2e2e2",
	  "box-shadow": "inset 2px 3px 3px -2px #e2e2e2"
    });
	$("div#reg_login").html("<span>используется при авторизации</span>");
	return;
	}
  
	$.post('/engine/rpc.php', { json : 1, methodName : 'core.registration.checkParams', rndval: new Date().getTime(), params : json_encode({ 'login' : $('#reg_login').val() }) }, function(data) {
		// Try to decode incoming data
		try {
			resTX = eval('('+data+')');
		} catch (err) { alert('Error parsing JSON output. Result: '+linkTX.response); }
		if (!resTX['status']) {
			alert('Error ['+resTX['errorCode']+']: '+resTX['errorText']);
		} else {
			if ((resTX['data']['login']>0)&&(resTX['data']['login'] < 100)) {
				$("#reg_login").css("border-color", "#b54d4b");
				$("div#reg_login").html("<span style='color:#b54d4b;'>Введенный логин уже существует или содержит неразрешенные символы!</span>");
			} else {
				$("#reg_login").css("border-color", "#94c37a");
				$("div#reg_login").html("<span style='color:#94c37a;'>Введенный вами логин свободен и может быть использован при регистрации.</span>");
			}
		}
	}, "text").error(function() { 
		alert('HTTP error during request', 'ERROR'); 
	});

  });
  
    $("#reg_email").change(function() {
	
	if ($('#reg_email').val() == '') {
	$("#reg_email").css({
      "display": "table-cell",
      "background": "#f9f9f9",
	  "border": "1px solid #e2e2e2",
	  "box-shadow": "inset 2px 3px 3px -2px #e2e2e2"
    });
	$("div#reg_email").html("<span>при восстановлении пароля новый пароль будет высылаться на этот адрес</span>");
	return;
	}
	
	$.post('/engine/rpc.php', { json : 1, methodName : 'core.registration.checkParams', rndval: new Date().getTime(), params : json_encode({ 'email' : $('#reg_email').val() }) }, function(data) {
		// Try to decode incoming data
		try {
			resTX = eval('('+data+')');
		} catch (err) { alert('Error parsing JSON output. Result: '+linkTX.response); }
		if (!resTX['status']) {
			alert('Error ['+resTX['errorCode']+']: '+resTX['errorText']);
		} else {
			if ((resTX['data']['email']>0)&&(resTX['data']['email'] < 100)) {
				$("#reg_email").css("border-color", "#b54d4b");
				$("div#reg_email").html("<span style='color:#b54d4b;'>Введенный вами email уже используется на сайте или имеет неправильный формат!</span>");
			} else {
				$("#reg_email").css("border-color", "#94c37a");
				$("div#reg_email").html("<span style='color:#94c37a;'>Введенный вами email может быть использован при регистрации.</span>");
			}
		}
	}).error(function() { 
		alert('HTTP error during request', 'ERROR'); 
	});

  });
  
  
    $("#reg_password2").change(function() {
	
	if ($('#reg_password2').val() == '' && $('#reg_password').val() == '') {
	$("#reg_password").css({
      "display": "table-cell",
      "background": "#f9f9f9",
	  "border": "1px solid #e2e2e2",
	  "box-shadow": "inset 2px 3px 3px -2px #e2e2e2"
    });
	$("#reg_password2").css({
      "display": "table-cell",
      "background": "#f9f9f9",
	  "border": "1px solid #e2e2e2",
	  "box-shadow": "inset 2px 3px 3px -2px #e2e2e2"
    });
	$("div#reg_password2").html("<span>должен совпадать с паролем. используется для проверки правильности ввода</span>");
	return;
	}

			if ($('#reg_password2').val() != $('#reg_password').val()) {
				$("#reg_password").css("border-color", "#b54d4b");
				$("#reg_password2").css("border-color", "#b54d4b");
				$("div#reg_password2").html("<span style='color:#b54d4b;'>Введенный вами пароль не совпадает с введным ранее!</span>");
			} else {
				$("#reg_password").css("border-color", "#94c37a");
				$("#reg_password2").css("border-color", "#94c37a");
				$("div#reg_password2").html("<span style='color:#94c37a;'>Пароли совпадают.</span>");
			}


  });
  
  
      $("#reg_password").change(function() {
	  
		if ($('#reg_password2').val() == '' && $('#reg_password').val() == '') {
		$("#reg_password").css({
		  "display": "table-cell",
		  "background": "#f9f9f9",
		  "border": "1px solid #e2e2e2",
		  "box-shadow": "inset 2px 3px 3px -2px #e2e2e2"
		});
		$("#reg_password2").css({
		  "display": "table-cell",
		  "background": "#f9f9f9",
		  "border": "1px solid #e2e2e2",
		  "box-shadow": "inset 2px 3px 3px -2px #e2e2e2"
		});
		$("div#reg_password2").html("<span>должен совпадать с паролем. используется для проверки правильности ввода</span>");
		return;
		}
			if ($('#reg_password2').val() != $('#reg_password').val()) {
				$("#reg_password").css("border-color", "#b54d4b");
				$("#reg_password2").css("border-color", "#b54d4b");
				$("div#reg_password2").html("<span style='color:#b54d4b;'>Введенный вами пароль не совпадает с введным ранее!</span>");
			} else {
				$("#reg_password").css("border-color", "#94c37a");
				$("#reg_password2").css("border-color", "#94c37a");
				$("div#reg_password2").html("<span style='color:#94c37a;'>Пароли совпадают.</span>");
			}


  });
  
});