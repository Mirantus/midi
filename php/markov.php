<?
Class MarkovChains{
	var $prepared = array();
	function MarkovChains($source){
		$source = strtolower($source);
		$source = str_replace(array ("? ", "! "), ".", $source);
		$source = str_replace(array (" -", "- ", "\t", "\r", "\n", "|", "&", '\\', '/', " :", " ;", "©", "•"), ' ', $source);
		$source = str_replace(array (")", "(", "]", "[", "'", "\"", '*', '•', '~', '{', '}'), '', $source);
		$source = str_replace(" ,", ",", $source);
		$source = preg_replace("~(\s+\d{1,2}\s+)|(\w*\.\w+)~", " ", $source);
		$source = preg_replace("~\s+~", " ", $source);$sentens = explode('. ', $source);
		$count_sentens = count($sentens);
		for ($j=0; $j<$count_sentens; ++$j){
		$sentens[$j] = explode(' ', $sentens[$j]);
			$count_words = count($sentens[$j]) - 1;
			for ($i=0; $i < $count_words; ++$i){
				$prefix = $sentens[$j][$i];
				$this->prepared[$prefix][] = $sentens[$j][$i+1];
			}
		}
		$keys = array_keys($this->prepared);
		foreach ($keys as $key){
			$this->prepared[$key] = array_unique($this->prepared[$key]);
		}
	}
	function GenerateText($size){
		$result_count = 0;
		for ($j=0; $result_count < $size; ++$j){
			$prev = array_rand($this->prepared);
			$num = mt_rand(5, 12);
			for ($i=0; $i<$num; ++$i){
				$sents[$j][$i] = $prev;
				++$result_count;
				$p = $this->prepared[$prev][mt_rand(0, count($this->prepared[$prev]) - 1)];
				if ($p == '') $p = array_rand($this->prepared);
				$prev = $p;
				if ($prev == '') break 2;
			}
		}
		foreach ($sents as $sent){
			$count_word=count($sent);
			if ($count_word<=2) continue;
			if (strlen($sent[$count_word-1]) < 4) unset($sent[$count_word-1]);
			$sent[$count_word-2] = rtrim($sent[$count_word-2], ",:;");
			$sent[$count_word-1] = rtrim($sent[$count_word-1], ",:;");
			$output .= ucfirst(implode(' ', $sent)).'. ';
		}
		$output = str_replace(' .', '.', $output);
		return $output;
	}
}
?>