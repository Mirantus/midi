<?php
/**
 * @var app\Controller\ContactsController $this
 * @var core\Form\Form $form
 * @var string $title
 */
?>
<h1><?=$title?></h1>

<form name="login" method="post" action="<?=$this->app->pageUrl?>" class="form" data-request="ajax">
    <input type="hidden" name="key" value="">

    <label for="login" class="label"><span class="alert">*</span> <?=$form->login->title?>:</label>
    <input id="login" name="login" type="text" maxlength="255" value="<?=h($form->login->value)?>" required class="input">
    <div class="alert"><?=$form->login->error;?></div>

    <label for="password" class="label"><span class="alert">*</span> <?=$form->password->title?>:</label>
    <input id="password" name="password" type="password" maxlength="255" value="<?=h($form->password->value)?>" required class="input">
    <div class="alert"><?=$form->password->error;?></div>

    <button type="submit" class="submit">Войти</button>
    <div class="alert"><?=$form->error;?></div>
</form>