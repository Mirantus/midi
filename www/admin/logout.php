<?php
    require('../core/init.php');
    $_SESSION['current_user'] = array ('id' => 0, 'title' => 'Гость', 'access' => '1');
    $site->redirect('/');