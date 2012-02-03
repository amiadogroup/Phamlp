<?php
/* SVN FILE: $Id$ */
/**
 * Phamlp_Haml_Renderer_Compact class file.
 * @author			Chris Yates <chris.l.yates@gmail.com>
 * @copyright 	Copyright (c) 2010 PBM Web Development
 * @license			http://phamlp.googlecode.com/files/license.txt
 * @package			PHamlP
 * @subpackage	Haml.renderers
 */

/**
 * Phamlp_Haml_Renderer_Compact class.
 * Renders blocks on single lines.
 * @package			PHamlP
 * @subpackage	Haml.renderers
 */
class Phamlp_Haml_Renderer_Compact extends Phamlp_Haml_Renderer {
	/**
	 * Renders the opening tag of an element
	 */
	public function renderOpeningTag($node) {
	  return ($node->isBlock ? '' : ' ') . parent::renderOpeningTag($node);
	}
	
	/**
	 * Renders the closing tag of an element
	 */
	public function renderClosingTag($node) {
	  return parent::renderClosingTag($node) . ($node->isBlock ? "\n" : ' ');
	}
}