<?php
require('../../core/init.php');
$site->setModule(basename(dirname(__FILE__)));
include($site->layoutPath . '/default.phtml');