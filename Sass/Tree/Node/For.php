<?php
/* SVN FILE: $Id$ */
/**
 * Phamlp_Sass_Tree_Node_For class file.
 * This is an enhanced version of the standard SassScript @for loop that adds
 * an optional step clause. Step must evaluate to a positive integer.
 * The syntax is:
 * <pre>@for <var> from <start> to|through <end>[ step <step>]</pre>.
 *
 * <start> can be less or greater than <end>.
 * If the step clause is ommitted the <step> = 1.
 * <var> is available to the rest of the script following evaluation
 * and has the value that terminated the loop.
 * 
 * @author			Chris Yates <chris.l.yates@gmail.com>
 * @copyright 	Copyright (c) 2010 PBM Web Development
 * @license			http://phamlp.googlecode.com/files/license.txt
 * @package			PHamlP
 * @subpackage	Sass.tree 
 */

/**
 * Phamlp_Sass_Tree_Node_For class.
 * Represents a Sass @for loop.
 * @package			PHamlP
 * @subpackage	Sass.tree
 */
class Phamlp_Sass_Tree_Node_For extends Phamlp_Sass_Tree_Node {
	const MATCH = '/@for\s+[!\$](\w+)\s+from\s+(.+?)\s+(through|to)\s+(.+?)(?:\s+step\s+(.+))?$/i';

	const VARIABLE = 1;
	const FROM = 2;
	const INCLUSIVE = 3;
	const TO = 4;
	const STEP = 5;
	const IS_INCLUSIVE = 'through';

	/**
	 * @var string variable name for the loop
	 */
	private $variable;
	/**
	 * @var string expression that provides the loop start value
	 */
	private $from;
	/**
	 * @var string expression that provides the loop end value
	 */
	private $to;
	/**
	 * @var boolean whether the loop end value is inclusive
	 */
	private $inclusive;
	/**
	 * @var string expression that provides the amount by which the loop variable
	 * changes on each iteration
	 */
	private $step;

	/**
	 * Phamlp_Sass_Tree_Node_For constructor.
	 * @param object source token
	 * @return Phamlp_Sass_Tree_Node_For
	 */
	public function __construct($token) {
		parent::__construct($token);
		if (!preg_match(self::MATCH, $token->source, $matches)) {
			throw new Phamlp_Sass_Tree_Node_ForException('Invalid {what}', array('{what}'=>'@for directive'), $this);
		}
		$this->variable  = $matches[self::VARIABLE];
		$this->from			 = $matches[self::FROM];
		$this->to				 = $matches[self::TO];
		$this->inclusive = ($matches[self::INCLUSIVE] === Phamlp_Sass_Tree_Node_For::IS_INCLUSIVE);
		$this->step			 = (empty($matches[self::STEP]) ? 1 : $matches[self::STEP]);
	}

	/**
	 * Parse this node.
	 * @param Phamlp_Sass_Tree_Context the context in which this node is parsed
	 * @return array parsed child nodes
	 */
	public function parse($context) {
		$children = array();
		$from = (float)$this->evaluate($this->from, $context)->value;
		$to		= (float)$this->evaluate($this->to,		$context)->value;
		$step = (float)$this->evaluate($this->step, $context)->value * ($to > $from ? 1 : -1);

		if ($this->inclusive) {
			$to += ($from < $to ? 1 : -1);
		}

		$context = new Phamlp_Sass_Tree_Context($context);
		for ($i = $from; ($from < $to ? $i < $to : $i > $to); $i = $i + $step) {
			$context->setVariable($this->variable, new Phamlp_Sass_Script_Literal_Number($i));
			$children = array_merge($children, $this->parseChildren($context));
		}
		return $children;
	}
}