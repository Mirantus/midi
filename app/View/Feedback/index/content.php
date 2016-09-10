<?php
    /**
     * @var core\View $this
     * @var core\Form\Form $form
     */
 ?>
<h3><?=$this->title?></h3>

<form name="feedback" method="post" action="<?=$this->app->pageUrl;?>" enctype="multipart/form-data" class="form" data-request="ajax">
    <input type="hidden" name="key" value="">

    <label for="title" class="label"><span class="alert">*</span> <?=$form->title->title;?>:</label>
    <input id="title" name="title" type="text" maxlength="255" value="<?=$form->title->value;?>" required class="input">
    <div class="alert"><?=$form->title->error;?></div>

    <label for="text" class="label"><span class="alert">*</span> <?=$form->text->title;?>:</label>
    <textarea id="text" name="text" rows="10" cols="17" class="textarea js-wysiwyg"><?=$form->text->value;?></textarea>
    <div class="alert"><?=$form->text->error;?></div>

    <label for="price" class="label"><?=$form->price->title;?>:</label>
    <input id="price" name="price" type="text" maxlength="255" value="<?=$form->price->value;?>" class="input">
    <div class="alert"><?=$form->price->error;?></div>

    <label for="image" class="label"><?=$form->image->title;?>:</label>
    <input id="image" name="image" type="file">
    <div class="alert"><?=$form->image->error;?></div>

    <label for="file" class="label"><?=$form->file->title;?>:</label>
    <input id="file" name="file" type="file">
    <div class="alert"><?=$form->file->error;?></div>

    <label for="name" class="label"><?=$form->name->title;?>:</label>
    <input id="name" name="name" type="text" maxlength="255" value="<?=$form->name->value;?>" class="input">
    <div class="alert"><?=$form->name->error;?></div>

    <label for="phone" class="label"><?=$form->phone->title;?>:</label>
    <input id="phone" name="phone" type="tel" maxlength="255" value="<?=$form->phone->value;?>" class="input">
    <div class="alert"><?=$form->phone->error;?></div>

    <label for="url" class="label"><?=$form->url->title;?>:</label>
    <input id="url" name="url" type="url" maxlength="255" value="<?=$form->url->value;?>" class="input">
    <div class="alert"><?=$form->url->error;?></div>

    <label for="email" class="label"><span class="alert">*</span> <?=$form->email->title;?>:</label>
    <input id="email" name="email" type="text" maxlength="255" value="<?=$form->email->value;?>" required class="input">
    <div class="alert"><?=$form->email->error;?></div>

    <label for="address" class="label"><?=$form->address->title;?>:</label>
    <input id="address" name="address" type="text" maxlength="255" value="<?=$form->address->value;?>" class="input">
    <div class="alert"><?=$form->address->error;?></div>

    <button id="submit" type="submit" class="submit">Отправить</button>
    <div class="alert"><?=$form->error;?></div>
</form>