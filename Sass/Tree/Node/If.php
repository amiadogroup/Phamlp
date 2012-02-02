<?php
/* SVN FILE: $Id$ */
/**
 * Phamlp_Sass_Tree_Node_If class file.
 * @author			Chris Yates <chris.l.yates@gmail.com>
 * @copyright 	Copyright (c) 2010 PBM Web Development
 * @license			http://phamlp.googlecode.com/files/license.txt
 * @package			PHamlP
 * @subpackage	Sass.tree
 */

/**
 * Phamlp_Sass_Tree_Node_If class.
 * Represents Sass If, Else If and Else statements.
 * Else If and Else statement nodes are chained below the If statement node.
 * @package			PHamlP
 * @subpackage	Sass.tree
 */
class Phamlp_Sass_Tree_Node_If extends Phamlp_Sass_Tree_Node {
	const MATCH_IF = '/^@if\s+(.+)$/i';
	const MATCH_ELSE = '/@else(\s+if\s+(.+))?/i';
	const IF_EXPRESSION = 1;
	const ELSE_IF = 1;
	const ELSE_EXPRESSION = 2;
	/**
	 * @var Phamlp_Sass_Tree_Node_If the next else node.
	 */
	private $else;
	/**
	 * @var string expression to evaluate
	 */
	private $expression;

	/**
	 * Phamlp_Sass_Tree_Node_If constructor.
	 * @param object source token
	 * @param boolean true for an "if" node, false for an "else if | else" node
	 * @return Phamlp_Sass_Tree_Node_If
	 */
	public function __construct($token, $if=true) {
		parent::__construct($token);
		if ($if) {
			preg_match(self::MATCH_IF, $token->source, $matches);
			$this->expression = $matches[Phamlp_Sass_Tree_Node_If::IF_EXPRESSION];
		}
		else {
			preg_match(self::MATCH_ELSE, $token->source, $matches);
			$this->expression = (sizeof($matches)==1 ? null : $matches[Phamlp_Sass_Tree_Node_If::ELSE_EXPRESSION]);
		}
	}

	/**
	 * Adds an "else" statement to this node.
	 * @param Phamlp_Sass_Tree_Node_If "else" statement node to add
	 * @return Phamlp_Sass_Tree_Node_If this node
	 */
	public function addElse($node) {
	  if (is_null($this->else)) {
	  	$node->parent	= $this->parent;
	  	$node->root		= $this->root;
			$this->else		= $node;
	  }
	  else {
			$this->else->addElse($node);
	  }
	  return $this;
	}

	/**
	 * Parse this node.
	 * @param Phamlp_Sass_Tree_Context the context in which this node is parsed
	 * @return array parsed child nodes
	 */
	public function parse($context) {
		if ($this->isElse() || $this->evaluate($this->expression, $context)->toBoolean()) {
			$children = $this->parseChildren($context);
		}
		elseif (!empty($this->else)) {
			$children = $this->else->parse($context);
		}
		else {
			$children = array();
		}
		return $children;
	}

	/**
	 * Returns a value indicating if this node is an "else" node.
	 * @return true if this node is an "else" node, false if this node is an "if"
	 * or "else if" node
	 */
	private function isElse() {
	  return empty($this->expression);
	}
}