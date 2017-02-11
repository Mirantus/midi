<?php
    /**
     * @var core\View $this
     * @var array $cats
     * @var string $flash
     */
 ?>
<h3><?=$this->title?></h3>

<? if ($flash) { ?>
<div class="flash"><?=$flash?></div>
<? } ?>

<? if (!empty($items)) { ?>
    <div class="sorted items" data-module="module">
        <ul class="ns">
            <? foreach ($items as $item) { ?>
                <li id="item<?=$item['id']?>" data-id="<?=$item['id']?>" class="item">
                    <? if ($this->isOwner) { ?>
                        <a href="/module/edit/<?=$item['id']?>/?return=<?=$_SERVER['REQUEST_URI']?>" class="comment"><i
                                    class="icon-pencil"></i></a>
                        <a href="/module/del/<?=$item['id']?>/" class="comment" data-request="ajax"
                           data-confirm="item-del"><i class="icon-remove"></i></a>
                    <? } ?>
                    <?
                        $url = '/module/item/' . $item['id'] . '/';
                        echo lib\Url::createLink($url, $item['title']);
                    ?>
                </li>
            <? } ?>
        </ul>
    </div>
<? } else { ?>
    <p>Данных нет</p>
<? } ?>

<?php include($this->partialPath . '/pagination.php');?>

<? if ($this->isOwner) { ?>
    <p><a href="/module/add/">Добавить</a></p>
<? } ?>
