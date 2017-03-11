<?php
    /**
     * @var core\View $this
     * @var core\Form\Form $form
     * @var boolean $success
     */
 ?>
<h3><?=$this->title?></h3>

<? if ($success) { ?>
    <p>Письмо с инструкцией по восстановлению пароля отправлено на указанный адрес.</p>
<? } else { ?>
    <form name="add" method="post" action="<?=$this->app->pageUrl;?>" class="form">
        <input type="hidden" name="key" value="">

        <label for="email" class="label"><span class="alert">*</span> <?=$form->email->title;?>:</label>
        <input id="email" name="email" type="text" maxlength="255" value="<?=$form->email->value;?>" required class="input">
        <div class="alert"><?=$form->email->error;?></div>

        <button id="submit" type="submit" class="submit">Отправить</button>
        <div class="alert"><?=$form->error;?></div>
    </form>
<? } ?>