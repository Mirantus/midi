<?php
/**
 * @var core\View $this
 * @var core\Form\Form $form
 */
?>
<h1><?=$this->title?></h1>

<form name="login" method="post" action="<?=$this->app->pageUrl?>" class="form">
    <input type="hidden" name="key" value="">

    <label for="email" class="label"><span class="alert">*</span> <?=$form->email->title?>:</label>
    <input id="email" name="email" type="email" maxlength="255" value="<?=h($form->email->value)?>" required class="input">
    <div class="alert"><?=$form->email->error;?></div>

    <label for="password" class="label"><span class="alert">*</span> <?=$form->password->title?>:</label>
    <input id="password" name="password" type="password" maxlength="255" value="<?=h($form->password->value)?>" required class="input">
    <div class="alert"><?=$form->password->error;?></div>

    <button id="submit" type="submit" class="submit">Войти</button>
    <div class="alert"><?=$form->error;?></div>
</form>