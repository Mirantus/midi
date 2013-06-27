<script language="JavaScript">
<!--
//скрипт динамического меню без использования свойств position, top, left и т.д...

show = new Array(0,0,0);//Массив состояний пунктов меню: 0 - закрытый, 1- раскрытый. Первый элемент не используется.

function submenu(menuid,show)//функция показа/скрытия вложенных меню. Параметры: id пункта меню и состояние показа.
{
if (show=="1") // если меню нужно показать, то
{
document.all[menuid].style.visibility='visible';   //делаем его видимым его собственной ширины и высоты
document.all[menuid].style.width='';
document.all[menuid].style.height='';
}
if (show=="0") // если меню нужно скрыть, то
{
document.all[menuid].style.visibility='hidden'; // делаем его невидимым
document.all[menuid].style.width='0';
document.all[menuid].style.height='1';
}
}
<!---->
</script>

<!--если подменю не показаны, то показываем и изменяем состояние в "показано" иначе - наоборот -->
<div onclick="if (show[1]=='0') {submenu('blok1','1'); show[1]='1';} else {submenu('blok1','0'); show[1]='0';}" style="cursor: hand;">Пункт меню 1</div>
<!-- по умолчанию подменю невидимо -->
<div id="blok1" style="width: 0; height: 1; overflow: hidden; visibility : hidden; padding-left: 10;">
 <a href="/">Ссылка 1</a><br>
 <a href="/">Ссылка 2</a>
</div>

<div onclick="if (show[2]=='0') {submenu('blok2','1'); show[2]='1';} else {submenu('blok2','0'); show[2]='0';}" style="cursor: hand;">Пункт меню 2</div>
<div id="blok2" style="width: 0; height: 1; overflow: hidden; visibility : hidden; padding-left: 10;">
 <a href="/">Ссылка 1</a><br>
 <a href="/">Ссылка 2</a>
</div>