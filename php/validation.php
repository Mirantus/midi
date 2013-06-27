<?php
	function is_word($string)
	{
		return preg_match("/\w{1,}/", $string);
	}
?>