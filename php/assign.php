<?php
	function assign($string)
	{
		$result = assign_html($string);
		if ($result != '')
		{
			$result = strip_tags($result);
			$result = htmlspecialchars($result);
		}	
		
		return $result;
	}	
	
	function assign_html($string)
	{
		$result = '';
		if ( isset($_GET[$string]) ) $result = $_GET[$string];
		if ( isset($_POST[$string]) ) $result = $_POST[$string];	
		if ( isset($_SESSION[$string]) ) $result = $_SESSION[$string];	
		
		return $result;
	}	
	
	function assign_file($string)
	{
		$result = '';
		if ( isset($_FILES[$string]['tmp_name']) && $_FILES[$string]['tmp_name'] != '' && $_FILES['file']['error'] < 1 )
		{
			$result['title'] = $_FILES[$string]['name'];
			$result['text'] = file_get_contents($_FILES[$string]['tmp_name']);
            unlink($_FILES[$string]['tmp_name']);
		}
		
		return $result;
	}
	
	function assign_url_param($url, $param, $value)
	{
		if (strpos($url, '?'))
		{
			if (strpos($url, $param))
			{
				$params = explode('?', $url);
				$path = $params[0];
				$params = explode('&', $params[1]);

				foreach ($params as $params_id => $params_item)
				{
					if (strpos($params_item, $param) !== false)
					{
						$item = explode('=', $params_item);
						$params[$params_id] = $param . '=' . $value;
					}
				}
				$url = $path . '?' . implode('&', $params);
			}
			else $url .= '&' . $param . '=' . $value;
		}
		else $url .= '?' . $param . '=' . $value;
		
		return $url;
	}
	
	function assign_page_content()
	{
		$page = PAGE;
		$page = str_replace(MODULE, '', $page);
		$page = explode('_', $page);
		$page = array_reverse($page);
		$page = ($page[0] == '') ? 'index' : $page[0];
		return $page . '.inc';
	}
?>