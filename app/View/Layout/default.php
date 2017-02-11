<?php    /**     * @var core\View $this     */?><!DOCTYPE html><html><head>	<meta http-equiv="Content-type" content="text/html; charset=utf-8">		<meta http-equiv="Content-Language" content="ru_RU">	<meta http-equiv="X-UA-Compatible" content="IE=Edge">	<link rel="stylesheet" href="/css/site.css?<?=$this->app->version?>" type="text/css">	<?php		if (file_exists($this->path . '/head.php')) {			include($this->path . '/head.php');		}		else {		    echo '<title>' . $this->app->title . '</title>';        }	?>	<meta name="robots" content="index, follow"></head><body>    <div class="layout-container">        <header>            <?php include($this->partialPath . '/header.php');?>        </header>        <div class="top-panel clearfix">            <div class="container">                <?php include($this->partialPath . '/search.php');?>            </div>        </div>        <div class="layout-content">            <div class="container">                <aside>                    <?php include($this->partialPath . '/menu.php');?>                </aside>                <section>                    <?php include($this->path . '/content.php');?>                </section>            </div>        </div>        <div class="bottom-panel">            <div class="container">                <?php include($this->partialPath . '/copyright.php');?>            </div>        </div>        <footer>            <div class="container">                <?php include($this->partialPath . '/counter.php');?>            </div>        </footer>    </div>    <script src="/js/jquery.min.js"></script>    <script src="/js/jquery-sortable/jquery.sortable.js"></script>    <script src="/js/ajax.js?<?=$this->app->version?>"></script>    <?php        if (file_exists($this->path . '/script.php')) {            include($this->path . '/script.php');        }    ?></body></html>