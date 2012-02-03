<?php
/* SVN FILE: $Id$ */
/**
 * Phamlp_Haml_Tree_Node_Filter class file.
 * @author			Chris Yates <chris.l.yates@gmail.com>
 * @copyright 	Copyright (c) 2010 PBM Web Development
 * @license			http://phamlp.googlecode.com/files/license.txt
 * @package			PHamlP
 * @subpackage	Haml.tree
 */

/**
 * Phamlp_Haml_Tree_Node_Filter class.
 * Represent a filter in the Haml source.
 * The filter is run on the output from child nodes when the node is rendered.
 * @package			PHamlP
 * @subpackage	Haml.tree
 */
class Phamlp_Haml_Tree_Node_Filter extends Phamlp_Haml_Tree_Node {
	/**
	 * @var HamlBaseFilter the filter to run
	 */
	private $filter;

	/**
	 * Phamlp_Haml_Tree_Node_Filter constructor.
	 * Sets the filter.
	 * @param HamlBaseFilter the filter to run
	 * @return Phamlp_Haml_Tree_Node_Filter
	 */
	public function __construct($filter, $parent) {
	  $this->filter = $filter;	  
	  $this->parent = $parent;
	  $this->root = $parent->root;
	  $parent->children[] = $this;
	}

	/**
	* Render this node.
	* The filter is run on the content of child nodes before being returned.
	* @return string the rendered node
	*/
	public function render() {
		$output = '';
		foreach ($this->children as $child) {
			$output .= $child->getContent();
		} // foreach
		return $this->debug($this->filter->run($output));
	}
}