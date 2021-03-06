<?php
/* SVN FILE: $Id$ */
/**
 * CSS Filter for {@link http://haml-lang.com/ Haml} class file.
 * @author			Chris Yates <chris.l.yates@gmail.com>
 * @copyright		Copyright (c) 2010 PBM Web Development
 * @license			http://phamlp.googlecode.com/files/license.txt
 * @package			PHamlP
 * @subpackage	Haml.filters
 */

/**
 * CSS Filter for {@link http://haml-lang.com/ Haml} class.
 * Surrounds the filtered text with <style> and CDATA tags.
 * Useful for including inline CSS.
 * @package			PHamlP
 * @subpackage	Haml.filters
 */
class Phamlp_Haml_Filter_Css extends Phamlp_Haml_Filter_Base {
	/**
	 * Run the filter
	 * @param string text to filter
	 * @return string filtered text
	 */
	public function run($text) {
	  return "<style type=\"text/css\">\n/*<![CDATA[*/\n" .
	  	preg_replace(Phamlp_Haml_Parser::MATCH_INTERPOLATION, '<?php echo \1; ?>', $text) .
	  	"/*]]>*/\n</style>\n";
	}
}