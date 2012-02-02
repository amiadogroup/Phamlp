<?php
/* SVN FILE: $Id$ */
/**
 * Phamlp_Sass_Renderer_Nested class file.
 * @author			Chris Yates <chris.l.yates@gmail.com>
 * @copyright 	Copyright (c) 2010 PBM Web Development
 * @license			http://phamlp.googlecode.com/files/license.txt
 * @package			PHamlP
 * @subpackage	Sass.renderers
 */

/**
 * Phamlp_Sass_Renderer_Nested class.
 * Nested style is the default Sass style, because it reflects the structure of
 * the document in much the same way Sass does. Each rule is indented based on
 * how deeply it's nested. Each property has its own line and is indented
 * within the rule. 
 * @package			PHamlP
 * @subpackage	Sass.renderers
 */
class Phamlp_Sass_Renderer_Nested extends Phamlp_Sass_Renderer_Expanded {	
	/**
	 * Renders the brace at the end of the rule
	 * @return string the brace between the rule and its properties
	 */
	protected function end() {
	  return " }\n";
	}
	
	/**
	 * Returns the indent string for the node
	 * @param Phamlp_Sass_Tree_Node the node being rendered
	 * @return string the indent string for this Phamlp_Sass_Tree_Node
	 */
	protected function getIndent($node) {
		return str_repeat(self::INDENT, $node->level);
	}

	/**
	 * Renders a directive.
	 * @param Phamlp_Sass_Tree_Node the node being rendered
	 * @param array properties of the directive
	 * @return string the rendered directive
	 */
	public function renderDirective($node, $properties) {
		$directive = $this->getIndent($node) . $node->directive . $this->between() . $this->renderProperties($properties);
		return preg_replace('/(.*})\n$/', '\1', $directive) . $this->end();
	}

	/**
	 * Renders rule selectors.
	 * @param Phamlp_Sass_Tree_Node the node being rendered
	 * @return string the rendered selectors
	 */
	protected function renderSelectors($node) {
		$indent = $this->getIndent($node);
	  return $indent.join(",\n$indent", $node->selectors);
	}
}
