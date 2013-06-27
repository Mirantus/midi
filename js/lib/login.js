function auth()
{
	var loader_place = document.getElementById('loginArea');
	var alert_place = document.getElementById('login-alert');
	var user_place = document.getElementById('user');
	var form = document.forms['login-form'];
	var login = form.login.value;
	var password = form.password.value;
	
	alert_place.innerHTML = '';
	loader_show(loader_place);
	
	var url='/ajax/login.php?login=' + login + '&password=' + password;
	request.open("GET", url, true);
	request.setRequestHeader("X-Requested-With","XMLHttpRequest");
	request.onreadystatechange =  function ()
	{
		if (request.readyState == 4 && request.status == 200)
		{
			if (request.responseText > 0)
			{				
				user_place.innerHTML = '<a href="/users/edit.php?id=' + request.responseText + '">' + login + ' | <a href="javascript:logout();">Выйти</a>';
				remove_node(form);
			}
			else
			{
				alert_place.innerHTML = 'Не верный логин или пароль';
			}
			loader_hide();
		}
	}
	request.send(null);
	
	return false;
}
function logout()
{
	var loader_place = document.getElementById('loginArea');
	loader_show(loader_place);
	
	var url='/ajax/logout.php';
	request.open("GET", url, true);
	request.setRequestHeader("X-Requested-With","XMLHttpRequest");
	request.onreadystatechange =  function ()
	{
		if (request.readyState == 4 && request.status == 200)
			if (request.responseText)
			{
				window.location = '/';
			}
	}
	request.send(null);
}