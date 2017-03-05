<?php
    /**
     * @var core\View $this
     * @var array $roles
     * @var core\Form\Form $form
     */
 ?>
<h3><?=$this->title?></h3>

<form name="add" method="post" action="<?=$this->app->pageUrl;?>" class="form">
    <input type="hidden" name="key" value="">

    <label for="email" class="label"><span class="alert">*</span> <?=$form->email->title;?>:</label>
    <input id="email" name="email" type="text" maxlength="255" value="<?=$form->email->value;?>" required class="input">
    <div class="alert"><?=$form->email->error;?></div>

    <label for="password" class="label"><span class="alert">*</span> <?=$form->password->title;?>:</label>
    <input id="password" name="password" type="password" maxlength="255" required class="input">
    <div class="alert"><?=$form->password->error;?></div>

    <label for="name" class="label"><span class="alert">*</span> <?=$form->name->title;?>:</label>
    <input id="name" name="name" type="text" maxlength="255" value="<?=$form->name->value;?>" required class="input">
    <div class="alert"><?=$form->name->error;?></div>

    <label for="role" class="label"><?=$form->role->title;?>:</label>
    <select id="role" name="role">
        <? foreach ($roles as $role => $role_name) { ?>
            <option value="<?=$role?>" <? if($role == $form->role->value) echo 'selected="selected"'?>><?=$role_name?></option>
        <? } ?>
    </select>

    <button id="submit" type="submit" class="submit">Добавить</button>
    <div class="alert"><?=$form->error;?></div>
</form>