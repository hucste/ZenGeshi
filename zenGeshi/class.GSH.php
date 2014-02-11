<?php
class GSH extends GeSHi { 
	
	public function __construct() {
		parent::GeSHi();
	}
	
	public function __destruct() {
	}
	
	public function getVersion() {
		return GESHI_VERSION;
	}
}
?>
