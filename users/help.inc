<h3>Восстановление пароля</h3>
<form name="" action="" method="post" class="mainform">
	<label>E-mail:</label>
	<input name="email" type="text" maxlength="255"  value="<?=htmlspecialchars($email, ENT_QUOTES);?>"/>
	<span class="comment">Данные для доступа будут отправлены на указанный email.</span>
	<button type="submit" class="submit">Отправить</button>
	<div class="alert"><?=$alert;?></div>
</form>