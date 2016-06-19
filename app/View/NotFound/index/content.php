<?php
    /**
     * @var app\Controller\NotFoundController $this
     */
?>
<h1>Страница не найдена</h1>
<p>Запрашиваемая страница <a href="<?=$this->app->pageUrl;?>"><?=$this->app->pageUrl;?></a> не найдена.</p>
<p>Воспользуйтесь меню, чтобы перейти в нужный раздел или вернитесь <a href="javascript:back()">назад</a>.</p>
<p></p>