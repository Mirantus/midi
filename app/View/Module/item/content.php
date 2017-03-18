<?php
    /**
     * @var core\View $this
     * @var array $item
     * @var array $comments
     * @var string $dataPath
     */
 ?>
<h3><?=h($item['title'])?></h3>

<? if ($flash) { ?>
    <div class="flash"><?=$flash?></div>
<? } ?>

<? if (!empty($item['image'])) { ?>
    <a href="<?=$dataPath . $item['id']?>/<?=$item['image']?>" rel="lightbox"><img src="<?=$dataPath . $item['id']?>/thumb_<?=$item['image']?>" alt="<?=h($item['title'])?>" class="image floatright"></a>
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
    <p><a href="<?=$dataPath . $item['id']?>/<?=$item['file']?>">/<?=$item['file'];?></a></p>
<? } ?>
<? if (!empty($item['date'])) echo lib\Date::sqlToDate($item['date']); ?>

<? if (isset($comments)) { ?>
    <h4>Комментарии</h4>
    <p><a href="/<?=$this->moduleAlias?>/addcomment/<?=$item['id'];?>">Добавить комментарий</a></p>
    <? if (count($comments) > 0) { ?>
        <ul class="ns">
            <? foreach ($comments as $comment_params) { ?>
                <li id="comment<?=$comment_params['id'];?>">
                    <p>
                        <strong>
                            <? if (!empty($comment_params['user'])) { ?>
                                <a href="/users/<?=$comment_params['user']?>"><?=h($comment_params['user_name']);?></a>
                            <? } elseif (!empty($comment_params['email'])) { ?>
                                <a href="mailto:<?=$comment_params['email'];?>"><?=h($comment_params['name']);?></a>
                            <? } else { ?>
                                <?=h($comment_params['name']);?>
                            <? } ?>
                        </strong>

                        <? if ($comment_params['user'] == $this->auth->get('id') || $this->auth->isAdmin()) { ?>
                            <a href="/<?=$this->moduleAlias?>/editcomment/<?=$comment_params['id']?>/?return=<?=$_SERVER['REQUEST_URI']?>" class="comment"><i
                                        class="icon-pencil"></i></a>
                            <a href="/<?=$this->moduleAlias?>/delcomment/<?=$comment_params['id']?>/" class="comment" data-request="ajax"
                               data-confirm="item-del"><i class="icon-remove"></i></a>
                        <? } ?>
                        <br>
                        <?=h($comment_params['text']);?>
                    </p>
                </li>
            <? } ?>
        </ul>
    <? } else { ?>
        <p>Комментариев нет</p>
    <? } ?>

<? } ?>