<?php
/**
 * Sass exception.
 * @author			Chris Yates <chris.l.yates@gmail.com>
 * @copyright 	Copyright (c) 2010 PBM Web Development
 * @license			http://phamlp.googlecode.com/files/license.txt
 * @package			PHamlP
 * @subpackage	Sass
 */

/**
 * Sass exception class.
 * @package			PHamlP
 * @subpackage	Sass
 */
class Phamlp_Sass_Exception extends Exception {
	/**
	 * Sass Exception.
	 * @param string Exception message
	 * @param array parameters to be applied to the message using <code>strtr</code>.
	 * @param object object with source code and meta data
	 */
	public function __construct($message, $params = array(), $object = null) {
		parent::__construct('sass:: ' . $message . ' ' . print_r($params, true) . (is_object($object) ? ": {$object->filename}::{$object->line}\nSource: {$object->source}" : ''));
	}
}