<?php
/* SVN FILE: $Id$ */
/**
 * CDATA Filter for {@link http://haml-lang.com/ Haml} class file.
 * @author			Chris Yates <chris.l.yates@gmail.com>
 * @copyright		Copyright (c) 2010 PBM Web Development
 * @license			http://phamlp.googlecode.com/files/license.txt
 * @package			PHamlP
 * @subpackage	Haml.filters
 */

/**
 * CDATA Filter for {@link http://haml-lang.com/ Haml} class.
 * Surrounds the filtered text with CDATA tags.
 * @package			PHamlP
 * @subpackage	Haml.filters
 */
class Phamlp_Haml_Filter_Cdata extends Phamlp_Haml_Filter_Base {
	/**
	 * Run the filter
	 * @param string text to filter
	 * @return string filtered text
	 */
	public function run($text) {
	  return "<![CDATA[\n" .
	  	preg_replace(Phamlp_Haml_Parser::MATCH_INTERPOLATION, '<?php echo \1; ?>', $text) .
	  	"  ]]>\n";
	}
}
