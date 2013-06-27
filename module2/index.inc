<script type="text/javascript" src="js/edit.js"></script>
<ul>
	<?
		if ($cats)
		{
			foreach ($cats as $cat_id => $cat_params)
			{
				echo '<li id="cat' . $cat_params['id'] . '">
						<a href="' . MODULE_URL . '/cat.php?cat=' . $cat_params['id'] . '">' . $cat_params['title'] . '</a> ';
						if ($_SESSION['current_user']['access'] > 1)		
							echo '<a href="' . MODULE_URL . '/editcat.php?cat=' . $cat_params['id'] . '">редактировать</a> 
									<a id="delcat" href="javascript:delcat(' . $cat_params['id'] . ',\'' . MODULE . '\')">удалить</a>';
				echo '</li>';
			}
		}
		else echo '<li>Рубрик нет</li>';
	?>
</ul>
<? if ($_SESSION['current_user']['access'] > 1): ?>
<a href="<?=MODULE_URL . '/editcat.php';?>">Добавить</a>
<? endif; ?>