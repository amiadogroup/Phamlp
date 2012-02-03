<?php
/* SVN FILE: $Id$ */
/**
 * Phamlp_Haml_Renderer class file.
 * @author			Chris Yates <chris.l.yates@gmail.com>
 * @copyright 	Copyright (c) 2010 PBM Web Development
 * @license			http://phamlp.googlecode.com/files/license.txt
 * @package			PHamlP
 * @subpackage	Haml.renderers
 */

/**
 * Phamlp_Haml_Renderer class.
 * Provides the most common version of each method. Child classs override
 * methods to provide style specific rendering.
 * @package			PHamlP
 * @subpackage	Haml.renderers
 */
class Phamlp_Haml_Renderer {
	/**#@+
	 * Output Styles
	 */
	const STYLE_COMPRESSED = 'compressed';
	const STYLE_COMPACT 	 = 'compact';
	const STYLE_EXPANDED 	 = 'expanded';
	const STYLE_NESTED 		 = 'nested';
	/**#@-*/

	const INDENT = '  ';

	private $format;
	private $attrWrapper;
	private $minimizedAttributes;

	/**
	 * Returns the renderer for the required render style.
	 * @param string render style
	 * @return Phamlp_Haml_Renderer
	 */
	static public function getRenderer($style, $options) {
		switch ($style) {
			case self::STYLE_COMPACT:
		  	return new Phamlp_Haml_Renderer_Compact($options);
			case self::STYLE_COMPRESSED:
		  	return new Phamlp_Haml_Renderer_Compressed($options);
			case self::STYLE_EXPANDED:
		  	return new Phamlp_Haml_Renderer_Expanded($options);
			case self::STYLE_NESTED:
		  	return new Phamlp_Haml_Renderer_Nested($options);
		} // switch
	}

	public function __construct($options) {
		foreach ($options as $name => $value) {
			$this->$name = $value;
		} // foreach
	}

	/**
	 * Renders element attributes
	 */
	private function renderAttributes($attributes) {
		$output = '';
		foreach ($attributes as $name => $value) {
			if (is_integer($name)) {  // attribute function
						$output .= " $value";
			}
			elseif ($name == $value &&
				($this->format === 'html4' || $this->format === 'html5')) {
						$output .= " $name";
			}
			else {
				$output .= " $name={$this->attrWrapper}$value{$this->attrWrapper}";
			}
		}
		return $output;
	}

	/**
	 * Renders the opening tag of an element
	 */
	public function renderOpeningTag($node) {
		$output  = "<{$node->content}";
		$output .= $this->renderAttributes($node->attributes);
		$output .= ($node->isSelfClosing ? ' /' : '') . '>';
	  return $output;
	}

	/**
	 * Renders the closing tag of an element
	 */
	public function renderClosingTag($node) {
		return ($node->isSelfClosing ? '' : "</{$node->content}>");
	}

	/**
	 * Renders the opening of a comment
	 */
	public function renderOpenComment($node) {
		return ($node->isConditional ? "\n\n" : '') . "<!--{$node->content}" . ($node->isConditional ? ">\n" : ' ');
	}

	/**
	 * Renders the closing of a comment
	 */
	public function renderCloseComment($node) {
		return ($node->isConditional ? "\n<![endif]" : ' ') .  '-->' . ($node->isConditional ? "\n" : '');
	}

	/**
	 * Renders the start of a code block
	 */
	public function renderStartCodeBlock($node) {
		return $this->renderContent($node);
	}

	/**
	 * Renders the end of a code block
	 */
	public function renderEndCodeBlock($node) {
		return '<?php }' . (!empty($node->doWhile) ? " {$node->doWhile}" : '') . ' ?>';
	}

	/**
	 * Renders content.
	 * @param HamlNode the node being rendered
	 * @return string the rendered content
	 */
	public function renderContent($node) {
	  return $node->content;
	}
}