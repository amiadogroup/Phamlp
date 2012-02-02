<?php
/* SVN FILE: $Id$ */
/**
 * Phamlp_Sass_Renderer class file.
 * @author			Chris Yates <chris.l.yates@gmail.com>
 * @copyright 	Copyright (c) 2010 PBM Web Development
 * @license			http://phamlp.googlecode.com/files/license.txt
 * @package			PHamlP
 * @subpackage	Sass.renderers
 */

/**
 * Phamlp_Sass_Renderer class.
 * @package			PHamlP
 * @subpackage	Sass.renderers
 */
class Phamlp_Sass_Renderer {
	/**#@+
	 * Output Styles
	 */
	const STYLE_COMPRESSED = 'compressed';
	const STYLE_COMPACT 	 = 'compact';
	const STYLE_EXPANDED 	 = 'expanded';
	const STYLE_NESTED 		 = 'nested';
	/**#@-*/

	const INDENT = '  ';

	/**
	 * Returns the renderer for the required render style.
	 * @param string render style
	 * @return Phamlp_Sass_Renderer
	 */
	public static function getRenderer($style) {
		switch ($style) {
			case self::STYLE_COMPACT:
		  	return new Phamlp_Sass_Renderer_Compact();
			case self::STYLE_COMPRESSED:
		  	return new Phamlp_Sass_Renderer_Compressed();
			case self::STYLE_EXPANDED:
		  	return new Phamlp_Sass_Renderer_Expanded();
			case self::STYLE_NESTED:
		  	return new Phamlp_Sass_Renderer_Nested();
		} // switch
	}
}
