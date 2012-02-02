<?php
/* SVN FILE: $Id$ */
/**
 * Phamlp_Sass_Renderer_Renderer class file.
 * @author			Chris Yates <chris.l.yates@gmail.com>
 * @copyright 	Copyright (c) 2010 PBM Web Development
 * @license			http://phamlp.googlecode.com/files/license.txt
 * @package			PHamlP
 * @subpackage	Sass.renderers
 */

require_once('SassCompactRenderer.php');
require_once('SassCompressedRenderer.php');
require_once('SassExpandedRenderer.php');
require_once('SassNestedRenderer.php');

/**
 * Phamlp_Sass_Renderer_Renderer class.
 * @package			PHamlP
 * @subpackage	Sass.renderers
 */
class Phamlp_Sass_Renderer_Renderer {
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
	 * @return Phamlp_Sass_Renderer_Renderer
	 */
	public static function getRenderer($style) {
		switch ($style) {
			case self::STYLE_COMPACT:
		  	return new SassCompactRenderer();
			case self::STYLE_COMPRESSED:
		  	return new SassCompressedRenderer();
			case self::STYLE_EXPANDED:
		  	return new SassExpandedRenderer();
			case self::STYLE_NESTED:
		  	return new SassNestedRenderer();
		} // switch
	}
}