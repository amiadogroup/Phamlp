<?php
/* SVN FILE: $Id$ */
/**
 * Preserve Filter for {@link http://haml-lang.com/ Haml} class file.
 * @author			Chris Yates <chris.l.yates@gmail.com>
 * @copyright		Copyright (c) 2010 PBM Web Development
 * @license			http://phamlp.googlecode.com/files/license.txt
 * @package			PHamlP
 * @subpackage	Haml.filters
 */

/**
 * Preserve Filter for {@link http://haml-lang.com/ Haml} class.
 * Does not parse the filtered text and preserves line breaks.
 * @package			PHamlP
 * @subpackage	Haml.filters
 */
class Phamlp_Haml_Filter_Preserve extends Phamlp_Haml_Filter_Base {
	/**
	 * Run the filter
	 * @param string text to filter
	 * @return string filtered text
	 */
	public function run($text) {
	  return str_replace("\n", '&#x000a;',
	  	preg_replace(Phamlp_Haml_Parser::MATCH_INTERPOLATION, '<?php echo \1; ?>', $text)
	  ) . "\n";
	}
}