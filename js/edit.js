//delete
function delimage(id, module)
{
	if ( !confirm('Вы действительно хотите удалить изображение?') ) return false;
	var item = $("#editImageBlock");
	$(item).append(loader);
	$.get('/ajax/delimage.php', { id: id, module: module },function(data){$(item).remove(); $(loader).remove();});	
	
	return true;
}
function delitem(id, module)
{
	if ( !confirm('Вы действительно хотите удалить?') ) return;	
	var item = $("#item"+id);
	$("#item"+id).append(loader);
	$.get('/ajax/delitem.php', { id: id, module: module },function(data){$(item).remove(); $(loader).remove();});	
}
function delcat(cat, module)
{
	if ( !confirm('Вы действительно хотите удалить рубрику и все ее содержимое?') ) return;	
	var obj = $("#cat"+cat);
	$("#cat"+cat).append(loader);
	$.get('/ajax/delcat.php', { cat: cat, module: module },function(data){$(obj).remove(); $(loader).remove();});	
}