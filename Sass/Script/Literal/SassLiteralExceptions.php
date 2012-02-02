<?php
/* SVN FILE: $Id$ */
/**
 * Sass literal exception classes.
 * @author			Chris Yates <chris.l.yates@gmail.com>
 * @copyright 	Copyright (c) 2010 PBM Web Development
 * @license			http://phamlp.googlecode.com/files/license.txt
 * @package			PHamlP
 * @subpackage	Sass.script.literals
 */

/**
 * Sass literal exception.
 * @package			PHamlP
 * @subpackage	Sass.script.literals
 */
class Phamlp_Sass_Script_LiteralException extends Phamlp_Sass_Script_ParserException {}

/**
 * Phamlp_Sass_Script_Literal_BooleanException class.
 * @package			PHamlP
 * @subpackage	Sass.script.literals
 */
class Phamlp_Sass_Script_Literal_BooleanException extends Phamlp_Sass_Script_LiteralException {}

/**
 * Phamlp_Sass_Script_Literal_ColourException class.
 * @package			PHamlP
 * @subpackage	Sass.script.literals
 */
class Phamlp_Sass_Script_Literal_ColourException extends Phamlp_Sass_Script_LiteralException {}

/**
 * Phamlp_Sass_Script_Literal_NumberException class.
 * @package			PHamlP
 * @subpackage	Sass.script.literals
 */
class Phamlp_Sass_Script_Literal_NumberException extends Phamlp_Sass_Script_LiteralException {}

/**
 * Phamlp_Sass_Script_Literal_StringException class.
 * @package			PHamlP
 * @subpackage	Sass.script.literals
 */
class Phamlp_Sass_Script_Literal_StringException extends Phamlp_Sass_Script_LiteralException {}
