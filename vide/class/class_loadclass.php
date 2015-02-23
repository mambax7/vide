<?php
class load_class {
	protected $blocks = array();
	protected $block_start = 3;
	protected $block_end = 8;
	protected $string_start = 2;
	protected $string_end = 8;
	protected $string = array();
	protected $regex_string = '(\'\')|("")|("(.|[\s\r\n\t\x02\x03\x08])*?[^\\\]")|(\'(.|[\s\r\t\n\x02\x03\x08])*?[^\\\]\')';
	protected $regex_heredoc = '<<<\'?([^\s^\n^\r]+)\'?[\s\r\n\t]?(.|[\s\r\n\t])*?\1';
	protected $regex_comment = '((\/\/)(.|[\s\r\x02\x03\x08])*?(\n))|(\/\*(.|[\s\r\n\x02\x03\x08])*?(\*\/))';
	protected $regex_class = 'class([\t\s\r\n]+)([a-zA-Z0-9_]+)([\t\s\r\n]*\{[\x03]([0-9]+)[\x08])\}';
	protected $regex_class_search_begin = 'class([\t\s\r\n]+)';
	protected $regex_class_search_end = '([\t\s\r\n]*\{[\x03]([0-9]+)[\x08])\}';
	static function execute($classes){
        global $DirName;
		$extends='';
		foreach ($classes as $v){
			load_class::heritage(XOOPS_ROOT_PATH.'/modules/'.$DirName.'/class/class_'.$v.'.php', $v, $extends);
			$extends = $v;
		}
        return new $extends();
	}
	static function execute_cache($classes, $cache_file){
		$extends='';
		if (file_exists($cache_file)){
			require($cache_file);
			$extends = $classes[count($classes)-1]['name'];
		} else{
			foreach ($classes as $item){
				load_class::heritage_cache($item['file'], $item['name'], $extends, $cache_file);
				$extends = $item['name'];
			}
			require($cache_file);
		}
		return new $extends();
	}
	static function heritage($file, $class_name, $extends){
		$code = file_get_contents($file);
		$parser = new load_class();
		$class = $parser->fetch_class($code, $class_name);
		$new = 'class '.$class['name'];
		if ($extends!='') $new .= ' extends '.$extends;
		$new .= ' { '.$class['source'].' } ';
		eval($new);
		return true;
	}
	static function heritage_cache($file, $class_name, $extends, $dest){
		$code = file_get_contents($file);
		$parser = new load_class();
		$class = $parser->fetch_class($code, $class_name);
		if (count($class)==0) return false;
		if (!file_exists($dest)) $new = '<?php '; else $new = '';
		$new .= 'class '.$class['name'];
		if ($extends!='') $new .= ' extends '.$extends;
		$new .= '{'.$class['source'].'}';
		$h = fopen($dest, 'a+');
		fwrite($h, $new);
		return true;
	}
	public function fetch_class($code, $class_name)	{
		$code = $this->comments_remove($code);
		$code = $this->strings_protect($code);
		$class = $this->class_get($code, $class_name);
		return $class;
	}
	public function fetch_classes($code){
		$code = $this->comments_remove($code);
		$code = $this->strings_protect($code);
		$classes = $this->classes_get($code);
		return $classes;
	}
	public function classes_get($code, $blocks_protected = false, $blocks_registered = false){
		$classes = array();

		// protect blocks
		if (!$blocks_protected) $code = $this->blocks_protect($code, !$blocks_registered);

		$pat = '/'.$this->regex_class.'/';
		preg_match_all($pat, $code, $matches);
		$count=count($matches);
		if ($count===0) return array();

		for($t=0; $t<$count; ++$t)
		{
			$class = array();
			$class['name'] = $matches[2][$t];
			$class['source'] = $this->strings_retrieve($this->blocks[intval($matches[4][$t])]['source']);
			$classes[] = $class;
		}
		return $classes;
	}

