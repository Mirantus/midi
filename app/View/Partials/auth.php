<?php
    /**
     * @var core\View $this
     */
?>
<div class="authArea">
    <? if ($this->auth->isAuth()) { ?>
        <?=lib\Url::createLink('/users/' . $this->auth->get('id'), $this->auth->get('name'), $this->app->page->alias == 'users_item')?> |
        <a href="/logout/">Выйти</a>
    <? } else { ?>
	    <a href="/login/">Войти</a>
    <? } ?>
</div>