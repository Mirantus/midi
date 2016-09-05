<?php
    /**
     * @var core\View $this
     * @var array $cats
     * @var core\Form\Form $form
     * @var string $title
     */
 ?>
<h3><?=$this->title?></h3>

<form name="add" method="post" action="<?=$this->app->pageUrl;?>" enctype="multipart/form-data" class="form" data-request="ajax">
    <input type="hidden" name="key" value="">

    <label for="text" class="label"><span class="alert">*</span> <?=$form->text->title;?>:</label>
    <textarea id="text" name="text" rows="10" cols="17" class="textarea" required><?=$form->text->value;?></textarea>
    <div class="alert"><?=$form->text->error;?></div>

    <label for="name" class="label"><span class="alert">*</span> <?=$form->name->title;?>:</label>
    <input id="name" name="name" type="text" maxlength="255" value="<?=$form->name->value;?>" required class="input">
    <div class="alert"><?=$form->name->error;?></div>

    <label for="email" class="label"><?=$form->email->title;?>:</label>
    <input id="email" name="email" type="text" maxlength="255" value="<?=$form->email->value;?>" class="input">
    <div class="alert"><?=$form->email->error;?></div>

    <button type="submit" class="submit">Отправить</button>
    <div class="alert"><?=$form->error;?></div>
</form>