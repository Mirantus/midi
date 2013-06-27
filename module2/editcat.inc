<!--validate-->
<script type="text/javascript" src="js/jquery.delegate.js"></script>
<script type="text/javascript" src="js/jquery.validate.js"></script>
<link rel="stylesheet" href="css/jquery.validate.css" type="text/css" />

<script type="text/javascript" src="js/edit.js"></script>
<h3>
	<?
		if ($mode == 'add') echo 'Добавить рубрику';
		if ($mode == 'edit') echo 'Изменить рубрику';
	?>
</h3>
<form name="<?=MODULE;?>" method="post" action="<?=PAGE_URL;?>" class="mainform">					
	<input name="cat" type="hidden" maxlength="255"  value="<?=htmlspecialchars($cat, ENT_QUOTES);?>"/>
	<label>Заголовок:</label>
	<input name="title" type="text" maxlength="255"  value="<?=htmlspecialchars($title, ENT_QUOTES);?>"/>
	
	<label>Текст:</label>
	<textarea name="text" rows="10" cols="17"><?=htmlspecialchars($text, ENT_QUOTES);?></textarea>

	<button type="submit" class="submit">
	<?
		if ($mode == 'add') echo 'Добавить';
		if ($mode == 'edit') echo 'Сохранить';
	?>
	</button>
	<div class="alert"><?=$alert;?></div>
</form>