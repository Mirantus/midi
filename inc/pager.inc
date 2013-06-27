<?php
	function pager($items, $per_page)
	{
		$result = false;
		$current = sql_prepare( assign('page') );
		if ($current == '') $current = 1;
		$total = count($items);
		$pages = ceil($total / $per_page);
		
		$i = 1;
		$start = $current * $per_page - $per_page + 1;
		$end = $current * $per_page;
		foreach ($items as $item_id => $item_params)
		{
			if ($i >= $start && $i <= $end ) $result['items'][$item_id] = $item_params;
			$i++;
		}
		
		if ($pages > 1)
		{
			$result['pager'] = '<ul class="pager">';
			for ($i = 1; $i <= $pages; $i++ ) 
			{
				if ($i == $current)
					$result['pager'] .= '<li>' . $i . '</li> ';
				else
					$result['pager'] .= '<li><a href="' . assign_url_param(PAGE_URL, 'page', $i) . '">' . $i . '</a></li> ';
			}
			$result['pager'] .= '</ul>';
		}
		
		return $result;
	}	
?>