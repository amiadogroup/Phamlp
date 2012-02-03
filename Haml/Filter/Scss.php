<?php
/* SVN FILE: $Id: Phamlp_Haml_Filter_Sass.php 49 2010-04-04 10:51:24Z chris.l.yates $ */
/**
 * {@link Scss http://sass-lang.com/} Filter for
 * {@link http://haml-lang.com/ Haml} class file.
 * @author			Chris Yates <chris.l.yates@gmail.com>
 * @copyright		Copyright (c) 2010 PBM Web Development
 * @license			http://phamlp.googlecode.com/files/license.txt
 * @package			PHamlP
 * @subpackage	Haml.filters
 */

/**
 * {@link Sass http://sass-lang.com/} Filter for
 * {@link http://haml-lang.com/ Haml} class.
 * Parses the text as Sass then calls the CSS filter.
 * Useful for including inline Sass.
 * @package			PHamlP
 * @subpackage	Haml.filters
 */
class Phamlp_Haml_Filter_Scss extends Phamlp_Haml_Filter_Base {
	/**
	 * Run the filter
	 * @param string text to filter
	 * @return string filtered text
	 */
	public function run($text) {
		$sass = new Phamlp_Sass_Parser(array('syntax'=>'scss'));
		$css = new Phamlp_Haml_Filter_Css();
		$css->init();

		return $css->run($sass->toCss(preg_replace(Phamlp_Haml_Parser::MATCH_INTERPOLATION, '<?php echo \1; ?>', $text), false));
	}
}