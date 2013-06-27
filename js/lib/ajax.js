//init
var request = false;
try 
{
	request = new XMLHttpRequest();
} 
catch (trymicrosoft) 
{
	try 
	{
		request = new ActiveXObject("Msxml2.XMLHTTP");
	} 
	catch (othermicrosoft) 
	{
		try 
		{
			request = new ActiveXObject("Microsoft.XMLHTTP");
		} 
		catch (failed) 
		{
			request = false;
		}  
	}
}
//ajax functions
function remove_node(obj)
{
	obj.parentNode.removeChild(obj);
	return true;
}

//loader
var loader = document.createElement('img');
loader.src = '/images/ajax-loader.gif';
loader.alt = 'Пожалуйста подождите';
loader.id = 'loader';

function loader_show(obj)
{
	obj.appendChild(loader);
}
function loader_hide()
{
	var loader = document.getElementById('loader');
	remove_node(loader);
}

//delete
function delimage(id, module)
{
	if ( !confirm('Вы действительно хотите удалить изображение?') ) return false;
	var item = document.getElementById('editImageBlock');	
	loader_show(item);	
	var url='/ajax/delimage.php?id=' + id + '&module=' + module;
	request.open("GET", url, true);
	request.setRequestHeader("X-Requested-With","XMLHttpRequest");
	request.onreadystatechange =  function ()
	{
		if (request.readyState == 4 && request.status == 200)		
			if (request.responseText)
			{
				remove_node(item);
			}
	}
	request.send(null);
	return true;
}
function delitem(id, module)
{
	if ( !confirm('Вы действительно хотите удалить?') ) return;
	var item = document.getElementById('item' + id);	
	loader_show(item);
	var url='/ajax/delitem.php?id=' + id + '&module=' + module;
	request.open("GET", url, true);
	request.setRequestHeader("X-Requested-With","XMLHttpRequest");
	request.onreadystatechange =  function ()
	{
		if (request.readyState == 4 && request.status == 200)		
			if (request.responseText)
			{
				remove_node(item);
			}
	}
	request.send(null);
}