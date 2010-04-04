<?php
/* SVN FILE: $Id$ */
/**
 * SassScriptParser class file.
 * @author			Chris Yates <chris.l.yates@gmail.com>
 * @copyright 	Copyright &copy; 2010 PBM Web Development
 * @license			http://phamlp.googlecode.com/files/license.txt
 * @package			PHamlP
 * @subpackage	Sass.script
 */

require_once('SassScriptLexer.php');
require_once('SassScriptParserExceptions.php');

/**
 * SassScriptParser class.
 * @package			PHamlP
 * @subpackage	Sass.script
 */
class SassScriptParser {
	/**
	 * @var SassScriptLexer the lexer object
	 */
	private $lexer;

	/**
	* SassScriptParser constructor.
	* @return SassScriptParser
	*/
	public function __construct() {
		$this->lexer = new SassScriptLexer();
	}

	/**
	 * Parse SassScript.
	 * @param string expression to parse
	 * @return string parsed value
	 */
	public function parse($expression) {
		return $this->calculate($this->lexer->lex($expression))->value;
	}

	/**
	 * Calculates a value from the tokens.
	 * @param array tokens in RPN
	 * @return SassLiteral SassLiteral object containing the result
	 */
	private function calculate($tokens) {
		$operands = array();

		while (count($tokens)) {
			$token = array_shift($tokens);
			if ($token instanceof SassScriptFunction) {
				array_push($operands, $token->perform());
			}
			elseif (!$token instanceof SassScriptOperation) {
				array_push($operands, $token);
			}
			else {
				$args = array();
				for ($i = 0, $c = $token->operandCount; $i < $c; $i++) {
					$args[] = array_shift($operands);
				}
				array_push($operands, $token->perform($args));
			}
		}
	  return array_pop($operands);
	}
}