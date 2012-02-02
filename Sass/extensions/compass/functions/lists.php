<?php
/* SVN FILE: $Id: Phamlp_Sass_Script_Literal_Boolean.php 49 2010-04-04 10:51:24Z chris.l.yates $ */
/**
 * Compass extension SassScript lists functions class file.
 * @author			Chris Yates <chris.l.yates@gmail.com>
 * @copyright 	Copyright (c) 2010 PBM Web Development
 * @license			http://phamlp.googlecode.com/files/license.txt
 * @package			PHamlP
 * @subpackage	Sass.extensions.compass.functions
 */
 
/**
 * Compass extension SassScript lists functions class.
 * A collection of functions for use in SassSCript.
 * @package			PHamlP
 * @subpackage	Sass.extensions.compass.functions
 */
class SassExtentionsCompassFunctionsLists {
	const SPACE_SEPARATOR = '/\s+/';
	
	# Return the first value from a space separated list.
	public static function first_value_of($list) {
		if ($list instanceof Phamlp_Sass_Script_Literal_String) {
			$items = preg_split(self::SPACE_SEPARATOR, $list->value);
			return new Phamlp_Sass_Script_Literal_String($items[0]);
		}
		else return $list;
	}
	
	# Return the nth value from a space separated list.
	public static function nth_value_of($list, $n) {
		if ($list instanceof Phamlp_Sass_Script_Literal_String) {
			$items = preg_split(self::SPACE_SEPARATOR, $list->value);
			return new Phamlp_Sass_Script_Literal_String($items[$n->toInt()-1]);
		}
		else return $list;
	}
	
	# Return the last value from a space separated list.
	public static function last_value_of($list) {
		if ($list instanceof Phamlp_Sass_Script_Literal_String) {
			$items = array_reverse(preg_split(self::SPACE_SEPARATOR, $list->value));
			return new Phamlp_Sass_Script_Literal_String($items[0]);
		}
		else return $list;
	}
}