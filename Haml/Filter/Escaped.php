<?php
/* SVN FILE: $Id$ */
/**
 * Escaped Filter for {@link http://haml-lang.com/ Haml} class file.
 * @author			Chris Yates <chris.l.yates@gmail.com>
 * @copyright		Copyright (c) 2010 PBM Web Development
 * @license			http://phamlp.googlecode.com/files/license.txt
 * @package			PHamlP
 * @subpackage	Haml.filters
 */

/**
 * Escaped Filter for {@link http://haml-lang.com/ Haml} class.
 * Escapes the text.
 * Code to be interpolated can be included by wrapping it in #().
 * @package			PHamlP
 * @subpackage	Haml.filters
 */
class Phamlp_Haml_Filter_Escaped extends Phamlp_Haml_Filter_Base {
	/**
	 * Run the filter
	 * @param string text to filter
	 * @return string filtered text
	 */
	public function run($text) {
	  return preg_replace(
	  	Phamlp_Haml_Parser::MATCH_INTERPOLATION,
	  	'<?php echo htmlspecialchars($text); ?>',
	  	htmlspecialchars($text)
	  ) . "\n";
	}
}
