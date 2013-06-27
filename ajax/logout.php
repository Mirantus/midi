<?php
	if ( !isset($_SESSION) ) session_start();
	unset($_SESSION['current_user']);
	echo 1;
?>