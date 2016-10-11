<?php
    /**
     * @var core\View $this
     * @var array $cats
     */
 ?>
<h3><?=$this->title?></h3>
<ul class="ns">
    <? if (!empty($items)) { ?>
        <? foreach ($items as $item) { ?>
            <li id="item<?=$item['id']?>">
                <? if ($this->isOwner) { ?>
                    <a href="/module/edit/<?=$item['id']?>/" class="comment"><i class="icon-pencil"></i></a>
                    <a href="/module/del/<?=$item['id']?>/" class="comment" data-request="ajax" data-confirm="item-del"><i class="icon-remove"></i></a>
                <? } ?>

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

<? if ($this->isOwner) { ?>
    <p><a href="/module/add/">Добавить</a></p>
<? } ?>
