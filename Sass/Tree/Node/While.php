<?php
/* SVN FILE: $Id$ */
/**
 * Phamlp_Sass_Tree_Node_While class file.
 * @author			Chris Yates <chris.l.yates@gmail.com>
 * @copyright 	Copyright (c) 2010 PBM Web Development
 * @license			http://phamlp.googlecode.com/files/license.txt
 * @package			PHamlP
 * @subpackage	Sass.tree
 */

/**
 * Phamlp_Sass_Tree_Node_While class.
 * Represents a Sass @while loop and a Sass @do loop.
 * @package			PHamlP
 * @subpackage	Sass.tree
 */
class Phamlp_Sass_Tree_Node_While extends Phamlp_Sass_Tree_Node {
	const MATCH = '/^@(do|while)\s+(.+)$/i';
	const LOOP = 1;
	const EXPRESSION = 2;
	const IS_DO = 'do';
	/**
	 * @var boolean whether this is a do/while.
	 * A do/while loop is guarenteed to run at least once.
	 */
	private $isDo;
	/**
	 * @var string expression to evaluate
	 */
	private $expression;

	/**
	 * Phamlp_Sass_Tree_Node_While constructor.
	 * @param object source token
	 * @return Phamlp_Sass_Tree_Node_While
	 */
	public function __construct($token) {
		parent::__construct($token);
		preg_match(self::MATCH, $token->source, $matches);
		$this->expression = $matches[self::EXPRESSION];
		$this->isDo = ($matches[self::LOOP] === Phamlp_Sass_Tree_Node_While::IS_DO);
	}

	/**
	 * Parse this node.
	 * @param Phamlp_Sass_Tree_Context the context in which this node is parsed
	 * @return array the parsed child nodes
	 */
	public function parse($context) {
		$children = array();
		if ($this->isDo) {
			do {
				$children = array_merge($children, $this->parseChildren($context));
			} while ($this->evaluate($this->expression, $context)->toBoolean());
		}
		else {
			while ($this->evaluate($this->expression, $context)->toBoolean()) {
				$children = array_merge($children, $this->parseChildren($context));
			}
		}
		return $children;
	}
}