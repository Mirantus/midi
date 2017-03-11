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
    <div class="items" data-module="<?=$this->moduleAlias?>">
        <ul class="ns">
            <? foreach ($items as $item) { ?>
                <li id="item<?=$item['id']?>" data-id="<?=$item['id']?>" class="item">
                    <? if ($this->isOwner) { ?>
                        <a href="/<?=$this->moduleAlias?>/edit/<?=$item['id']?>/?return=<?=$_SERVER['REQUEST_URI']?>" class="comment"><i
                                    class="icon-pencil"></i></a>
                        <a href="/<?=$this->moduleAlias?>/del/<?=$item['id']?>/" class="comment" data-request="ajax"
                           data-confirm="item-del"><i class="icon-remove"></i></a>
                    <? } ?>
                    <?=$item['name']?>
                </li>
            <? } ?>
        </ul>
    </div>
<? } else { ?>
    <p>Данных нет</p>
<? } ?>

<?php include($this->partialPath . '/pagination.php');?>

<? if ($this->isOwner) { ?>
    <p><a href="/<?=$this->moduleAlias?>/add/">Добавить</a></p>
<? } ?>
