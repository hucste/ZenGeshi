<?php
/**
 * zenGeshi - GeSHi support
 *
 * Provides GeSHi : Generic Syntax Higjlighter
 *
 * @author Stephane HUC (hucste) <devs@stephane-huc.net>
 * @package zenphoto plugin
 * @since 2011.04.03
 *
 */

include(dirname(__FILE__).'/zenGeshi/class.ZenGeshi.php');

// here declarate new zengeshi !
$zengeshi = new ZenGeshi();
$zengeshi_version = $zengeshi->getPluginVersion();
$geshi_version = $zengeshi->getGeshiVersion();
if( !empty( $geshi_version ) ) {
	$zengeshi_geshi_info = '<em>Geshi version: '.$geshi_version.'</em>';
}
else $zengeshi_geshi_info = '';

// here declarate $variables for zenphoto
$plugin_is_filter = 5|ADMIN_PLUGIN|THEME_PLUGIN;
$plugin_description =  gettext('Adding Geshi support - '.$zengeshi_geshi_info);
$plugin_author = gettext('Stephane HUC (alias &quot;hucste&quot;)');
if( !empty( $zengeshi_version ) )	$plugin_version = $zengeshi_version;
$plugin_URL = 'http://zenphoto.dev.stephane-huc.net/pages/zenGeshi-Plugin';

$option_interface = 'ZenGeshiOptions';

zp_register_filter('theme_head','printHighLightCSS');
zp_register_filter('theme_body','printHighLightCode');

function getHighlightCode($id, $content) {
	global $zengeshi;
	$zengeshi->getCode($id, $content);
}

function printHighlightCSS() {
	global $zengeshi;
	$zengeshi->printCSS();
}

function printHighlightCode($id) {
	global $zengeshi;
	$zengeshi->printCode($id);
}

class ZenGeshiOptions {

	function ZenGeshiOptions() {
		setOptionDefault('zengeshi_enable_line_numbers', 'No');
		setOptionDefault('zengeshi_start_number', 0);
		setOptionDefault('zengeshi_styling_line_numbers_normal', '');
		setOptionDefault('zengeshi_styling_line_numbers_fancy', '');
		setOptionDefault('zengeshi_styling_line_numbers_overwrite', 0);
	}

	function getOptionsSupported() {
		return array (
			gettext('Enabling Line Numbers') => array (
				'desc' => gettext('To highlight a source with line numbers...'),
				'key' => 'zengeshi_enable_line_numbers',
				'selections' => array (
					gettext('No Line Numbers') => 'No',
					gettext('Normal Line Numbers') => 'Normal',
					gettext('Fancy Line Numbers') => 'Fancy',
				),
				'type' => OPTION_TYPE_SELECTOR,
			),
			gettext('Choosing a Start Number') => array (
				'desc' => gettext('make the line numbers start at any number, rather than just 1.'),
				'key' => 'zengeshi_start_number',
				'type' => OPTION_TYPE_TEXTBOX,
			),
			gettext('Styling Line Numbers: For Normal Style!') => array (
				'desc' => gettext('Styles CSS are set for line numbers: <em>example: background: #fcfcfc;</em>.<br>
				If you’re using Fancy Line Numbers mode, you pass a second string for the style in textbox named &quot;Styling Line Numbers: For Fancy Style!&quot;.'),
				'key' => 'zengeshi_styling_line_numbers_normal',
				'type' => OPTION_TYPE_TEXTBOX,
			),
			gettext('Styling Line Numbers: For Fancy Style!') => array (
				'desc' => gettext('Styles CSS are set for line numbers: <em>example: background: #f0f0f0;</em>.<br>
				If you’re using this mode, you do to pass a first string for the style in textbox named &quot;Styling Line Numbers: For Normal Style!&quot;.'),
				'key' => 'zengeshi_styling_line_numbers_fancy',
				'type' => OPTION_TYPE_TEXTBOX,
			),
			gettext('Styling Line Numbers overwriting') => array (
				'desc' => gettext('Those styles you pass overwrite the current styles. Choose &quot;Combine the style&quot; specify to combine your styles with the generic geshi&apos; styles.'),
				'key' => 'zengeshi_styling_line_numbers_overwrite',
				'selections' => array (
					gettext('Overwrite the style') => 0,
					gettext('Combine the style') => 1,
				),
				'type' => OPTION_TYPE_SELECTOR,
			),
		);
	}

	function handleOption($option,$currentValue) {	}

}

?>
