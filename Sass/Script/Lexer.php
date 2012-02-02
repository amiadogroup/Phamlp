<?php
/* SVN FILE: $Id$ */
/**
 * Phamlp_Sass_Script_Lexer class file.
 * @author			Chris Yates <chris.l.yates@gmail.com>
 * @copyright 	Copyright (c) 2010 PBM Web Development
 * @license			http://phamlp.googlecode.com/files/license.txt
 * @package			PHamlP
 * @subpackage	Sass.script
 */

/**
 * Phamlp_Sass_Script_Lexer class.
 * Lexes SassSCript into tokens for the parser.
 * 
 * Implements a {@link http://en.wikipedia.org/wiki/Shunting-yard_algorithm Shunting-yard algorithm} to provide {@link http://en.wikipedia.org/wiki/Reverse_Polish_notation Reverse Polish notation} output.
 * @package			PHamlP
 * @subpackage	Sass.script
 */
class Phamlp_Sass_Script_Lexer {
	const MATCH_WHITESPACE = '/^\s+/';

	/**
	 * @var Phamlp_Sass_Script_Parser the parser object
	 */
	private $parser;

	/**
	* Phamlp_Sass_Script_Lexer constructor.
	* @return Phamlp_Sass_Script_Lexer
	*/
	public function __construct($parser) {
		$this->parser = $parser;
	}
	
	/**
	 * Lex an expression into SassScript tokens.
	 * @param string expression to lex
	 * @param Phamlp_Sass_Tree_Context the context in which the expression is lexed
	 * @return array tokens
	 */
	public function lex($string, $context) {
		$tokens = array();
		while ($string !== false) {
			if (($match = $this->isWhitespace($string)) !== false) {
				$tokens[] = null;
			}
			elseif (($match = Phamlp_Sass_Script_Function::isa($string)) !== false) {
				preg_match(Phamlp_Sass_Script_Function::MATCH_FUNC, $match, $matches);
				
				$args = array();
				foreach (Phamlp_Sass_Script_Function::extractArgs($matches[Phamlp_Sass_Script_Function::ARGS])
						as $expression) {
					$args[] = $this->parser->evaluate($expression, $context);
				}
				
				$tokens[] = new Phamlp_Sass_Script_Function(
						$matches[Phamlp_Sass_Script_Function::NAME], $args);
			}
			elseif (($match = Phamlp_Sass_Script_Literal_String::isa($string)) !== false) {
				$tokens[] = new Phamlp_Sass_Script_Literal_String($match);
			}
			elseif (($match = Phamlp_Sass_Script_Literal_Boolean::isa($string)) !== false) {
				$tokens[] = new Phamlp_Sass_Script_Literal_Boolean($match);
			}
			elseif (($match = Phamlp_Sass_Script_Literal_Colour::isa($string)) !== false) {
				$tokens[] = new Phamlp_Sass_Script_Literal_Colour($match);
			}
			elseif (($match = Phamlp_Sass_Script_Literal_Number::isa($string)) !== false) {				
				$tokens[] = new Phamlp_Sass_Script_Literal_Number($match);
			}
			elseif (($match = Phamlp_Sass_Script_Operation::isa($string)) !== false) {
				$tokens[] = new Phamlp_Sass_Script_Operation($match);
			}
			elseif (($match = Phamlp_Sass_Script_Variable::isa($string)) !== false) {
				$tokens[] = new Phamlp_Sass_Script_Variable($match);
			}
			else {
				$_string = $string;
				$match = '';
				while (strlen($_string) && !$this->isWhitespace($_string)) {
					foreach (Phamlp_Sass_Script_Operation::$inStrOperators as $operator) {
						if (substr($_string, 0, strlen($operator)) == $operator) {
							break 2;
						}
					}
					$match .= $_string[0];
					$_string = substr($_string, 1);			
				}
				$tokens[] = new Phamlp_Sass_Script_Literal_String($match);
			}			
			$string = substr($string, strlen($match));
		}
		return $tokens; 
	}

	/**
	 * Returns a value indicating if a token of this type can be matched at
	 * the start of the subject string.
	 * @param string the subject string
	 * @return mixed match at the start of the string or false if no match
	 */
	public function isWhitespace($subject) {
		return (preg_match(self::MATCH_WHITESPACE, $subject, $matches) ? $matches[0] : false);
	}
}
