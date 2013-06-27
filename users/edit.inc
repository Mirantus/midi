<script type="text/javascript" src="js/jquery.delegate.js"></script>
<script type="text/javascript" src="js/jquery.validate.js"></script>
<script type="text/javascript" src="js/edit.js"></script>
<link rel="stylesheet" href="css/jquery.validate.css" type="text/css" />
<h3>
	<?
		if ($mode == 'add') echo 'Регистрация';
		if ($mode == 'edit') echo 'Изменение данных';
	?>
</h3>
<form name="<?=MODULE;?>" method="post" action="<?=PAGE_URL;?>" enctype="multipart/form-data" class="mainform">					
	<input name="id" type="hidden" maxlength="255"  value="<?=htmlspecialchars($id, ENT_QUOTES);?>"/>
	<label><span class="alert">*</span> Логин:</label>
	<input name="title" type="text" maxlength="255"  value="<?=htmlspecialchars($title, ENT_QUOTES);?>"/>
	
	<label>Пароль:</label>
	<input name="password" type="password" maxlength="255"  value=""/>
	
	<label>Подтверждение пароля:</label>
	<input name="password2" type="password" maxlength="255"  value=""/>
	
	<label>О себе:</label>
	<textarea name="text" rows="10" cols="17"><?=htmlspecialchars($text, ENT_QUOTES);?></textarea>
	
	<label>Фотография:</label>
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
	
	<label>Имя:</label>
	<input name="name" type="text" maxlength="255" value="<?=htmlspecialchars($name, ENT_QUOTES);?>"/>
	
	<label>Пол:</label>
	<select name="gender">
		<option value="1" <? if ($gender == '1') echo 'selected="selected"'; ?> >мужской</option>
		<option value="0" <? if ($gender == '0') echo 'selected="selected"'; ?> >женский</option>
	</select>
	
	<label>Дата рождения:</label>
	<input name="birthday" type="text" maxlength="10" value="<?=htmlspecialchars(convert_sql_to_date($birthday), ENT_QUOTES);?>"/>
	<div class="comment">Пример: 01.01.1900</div>

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

	<button type="submit" class="submit">
	<?
		if ($mode == 'add') echo 'Добавить';
		if ($mode == 'edit') echo 'Сохранить';
	?>
	</button>
	<div class="alert"><?=$alert;?></div>
</form>