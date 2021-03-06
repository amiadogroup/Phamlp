<?php
/* SVN FILE: $Id$ */
/**
 * Phamlp_Haml_Renderer_Expanded class file.
 * @author			Chris Yates <chris.l.yates@gmail.com>
 * @copyright 	Copyright (c) 2010 PBM Web Development
 * @license			http://phamlp.googlecode.com/files/license.txt
 * @package			PHamlP
 * @subpackage	Haml.renderers
 */

/**
 * Phamlp_Haml_Renderer_Expanded class.
 * Blocks are on single lines and content indented.
 * @package			PHamlP
 * @subpackage	Haml.renderers
 */
class Phamlp_Haml_Renderer_Expanded extends Phamlp_Haml_Renderer {
	/**
	 * Renders the opening tag of an element
	 */
	public function renderOpeningTag($node) {
	  return parent::renderOpeningTag($node) .
	  	($node->whitespaceControl['inner'] ? '' :
	  	($node->isSelfClosing && $node->whitespaceControl['outer'] ? '' : "\n"));
	}

	/**
	 * Renders the closing tag of an element
	 */
	public function renderClosingTag($node) {
	  return ($node->isSelfClosing ? '' : parent::renderClosingTag($node) .
	  	($node->whitespaceControl['outer'] ? '' : "\n"));
	}

	/**
	 * Renders content.
	 * @param Phamlp_Haml_Tree_Node the node being rendered
	 * @return string the rendered content
	 */
	public function renderContent($node) {
	  return self::INDENT . parent::renderContent($node) . "\n";
	}

	/**
	 * Renders the start of a code block
	 */
	public function renderStartCodeBlock($node) {
		return parent::renderStartCodeBlock($node) . "\n";
	}

	/**
	 * Renders the end of a code block
	 */
	public function renderEndCodeBlock($node) {
		return parent::renderEndCodeBlock($node) . "\n";
	}
}