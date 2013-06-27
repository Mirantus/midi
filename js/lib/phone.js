alert(123);
function phone()
{
	phone_form = document.forms["phone-form"];
	if ( phone_check(phone_form) ) phone_form.submit();
}

function phone_check(form)
{				  
    if ( !is_right_length(phone_form.phone.value,1,255) ) 
	{
		alert(errors['1-255']);
		return false;
	}
	return true;
}