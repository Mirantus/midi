function check(form)
{				  
	if ( form.password.value != form.password2.value ) 
	{
		alert('Пароль и подтверждение должны совпадать');
		return false;
	}
	return true;
}