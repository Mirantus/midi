<?php
    /**
     * @var core\View $this
     */
?>
<div class="authArea">
    <? if ($this->auth->isAuth()) { ?>
        <?=$this->auth->get('name')?> |
        <a href="/logout/">Выйти</a>
    <? } else { ?>
	    <a href="/login/">Войти</a>
    <? } ?>
</div>