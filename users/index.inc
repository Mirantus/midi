<? if ($_SESSION['current_user']['access'] > 1): ?>
	<script type="text/javascript" src="js/edit.js"></script>
	<ul>
		<?
			if ($items)
			{
				foreach ($items as $item_id => $item_params)
				{
					echo '<li id="item' . $item_id . '">
							<a href="' . MODULE_URL . '/item.php?id=' . $item_id . '">' . $item_params['title'] . '</a>
							<a href="' . MODULE_URL . '/edit.php?id=' . $item_id . '">редактировать</a> 
							<a id="delitem" href="javascript:delitem(' . $item_id . ',\'' . MODULE . '\')">удалить</a>
						</li>';
				}
			}
			else echo '<li>Пользователей нет</li>';
		?>
	</ul>
	<?=$pager;?>
	<a href="<?=MODULE_URL . '/edit.php';?>">Добавить</a>
<? else: ?>
	<? include_once(SITE_PATH . '/inc/login.inc'); ?>
	<div class="clear"></div>
<? endif; ?>