<?php
    /**
     * @var app\Controller\ModuleController $this
     * @var array $item
     * @var array $comments
     */
 ?>
<h3><?=h($item['title'])?></h3>

<? if (!empty($item['image'])) { ?>
    <a href="/data/<?=$this->name?>/items/<?=$item['id']?>/<?=$item['image']?>" rel="lightbox"><img src="/data/<?=$this->name?>/items/<?=$item['id']?>/thumb_<?=$item['image']?>" alt="<?=h($item['title'])?>" class="image floatright"></a>
<? } ?>

<?
    if (!empty($item['text'])) echo $item['text'] . '<br>';
    if (!empty($item['price'])) echo 'Цена: ' . $item['price'] . 'р.<br>';
    if (!empty($item['name'])) echo 'Имя: ' . $item['name'] . '<br>';
    if (!empty($item['phone'])) echo 'Телефон: ' . $item['phone'] . '<br>';
    if (!empty($item['url'])) echo 'Сайт: <a href="' . $item['url'] . '" target="_blank">' . $item['url'] . '</a><br>';
    if (!empty($item['email'])) echo 'E-mail: <a href="mailto:' . $item['email'] . '">' . $item['email'] . '</a><br>';
    if (!empty($item['address'])) echo 'Адрес: ' . $item['address'] . '<br>';
?>

<?
?>
<? if (!empty($item['file'])) { ?>
    <p><a href="/data/<?=$this->name?>/items/<?=$item['id']?>/<?=$item['file']?>">/<?=$item['file'];?></a></p>
<? } ?>
<? if (!empty($item['date'])) echo Date::sqlToDate($item['date']); ?>

<? if (isset($comments)) { ?>
    <h4>Комментарии</h4>
    <p><a href="/module/addcomment/<?=$item['id'];?>">Добавить комментарий</a></p>
    <? if (count($comments) > 0) { ?>
        <? foreach ($comments as $comment_params) { ?>
            <p id="comment<?=$comment_params['id'];?>">
                <strong>
                    <? if (!empty($comment_params['email'])) { ?>
                        <a href="mailto:<?=$comment_params['email'];?>"><?=h($comment_params['name']);?></a>
                    <? } else { ?>
                        <?=h($comment_params['name']);?>
                    <? } ?>
                </strong><br>
                <?=h($comment_params['text']);?>
            </p>
        <? } ?>
    <? } else { ?>
        <p>Комментариев нет</p>
    <? } ?>

<? } ?>