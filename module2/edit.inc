<!--validate-->
<script type="text/javascript" src="js/jquery.delegate.js"></script>
<script type="text/javascript" src="js/jquery.validate.js"></script>
<link rel="stylesheet" href="css/jquery.validate.css" type="text/css" />

<!--tips-->
<script type="text/javascript" src="js/jquery.jtip.js"></script>
<link rel="stylesheet" href="css/jquery.jtip.css" type="text/css" />

<script type="text/javascript" src="js/edit.js"></script>
<h3>
	<?
		if ($mode == 'add') echo 'Добавить';
		if ($mode == 'edit') echo 'Изменить';
	?>
</h3>
<form name="<?=MODULE;?>" method="post" action="<?=PAGE_URL;?>" enctype="multipart/form-data" class="mainform">					
	<input name="id" type="hidden" maxlength="255"  value="<?=htmlspecialchars($id, ENT_QUOTES);?>"/>
	<label><span class="alert">*</span> Заголовок:</label>
	<input name="title" type="text" maxlength="255"  value="<?=htmlspecialchars($title, ENT_QUOTES);?>"/>
	<a href="/ajax/tips/title.htm?width=400" class="jTip" id="designTip" name="Заголовок">?</a>
	
	<label>Текст:</label>
	<textarea name="text" rows="10" cols="17"><?=htmlspecialchars($text, ENT_QUOTES);?></textarea>
	
	<label>Цена:</label>
	<input name="price" type="text" maxlength="255" value="<?=htmlspecialchars($price, ENT_QUOTES);?>"/>
	
	<label>Изображение:</label>
	<input name="image" type="file" class="autowidth"/>
	<?
		if ($image != '')
		{
			echo '<div id="editImageBlock">';
			echo '<img id="" src="/data/' . MODULE . '/images/s_' . $image . '" alt="">';
			echo '<button type="button" onclick="delimage(' . $id . ',\'' . MODULE . '\')">удалить</button>';
			echo '</div>';
		}	
	?>
	
	<label>Файл:</label>
	<input name="file" type="file" class="autowidth"/>
	
	<label>Имя:</label>
	<input name="name" type="text" maxlength="255" value="<?=htmlspecialchars($name, ENT_QUOTES);?>"/>
	
	<label>Телефон:</label>
	<input name="phone" type="text" maxlength="255"  value="<?=htmlspecialchars($phone, ENT_QUOTES);?>"/>
		
	<label>ICQ:</label>
	<input name="icq" type="text" maxlength="11"  value="<?=htmlspecialchars($icq, ENT_QUOTES);?>"/>
	
	<label>Сайт:</label>
	<input name="url" type="text" maxlength="255"  value="<?=htmlspecialchars($url, ENT_QUOTES);?>"/>
	
	<label>E-mail:</label>
	<input name="email" type="text" maxlength="255"  value="<?=htmlspecialchars($email, ENT_QUOTES);?>"/>
	
	<label>Город:</label>
	<input name="city" type="text" maxlength="255"  value="<?=htmlspecialchars($city, ENT_QUOTES);?>"/>
	
	<label>Почтовый индекс:</label>
	<input name="zip" type="text" maxlength="255"  value="<?=htmlspecialchars($zip, ENT_QUOTES);?>"/>
	
	<label>Адрес:</label>
	<input name="address" type="text" maxlength="255"  value="<?=htmlspecialchars($address, ENT_QUOTES);?>"/>
	
	<label>Организация:</label>
	<input name="company" type="text" maxlength="255"  value="<?=htmlspecialchars($company, ENT_QUOTES);?>"/>
	
	<label>Род занятий:</label>
	<input name="occupation" type="text" maxlength="255"  value="<?=htmlspecialchars($occupation, ENT_QUOTES);?>"/>
	
	<label>Рубрика:</label>
	<select name="cat">
		<?
			foreach ($cats_info as $cats_params)
			{
				echo '<option value="' . $cats_params['id'] . '"';
				if ($cats_params['id'] == $cat)	echo ' selected="selected"';
				echo '>' . $cats_params['title'] . '</option>';
			}
		?>
	</select>
	
	<button type="submit" class="submit">
	<?
		if ($mode == 'add') echo 'Добавить';
		if ($mode == 'edit') echo 'Сохранить';
	?>
	</button>
	<div class="alert"><?=$alert;?></div>
</form>