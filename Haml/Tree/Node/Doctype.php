<?php
/* SVN FILE: $Id$ */
/**
 * Phamlp_Haml_Tree_Node_Doctype class file.
 * @author			Chris Yates <chris.l.yates@gmail.com>
 * @copyright 	Copyright (c) 2010 PBM Web Development
 * @license			http://phamlp.googlecode.com/files/license.txt
 * @package			PHamlP
 * @subpackage	Haml.tree
 */

/**
 * Phamlp_Haml_Tree_Node_Doctype class.
 * Represents a Doctype.
 * Doctypes are always rendered on a single line with a newline.
 * @package			PHamlP
 * @subpackage	Haml.tree
 */
class Phamlp_Haml_Tree_Node_Doctype extends Phamlp_Haml_Tree_Node {
	/**
	 * Render this node.
	 * @return string the rendered node
	 */
	public function render() {
		return $this->debug($this->content . "\n");
	}
}