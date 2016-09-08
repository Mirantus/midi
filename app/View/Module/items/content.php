<?php
    /**
     * @var core\View $this
     * @var array $cats
     * @var string $title
     */
 ?>
<h3><?=$this->title?></h3>
<ul class="ns">
    <? if (!empty($items)) { ?>
        <? foreach ($items as $item) { ?>
            <li id="item<?=$item['id']?>">
                <? if ($_SESSION['auth']) { ?>
                    <a href="/module/edit/<?=$item['id']?>/" class="comment"><i class="icon-edit"></i></a>
                    <a href="/module/del/<?=$item['id']?>/" class="comment" data-request="ajax" data-confirm="item-del"><i class="icon-remove"></i></a>
                <? } ?>
                &nbsp;
                <?
                    $url = '/module/item/' . $item['id'] . '/';
                    echo lib\Url::createLink($url, $item['title']);
                ?>
            </li>
        <? } ?>
    <? } else { ?>
        <li>Данных нет</li>
    <? } ?>
</ul>
<?php include($this->partialPath . '/pagination.php');?>

<? if ($_SESSION['auth']) { ?>
    <p><a href="/module/add/">Добавить</a></p>
<? } ?>
