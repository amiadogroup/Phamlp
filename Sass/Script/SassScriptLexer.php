<?php
/* SVN FILE: $Id$ */
/**
 * SassScriptLexer class file.
 * @author			Chris Yates <chris.l.yates@gmail.com>
 * @copyright 	Copyright (c) 2010 PBM Web Development
 * @license			http://phamlp.googlecode.com/files/license.txt
 * @package			PHamlP
 * @subpackage	Sass.script
 */

require_once('literals/SassBoolean.php');
require_once('literals/SassColour.php');
require_once('literals/SassNumber.php');
require_once('literals/SassString.php');
require_once('SassScriptOperation.php');
require_once('SassScriptVariable.php');

/**
 * SassScriptLexer class.
 * Lexes SassSCript into tokens for the parser.
 * 
 * Implements a {@link http://en.wikipedia.org/wiki/Shunting-yard_algorithm Shunting-yard algorithm} to provide {@link http://en.wikipedia.org/wiki/Reverse_Polish_notation Reverse Polish notation} output.
 * @package			PHamlP
 * @subpackage	Sass.script
 */
class SassScriptLexer {
	const MATCH_WHITESPACE = '/^\s+/';

	/**
	 * @var Phamlp_Sass_Script_Parser the parser object
	 */
	private $parser;

	/**
	* SassScriptLexer constructor.
	* @return SassScriptLexer
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
			elseif (($match = SassString::isa($string)) !== false) {
				$tokens[] = new SassString($match);
			}
			elseif (($match = SassBoolean::isa($string)) !== false) {
				$tokens[] = new SassBoolean($match);
			}
			elseif (($match = SassColour::isa($string)) !== false) {
				$tokens[] = new SassColour($match);
			}
			elseif (($match = SassNumber::isa($string)) !== false) {				
				$tokens[] = new SassNumber($match);
			}
			elseif (($match = SassScriptOperation::isa($string)) !== false) {
				$tokens[] = new SassScriptOperation($match);
			}
			elseif (($match = SassScriptVariable::isa($string)) !== false) {
				$tokens[] = new SassScriptVariable($match);
			}
			else {
				$_string = $string;
				$match = '';
				while (strlen($_string) && !$this->isWhitespace($_string)) {
					foreach (SassScriptOperation::$inStrOperators as $operator) {
						if (substr($_string, 0, strlen($operator)) == $operator) {
							break 2;
						}
					}
					$match .= $_string[0];
					$_string = substr($_string, 1);			
				}
				$tokens[] = new SassString($match);
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
