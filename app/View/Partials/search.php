<?php
    /**
     * @var core\View $this
     */
?>
<div class="searchArea">
	<form name="search-form" action="http://www.yandex.ru/yandsearch" method="get">
		<input name="text" type="search" maxlength="255" value="" placeholder="Текст поиска" required><input type="submit" value="Найти">
		<input type="hidden" name="stype" value="www">
		<input type="hidden" name="surl" value="<?=$this->app->pageUrl?>">
	</form>
</div>