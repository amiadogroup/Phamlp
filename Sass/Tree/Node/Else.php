<?php
/* SVN FILE: $Id: Phamlp_Sass_Tree_Node_If.php 49 2010-04-04 10:51:24Z chris.l.yates $ */
/**
 * Phamlp_Sass_Tree_Node_Else class file.
 * @author			Chris Yates <chris.l.yates@gmail.com>
 * @copyright 	Copyright (c) 2010 PBM Web Development
 * @license			http://phamlp.googlecode.com/files/license.txt
 * @package			PHamlP
 * @subpackage	Sass.tree
 */

/**
 * Phamlp_Sass_Tree_Node_Else class.
 * Represents Sass Else If and Else statements.
 * Else If and Else statement nodes are chained below the If statement node.
 * @package			PHamlP
 * @subpackage	Sass.tree
 */
class Phamlp_Sass_Tree_Node_Else extends Phamlp_Sass_Tree_Node_If {
	/**
	 * Phamlp_Sass_Tree_Node_Else constructor.
	 * @param object source token
	 * @return Phamlp_Sass_Tree_Node_Else
	 */
	public function __construct($token) {
		parent::__construct($token, false);
	}
}