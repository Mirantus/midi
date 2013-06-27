$().ready(function(){
	   $("form[name='module']").validate({
			   rules : {
					   title : "required",
					   text : "required",
					   price : {required:true, number:true},
					   image: {accept: "jpg|gif|png"},
					   name : "required",
					   phone : "required",
					   icq : {required:true, digits:true, rangelength: [5, 10]},
					   url : {required:true, url:true},
					   email : {required:true, email:true},
					   city : "required",
					   zip : "required",
					   address : "required",
					   company : "required",
					   occupation : "required"
			   },
			   messages : {}
	  });
});