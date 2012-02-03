<?php
/* SVN FILE: $Id$ */
/**
 * Phamlp_Haml_Tree_Node_Root class file.
 * @author			Chris Yates <chris.l.yates@gmail.com>
 * @copyright 	Copyright (c) 2010 PBM Web Development
 * @license			http://phamlp.googlecode.com/files/license.txt
 * @package			PHamlP
 * @subpackage	Haml.tree
 */

/**
 * Phamlp_Haml_Tree_Node_Root class.
 * Also the root node of a document.
 * @package			PHamlP
 * @subpackage	Haml.tree
 */
class Phamlp_Haml_Tree_Node_Root extends Phamlp_Haml_Tree_Node {
	/**
	 * @var Phamlp_Haml_Renderer the renderer for this node
	 */
	protected $renderer;
	/**
	 * @var array options
	 */
	protected $options;

	/**
	 * Root Phamlp_Haml_Tree_Node constructor.
	 * @param array options for the tree
	 * @return Phamlp_Haml_Tree_Node
	 */
	public function __construct($options) {
		$this->root = $this;
		$this->options = $options;
		$this->renderer = Phamlp_Haml_Renderer::getRenderer($this->options['style'],
			array(
				'format' => $this->options['format'],
				'attrWrapper' => $this->options['attrWrapper'],
				'minimizedAttributes' => $this->options['minimizedAttributes'],
			)
		);
		$this->token = array('level' => -1);
	}

	/**
	 * Render this node.
	 * @return string the rendered node
	 */
	public function render() {
		foreach ($this->children as $child) {
			$this->output .= $child->render();
		} // foreach
		return $this->output;
	}
}
