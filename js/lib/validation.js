errors = new Array();
errors = {'system_error':'Произошла внутренняя ошибка','wrong':'Неверное значение поля','empty':'Поле должно быть заполнено','damn':'Поля не должны содержать нецензурных выражений','exist':'Поле с таким именем уже существует','upload_error':'Ошибка закачки файла','email':'Введен несуществующий адрес e-mail','login_error':'Не верный логин или пароль','password_confirm':'Пароль и подтверждение должны совпадать','num':'Поле должно состоять только из цифр','icq':'Введен несуществующий адрес icq','url':'Введен несуществующий адрес сайта','ip':'Введен несуществующий ip','cat':'Выберите категорию','login':'Логин должен состоять из символов A-Za-z0-9_','is_user':'Пользователь с таким логином уже существует, придумайте другой логин'};

function is_system(string)
{
	   if (/\W/.test(string)) return(false);
	   return(true);
}

function is_digital(string)
{
	   if (/\d+/.test(string)) return(true);
	   return(false);
}

function is_right_length(string,min,max)
{
	   if ( string.length>max ) return(false);
	   if ( string.length<min ) return(false);
	   return(true);
}

function is_empty(string)
{
	   if ( string.length > 0 ) return(false);
	   return(true);
}

function is_email(string)
{
	if (/\w+@\w+\.\w{1,4}/.test(string)) return(true);
	return(false);
}

function is_url(string)
{
	if (/\w+\.\w{1,4}/.test(string)) return(true);
	return(false);
}

function is_icq(string)
{
   if (/\d{5,9}/.test(string)) return(true);
   return(false);
}

function is_ip(string)
{
	   if (/\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}/.test(string)) return(true);
	   return(false);
}

function is_image(string)
{
	string = string.toLowerCase();	
	if (string.indexOf('.jpg') != -1) return(true);
	if (string.indexOf('.jpeg') != -1) return(true);
	if (string.indexOf('.gif') != -1) return(true);
	if (string.indexOf('.png') != -1) return(true);
	return(false);
}

//other
function set_error(error)
{
	alert(error);
	return(false);
}