	public function class_get($code, $class_name, $blocks_protected = false, $blocks_registered = false)
	{
		// protect blocks
		if (!$blocks_protected) $code = $this->blocks_protect($code, !$blocks_registered);

		$pat = '/'.$this->regex_class_search_begin.$class_name.$this->regex_class_search_end.'/';
		preg_match_all($pat, $code, $matches);
		if (count($matches)===0) return array();
		$class = array();
		$class['name'] = $class_name;
		$class['source'] = $this->strings_retrieve($this->blocks[intval($matches[3][0])]['source']);

		return $class;
	}

	private function blocks_protect($code, $register=true)
	{
		// register blocks in this->blocks
		if ($register) $this->blocks_register($code);
		$blocks = $this->blocks;

		// replace every block begining by the last one
		for ($t=count($blocks)-1; $t>=0; $t--)
		{
			if ($blocks[$t]!==true) $code = str_replace($blocks[$t]['source'], chr($this->block_start).$t.chr($this->block_end), $code);
		}
		return $code;
	}
	private function blocks_register($code)
	{
		$block_start = array();
		$offset=0;

		$l=strpos($code, '{', $offset);
		$r=strpos($code, '}', $offset);
		while ($l!==false || $r!==false)
		{
			if ($r===false || $l!==false && $l<$r)
			{
				$block_start[] = $l;
				$offset = $l+1;
			} else
			{
				$start = array_pop($block_start)+1;
				if (count($block_start)===0) $this->blocks[] = array( 'source' => substr($code, $start, $r-$start) );
				$offset = $r+1;
			}
			$l=strpos($code, '{', $offset);
			$r=strpos($code, '}', $offset);
		}
	}
	private function blocks_clean()
	{
		$this->blocks = array();
	}

	private function blocks_retrieve($code)
	{
		$pat = '/'.chr($this->block_start).'(.)*?'.chr($this->block_end).'/';
		preg_match_all($pat, $code, $matches);
		foreach($matches[0] as $key => $value)
		{
			$int = intval(str_replace(array(chr($this->block_start), chr($this->block_end)), '', $value));
			$str = $this->blocks[$int]['source'];
			$code = str_replace($value, $str, $code);
		}

		return $code;
	}

	private function comments_remove($code)
	{
		$pat = '/'.$this->regex_string.'|'.$this->regex_comment.'/';
		preg_match_all($pat, $code, $matches);
		$strings = string_sort_by_len($matches[0]);
		foreach ($strings as $str)
		{
			//			if (substr($str, 0, 1)==="/")
			if (strncmp($str, '/', 1)===0)
			{
				$code = str_replace($str, "\n", $code);
			}
		}
		return $code;
	}

	private function strings_protect($code, $register=true)
	{
		// registers the quoted strings from the code in this->strings
		if ($register)
		{
			$this->strings_heredoc_register($code);
			$this->strings_register($code);
			$this->strings = string_sort_by_len($this->strings);
		}

		// Replace the strings
		foreach($this->strings as $key => $value)
		{
			$code = str_replace($value, chr($this->string_start).$this->strings_index($value).chr($this->string_end), $code);
		}

		return $code;
	}

	private function strings_index($string)
	{
		return array_search($string, $this->strings);
	}

	private function strings_heredoc_register($code)
	{
		$pat = '/'.$this->regex_heredoc.'/';
		preg_match_all($pat, $code, $matches);
		foreach ($matches[0] as $string)
		{
			$this->strings[] = $string;
		}
	}

	private function strings_register($code)
	{
		$pat = '/'.$this->regex_string.'/';
		preg_match_all($pat, $code, $matches);
		foreach ($matches[0] as $string)
		{
			$this->strings[] = $string;
		}
	}

	private function strings_clean()
	{
		$this->strings = array();
	}

	private function strings_retrieve($code)
	{
		$pat = '/'.chr($this->string_start).'(.)*?'.chr($this->string_end).'/';
		preg_match_all($pat, $code, $matches);
		foreach($matches[0] as $key => $value)
		{
			$int = intval(str_replace(array(chr($this->string_start), chr($this->string_end)), '', $value));
			$str = $this->strings[$int];
			$code = str_replace($value, $str, $code);
		}

		return $code;
	}



}

function string_sort_by_len($strings)
{
	$sort = array_combine($strings, array_map('strlen', $strings));
	arsort($sort);
	return array_keys($sort);
}

