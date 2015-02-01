<?php
    require('../../core/init.php');
    $_SESSION['current_user'] = array ('id' => 1, 'title' => 'Администратор', 'access' => '4');
    $site->redirect('/');