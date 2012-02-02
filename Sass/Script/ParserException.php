<?php
/* SVN FILE: $Id$ */
/**
 * SassScript Parser exception class file.
 * @author			Chris Yates <chris.l.yates@gmail.com>
 * @copyright 	Copyright (c) 2010 PBM Web Development
 * @license			http://phamlp.googlecode.com/files/license.txt
 * @package			PHamlP
 * @subpackage	Sass.script
 */

/**
 * Phamlp_Sass_Script_ParserException class.
 * @package			PHamlP
 * @subpackage	Sass.script
 */
class Phamlp_Sass_Script_ParserException extends Phamlp_Sass_Exception {}

/**
 * Phamlp_Sass_Script_LexerException class.
 * @package			PHamlP
 * @subpackage	Sass.script
 */
class Phamlp_Sass_Script_LexerException extends Phamlp_Sass_Script_ParserException {}

/**
 * Phamlp_Sass_Script_OperationException class.
 * @package			PHamlP
 * @subpackage	Sass.script
 */
class Phamlp_Sass_Script_OperationException extends Phamlp_Sass_Script_ParserException {}

/**
 * Phamlp_Sass_Script_FunctionException class.
 * @package			PHamlP
 * @subpackage	Sass.script
 */
class Phamlp_Sass_Script_FunctionException extends Phamlp_Sass_Script_ParserException {}