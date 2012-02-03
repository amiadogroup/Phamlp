<?php
/* SVN FILE: $Id$ */
/**
 * Phamlp_Haml_Tree_Node_CodeBlock class file.
 * @author			Chris Yates <chris.l.yates@gmail.com>
 * @copyright 	Copyright (c) 2010 PBM Web Development
 * @license			http://phamlp.googlecode.com/files/license.txt
 * @package			PHamlP
 * @subpackage	Haml.tree
 */

require_once('Phamlp_Haml_Tree_Node_Root.php');
require_once('Phamlp_Haml_Tree_NodeExceptions.php');

/**
 * Phamlp_Haml_Tree_Node_CodeBlock class.
 * Represents a code block - if, elseif, else, foreach, do, and while.
 * @package			PHamlP
 * @subpackage	Haml.tree
 */
class Phamlp_Haml_Tree_Node_CodeBlock extends Phamlp_Haml_Tree_Node {
	/**
	 * @var Phamlp_Haml_Tree_Node_CodeBlock else nodes for if statements
	 */
	public $else;
	/**
	 * @var string while clause for do-while loops
	 */
	public $doWhile;

	/**
	 * Adds an "else" statement to this node.
	 * @param Phamlp_Sass_Tree_Node_If "else" statement node to add
	 * @return Phamlp_Sass_Tree_Node_If this node
	 */
	public function addElse($node) {
	  if (is_null($this->else)) {
	  	$node->root			= $this->root;
	  	$node->parent		= $this->parent;
			$this->else			= $node;
	  }
	  else {
			$this->else->addElse($node);
	  }
	  return $this;
	}

	public function render() {
		$output = $this->renderer->renderStartCodeBlock($this);
		foreach ($this->children as $child) {
			$output .= $child->render();
		} // foreach
		$output .= (empty($this->else) ?
			$this->renderer->renderEndCodeBlock($this) : $this->else->render());

	  return $this->debug($output);
	}
}