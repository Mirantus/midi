<?php
    /**
     * @var app\Controller\ModuleController $this
     * @var array $cats
     * @var string $title
     */
 ?>
<h3><?=$title?></h3>
<ul class="ns">
    <?php
        if (!empty($cats)) {
            foreach ($cats as $cat) {
                echo '<li id="item' . $cat['id'] . '">';
                    $url = '/module/cat/' . $cat['id'] . '/';
                    echo $this->app->createLink($url, h($cat['title']));
                echo '</li>';
            }
        } else echo '<li>Данных нет</li>';
    ?>
</ul>
<?php include($this->app->partialPath . '/pagination.php');?>