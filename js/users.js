$().ready(function(){
	   $("form[name='users']").validate({
			   rules : {
					   title : "required",
					   /*password2 : {equalTo: "input[name='password']"},*/
					   image: {accept: "jpg|gif|png"},
					   icq : {digits:true, rangelength: [5, 10]},
					   url : "url",
					   email : "email"
			   },
			   messages : {}
	  });
});