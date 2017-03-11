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
    <div class="sorted items" data-module="<?=$this->moduleAlias?>" data-level="cat">
        <ul class="ns">
            <? foreach ($items as $item) { ?>
                <li id="item<?=$item['id']?>" data-id="<?=$item['id']?>" class="item">
                    <? if ($this->isOwner) { ?>
                        <a href="/<?=$this->moduleAlias?>/editcat/<?=$item['id']?>/?return=<?=$_SERVER['REQUEST_URI']?>" class="comment">
                            <i class="icon-pencil"></i>
                        </a>
                        <a href="/<?=$this->moduleAlias?>/delcat/<?=$item['id']?>/" class="comment" data-request="ajax" data-confirm="item-del">
                            <i class="icon-remove"></i>
                        </a>
                    <? } ?>
                    <?
                        $url = '/' . $this->moduleAlias . '/cat/' . $item['id'] . '/';
                        echo lib\Url::createLink($url, $item['title']);
                    ?>
                </li>
            <? } ?>
        </ul>
    </div>
<? } else { ?>
    <p>Рубрик нет</p>
<? } ?>

<?php include($this->partialPath . '/pagination.php');?>

<? if ($this->isOwner) { ?>
    <p><a href="/<?=$this->moduleAlias?>/addcat/">Добавить</a></p>
<? } ?>
