<?php
    /**
     * @var core\View $this
     * @var core\Form\Form $form
     * @var string $id
     */
 ?>
<h3><?=$this->title?></h3>

<form name="edit" method="post" action="<?=$this->app->pageUrl?>" enctype="multipart/form-data" class="form">
    <input type="hidden" name="key" value="">

    <label for="title" class="label"><span class="alert">*</span> <?=$form->title->title?>:</label>
    <input id="title" name="title" type="text" maxlength="255" value="<?=$form->title->value?>" required class="input">
    <div class="alert"><?=$form->title->error?></div>

    <button id="submit" type="submit" class="submit">Сохранить</button>
    <div class="alert"><?=$form->error?></div>
</form>