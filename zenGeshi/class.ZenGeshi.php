<?php
class ZenGeshi {

	private $geshi;	// object geshi
	private $gsh;
	private $zengeshi = array();

	public function __construct() {
		$this->setConstants();

		$this->getFilesNeeded();

		$this->setVariables();
	}

	public function __destruct() {
		unset($zengeshi);
	}

	private function geshi_advanced_features() {
		$this->geshi_enable_line_numbers();
		$this->geshi_start_number();
		$this->geshi_styling_line_numbers();
	}

	// Enable Line numbers
	private function geshi_enable_line_numbers() {

		if($this->zengeshi['enable_line_numbers'] != 'No') {
			switch($this->zengeshi['enable_line_numbers']) {
				case 'Fancy':
					$this->geshi->enable_line_numbers(GESHI_FANCY_LINE_NUMBERS);
				break;

				case 'Normal':
					$this->geshi->enable_line_numbers(GESHI_NORMAL_LINE_NUMBERS);
				break;

				default:
					$this->geshi->enable_line_numbers(GESHI_NO_LINE_NUMBERS);
				break;
			}
		}
		else $this->geshi->enable_line_numbers(GESHI_NO_LINE_NUMBERS);

	}

	// Choosing a start number
	private function geshi_start_number() {
		if(!empty($this->zengeshi['start_number'])) {
			$this->geshi->start_line_numbers_at($this->zengeshi['start_number']);
		}
	}

	// Style Line numbers
	private function geshi_styling_line_numbers() {

		$boolean = $this->zengeshi['style_line_numbers']['overwrite'];
		$mode = $this->zengeshi['enable_line_numbers'];
		$style['fancy'] = $this->zengeshi['style_line_numbers']['fancy'];
		$style['normal'] = $this->zengeshi['style_line_numbers']['normal'];

		if(!empty($boolean)) $boolean = true;
		else $boolean = false;

		if($mode == 'Fancy' && !empty($style['fancy'])) $this->geshi->set_line_style($style['normal'], $style['fancy'], $boolean);
		else $this->geshi->set_line_style($style['normal'], $boolean);

		unset($boolean, $mode, $style);
	}

	public function getCode($id, $content) {

		if(!empty($id)) $id = strval($id);

		if(preg_match_all($this->zengeshi['pattern'], $content, $match)) {

			if(!empty($match[1]) && is_array($match[1])) {

				foreach($match[1] as $key => $value) {
					if(!empty($value)) $lang = $value;

					if(!empty($match[2][$key])) {
						$this->zengeshi['search'][$id][$key] = "<!-- geshi lang='".$lang."' -->".$match[2][$key].'<!-- geshi end -->';
						$source = htmlspecialchars_decode($match[2][$key]);
					}

					if(!empty($lang) && !empty($source)) {
						$this->geshi = new GeSHi($source, $lang);
						$this->zengeshi['css'][$lang] = $this->geshi->get_stylesheet();
						$this->geshi_advanced_features();
						$this->zengeshi['replace'][$id][$key] = $this->geshi->parse_code();
						unset($this->geshi);
					}
					unset($lang,$source);
				}
				unset($key, $value);

			}
		}

		if( !empty($this->zengeshi['search'][$id]) && is_array($this->zengeshi['search'][$id])
			&& !empty($this->zengeshi['replace'][$id]) && is_array($this->zengeshi['replace'][$id]) )
		{
			$this->zengeshi['content'][$id] = str_replace($this->zengeshi['search'][$id], $this->zengeshi['replace'][$id], $content);
		}
		else $this->zengeshi['content'][$id] = $content;

		unset($id, $content);
	}

	private function getFilesNeeded() {
		require_once(ZENGESHI_SCRIPT);
		require_once(ZENGESHI_ROOT.'/class.GSH.php');
	}

	public function getGeshiVersion() {
		if(!empty($this->zengeshi['geshi_version'])) return $this->zengeshi['geshi_version'];
	}

	public function getPluginVersion() {
		return ZENGESHI_VERSION;
	}

	public function printCode($id) {
		if(!empty($id)) $id = strval($id);

		if(!empty($this->zengeshi['content'][$id])) echo $this->zengeshi['content'][$id];
		else echo 'This id:'.$id.' is empty or not exists!';
	}

	public function printCSS() {

		if( !empty($this->zengeshi['css'])
			&& is_array($this->zengeshi['css']) )
		{
			$html = '<!-- GeSHi CSS -->'."\n";
			$html .= '<style type="text/css">'."\n";
			$html .= '// <!-- <![CDATA['."\n";

			foreach($this->zengeshi['css'] as $value) {
				$html .= $value;
			}
			unset($value);

			$html .= '// ]]> -->'."\n";
			$html .= '</style>'."\n";
			$html .= '<!-- GeSHi CSS end -->'."\n";
		}

		if(!empty($html)) {
			echo $html;
			unset($html);
		}

	}

	private function setConstants() {
		define('ZENGESHI_ROOT', dirname(__FILE__));
		define('ZENGESHI_SCRIPT', ZENGESHI_ROOT.'/geshi.php');
		define('ZENGESHI_VERSION', trim( file_get_contents( ZENGESHI_ROOT.'/version' ) ) );
	}

	private function setVariables() {
		$zengeshi['pattern'] = "`<!-- geshi lang='(.*)' -->(.*)<!-- geshi end -->`Us";;

		$zengeshi['enable_line_numbers'] = getOption('zengeshi_enable_line_numbers');
		$zengeshi['start_number'] = intval(getOption('zengeshi_start_number'));
		$zengeshi['style_line_numbers']['fancy'] = getOption('zengeshi_styling_line_numbers_fancy');
		$zengeshi['style_line_numbers']['normal'] = getOption('zengeshi_styling_line_numbers_normal');
		$zengeshi['style_line_numbers']['overwrite'] = getOption('zengeshi_styling_line_numbers_overwrite');

		$gsh = new GSH();
		$zengeshi['geshi_version'] = $gsh->getVersion();

		$this->zengeshi = $zengeshi;
	}
}
?>
