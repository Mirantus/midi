function obj_info(obj, objName)
{
    var result = "";
    for (var i in obj) // обращение к свойствам объекта по индексу
        result += objName + "." + i + " = " + obj[i] + "<br />\n";
    document.write(result);
}

function position(obj) 
{
	var x = y = 0;
    while(obj) 
	{
		x += obj.offsetLeft;
        y += obj.offsetTop;
		obj = obj.offsetParent;
      }
      return {x:x, y:y};
}

function clean(value,text)
{
	if (value==text) value="";
	return value;
}

function add2favor()
{
	if (navigator.appName == "Microsoft Internet Explorer" && parseFloat(navigator.appVersion) >= 4.0)
		window.external.AddFavorite(location.href, document.title);
	else
		window.alert("Ваш браузер не поддерживает данную функцию.");
}