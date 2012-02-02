<?php
/* SVN FILE: $Id$ */
/**
 * Phamlp_Sass_Tree_Node_Directive class file.
 * @author			Chris Yates <chris.l.yates@gmail.com>
 * @copyright 	Copyright (c) 2010 PBM Web Development
 * @license			http://phamlp.googlecode.com/files/license.txt
 * @package			PHamlP
 * @subpackage	Sass.tree
 */

/**
 * Phamlp_Sass_Tree_Node_Directive class.
 * Represents a CSS directive.
 * @package			PHamlP
 * @subpackage	Sass.tree
 */
class Phamlp_Sass_Tree_Node_Directive extends Phamlp_Sass_Tree_Node {
	const NODE_IDENTIFIER = '@';
	const MATCH = '/^(@\w+)/';

	/**
	 * Phamlp_Sass_Tree_Node_Directive.
	 * @param object source token
	 * @return Phamlp_Sass_Tree_Node_Directive
	 */
	public function __construct($token) {
		parent::__construct($token);
	}
	
	protected function getDirective() {
		return self::extractDirective($this->token);
	}

	/**
	 * Parse this node.
	 * @param Phamlp_Sass_Tree_Context the context in which this node is parsed
	 * @return array the parsed node
	 */
	public function parse($context) {
		$this->children = $this->parseChildren($context);
		return array($this);
	}

	/**
	 * Render this node.
	 * @return string the rendered node
	 */
	public function render() {
		$properties = array();
		foreach ($this->children as $child) {
			$properties[] = $child->render();
		} // foreach

		return $this->renderer->renderDirective($this, $properties);
	}

	/**
	 * Returns a value indicating if the token represents this type of node.
	 * @param object token
	 * @return boolean true if the token represents this type of node, false if not
	 */
	public static function isa($token) {
		return $token->source[0] === self::NODE_IDENTIFIER;
	}

	/**
	 * Returns the directive
	 * @param object token
	 * @return string the directive
	 */
	public static function extractDirective($token) {
		preg_match(self::MATCH, $token->source, $matches);
	  return strtolower($matches[1]);
	}
}