<?php
/**
 * Phamlp_Sass_Tree_Node_Root class file.
 * @author			Chris Yates <chris.l.yates@gmail.com>
 * @copyright 	Copyright (c) 2010 PBM Web Development
 * @license			http://phamlp.googlecode.com/files/license.txt
 * @package			PHamlP
 * @subpackage	Sass.tree
 */

/**
 * Phamlp_Sass_Tree_Node_Root class.
 * Also the root node of a document.
 * @package			PHamlP
 * @subpackage	Sass.tree
 */
class Phamlp_Sass_Tree_Node_Root extends Phamlp_Sass_Tree_Node {
	/**
	 * @var Phamlp_Sass_Script_Parser SassScript parser
	 */
	protected $script;
	/**
	 * @var Phamlp_Sass_Renderer the renderer for this node
	 */
	protected $renderer;
	/**
	 * @var Phamlp_Sass_Parser
	 */
	protected $parser;
	/**
	 * @var array extenders for this tree in the form extendee=>extender
	 */
	protected $extenders = array();

	/**
	 * Root Phamlp_Sass_Tree_Node constructor.
	 * @param Phamlp_Sass_Parser Sass parser
	 * @return Phamlp_Sass_Tree_Node
	 */
	public function __construct($parser) { 
		parent::__construct((object) array(
			'source' => '',
			'level' => -1,
			'filename' => $parser->filename,
			'line' => 0,
		));
		$this->parser = $parser;
		$this->script = new Phamlp_Sass_Script_Parser();
		$this->renderer = Phamlp_Sass_Renderer::getRenderer($parser->style);
		$this->root = $this;
	}

	/**
	 * Parses this node and its children into the render tree.
	 * Dynamic nodes are evaluated, files imported, etc.
	 * Only static nodes for rendering are in the resulting tree.
	 * @param Phamlp_Sass_Tree_Context the context in which this node is parsed
	 * @return Phamlp_Sass_Tree_Node root node of the render tree
	 */
	public function parse($context) {
		$node = clone $this;
		$node->children = $this->parseChildren($context);
		return $node;
	}

	/**
	 * Render this node.
	 * @return string the rendered node
	 */
	public function render() {
		$node = $this->parse(new Phamlp_Sass_Tree_Context());
		$output = '';
		foreach ($node->children as $child) {
			$output .= $child->render();
		} // foreach
		return $output;
	}
	
	public function extend($extendee, $selectors) {
		$this->extenders[$extendee] = (isset($this->extenders[$extendee])
			? array_merge($this->extenders[$extendee], $selectors) : $selectors);		
	}
	
	public function getExtenders() {
		return $this->extenders;  
	} 

	/**
	 * Returns a value indicating if the line represents this type of node.
	 * Child classes must override this method.
	 * @throws Phamlp_Sass_Tree_NodeException if not overriden
	 */
	public static function isa($line) {
		throw new Phamlp_Sass_Tree_NodeException('Child classes must override this method');
	}
}
