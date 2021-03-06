<?php
/**
 * Haml exception.
 * @author			Chris Yates <chris.l.yates@gmail.com>
 * @copyright 	Copyright (c) 2010 PBM Web Development
 * @license			http://phamlp.googlecode.com/files/license.txt
 * @package			PHamlP
 * @subpackage	Haml
 */

/**
 * Haml exception class.
 * @package			PHamlP
 * @subpackage	Haml
 */
class Haml_Exception extends Phamlp_Exception {
	/**
	 * Haml Exception. 
	 * @param string Exception message
	 * @param array parameters to be applied to the message using <code>strtr</code>.
	 * @param object object with source code and meta data
	 */
	public function __construct($message, $params = array(), $object = null) {
		parent::__construct('Haml', $message, $params, $object);
	}
}