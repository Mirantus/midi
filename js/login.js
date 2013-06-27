 $(document).ready(function(){
	
	$("#loginForm").submit(function() {
	
		$("#loginAlert").html('');
		$("#loginForm button").after(loader);
		
		 $.get('/ajax/login.php', { login: $("#login").val(), password: $("#password").val() },
		   function(data){
				if (data > 0)
				{
					window.location = '/';
				}
				else
				{
					$(loader).remove();
					$("#loginAlert").html('Не верный логин или пароль');
				}
		   });
	    return false;
	});
	
	$("#logout").click(function() {
		$(loader).show();
		$.get('/ajax/logout.php', '', function(data){ window.location = '/'; });
		$(loader).remove();
	    return false;
	});
});