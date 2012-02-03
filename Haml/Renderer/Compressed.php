<?php
/* SVN FILE: $Id$ */
/**
 * Phamlp_Haml_Renderer_Compressed class file.
 * @author			Chris Yates <chris.l.yates@gmail.com>
 * @copyright 	Copyright (c) 2010 PBM Web Development
 * @license			http://phamlp.googlecode.com/files/license.txt
 * @package			PHamlP
 * @subpackage	Haml.renderers
 */

/**
 * Phamlp_Haml_Renderer_Compressed class.
 * Output has minimal whitespace.
 * @package			PHamlP
 * @subpackage	Haml.renderers
 */
class Phamlp_Haml_Renderer_Compressed extends Phamlp_Haml_Renderer {
	/**
	 * Renders the opening of a comment.
	 * Only conditional comments are rendered
	 */
	public function renderOpenComment($node) {
		if ($node->isConditional) return parent::renderOpenComment($node);
	}

	/**
	 * Renders the closing of a comment.
	 * Only conditional comments are rendered
	 */
	public function renderCloseComment($node) {
		if ($node->isConditional) return parent::renderCloseComment($node);
	}
	
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
	  return parent::renderClosingTag($node) . ($node->isBlock ? '' : ' ');
	}
}