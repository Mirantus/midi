<script type="text/javascript" src="js/edit.js"></script>
<ul>
	<?
		if ($items)
		{
			foreach ($items as $item_id => $item_params)
			{
				echo '<li id="item' . $item_id . '">
						<a href="' . MODULE_URL . '/item.php?id=' . $item_id . '">' . $item_params['title'] . '</a> ';
						if ($_SESSION['current_user']['access'] > 1 || $_SESSION['current_user']['id'] === $item_params['user'])		
							echo '<a href="' . MODULE_URL . '/edit.php?id=' . $item_id . '">редактировать</a> 
									<a id="delitem" href="javascript:delitem(' . $item_id . ',\'' . MODULE . '\')">удалить</a>';
				echo '</li>';
			}
		}
		else echo '<li>Данных нет</li>';
	?>
</ul>
<?=$pager;?>
<a href="<?=MODULE_URL . '/edit.php';?>">Добавить</a>