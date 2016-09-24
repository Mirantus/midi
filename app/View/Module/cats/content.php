<?php
    /**
     * @var core\View $this
     * @var array $cats
     */
 ?>
<h3><?=$this->title?></h3>
<ul class="ns">
    <?php
        if (!empty($cats)) {
            foreach ($cats as $cat) {
                echo '<li id="item' . $cat['id'] . '">';
                    $url = '/module/cat/' . $cat['id'] . '/';
                    echo lib\Url::createLink($url, h($cat['title']));
                echo '</li>';
            }
        } else echo '<li>Данных нет</li>';
    ?>
</ul>
<?php include($this->partialPath . '/pagination.php');?>