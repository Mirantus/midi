<?php
    /**
     * @var core\View $this
     * @var core\Form\Form $form
     */
 ?>
<h3><?=$this->title?></h3>

<form name="add" method="post" action="<?=$this->app->pageUrl;?>" class="form">
    <input type="hidden" name="key" value="">

    <label for="password" class="label"><span class="alert">*</span> Введите новый пароль:</label>
    <input id="password" name="password" type="password" maxlength="255" required class="input">
    <div class="alert"><?=$form->password->error;?></div>

    <button id="submit" type="submit" class="submit">Изменить пароль</button>
    <div class="alert"><?=$form->error;?></div>
</form>