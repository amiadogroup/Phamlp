<?php
/* SVN FILE: $Id$ */
/**
 * SassColor class file.
 * @author			Chris Yates <chris.l.yates@gmail.com>
 * @copyright 	Copyright (c) 2010 PBM Web Development
 * @license			http://phamlp.googlecode.com/files/license.txt
 * @package			PHamlP
 * @subpackage	Sass.script.literals
 */

require_once('SassLiteral.php');
 
/**
 * SassColor class.
 * A SassScript object representing a CSS color.
 * 
 * A color may be represented internally as RGBA, HSLA, or both. It is
 * originally represented as whatever its input is; if it’s created with RGB
 * values, it’s represented as RGBA, and if it’s created with HSL values, it’s
 * represented as HSLA. Once a property is accessed that requires the other
 * representation – for example, SassColor::red for an HSL color – that
 * component is calculated and cached.
 * 
 * The alpha channel of a color is independent of its RGB or HSL representation.
 * It’s always stored, as 1 if nothing else is specified. If only the alpha
 * channel is modified using SassColor::with(), the cached RGB and HSL values
 * are retained.
 * 
 * Color operations are all piecewise, e.g. when adding two colors each
 * component is added independantly; Rr = R1 + R2, Gr = G1 + G2, Br = B1 + B2.
 * 
 * Colors are returned as a named color if possible or #rrggbb.
 *
 * @package			PHamlP
 * @subpackage	Sass.script.literals
 */
class SassColor extends SassLiteral {
	/**@#+
	 * Regexes for matching and extracting colors
	 */
	const MATCH = '/^((#([\da-f]{6}|[\da-f]{3}))|transparent|{CSS_COLORS})/';
	const EXTRACT_3 = '/#([\da-f])([\da-f])([\da-f])/';
	const EXTRACT_6 = '/#([\da-f]{2})([\da-f]{2})([\da-f]{2})/';
	const TRANSPARENT = 'transparent';
	/**@#-*/

	/**@#-*/
	static private $svgColors = array(
		'aliceblue'							=> '#f0f8ff',
		'antiquewhite'					=> '#faebd7',
		'aqua'									=> '#00ffff',
		'aquamarine'						=> '#7fffd4',
		'azure'									=> '#f0ffff',
		'beige'									=> '#f5f5dc',
		'bisque'								=> '#ffe4c4',
		'black'									=> '#000000',
		'blanchedalmond'				=> '#ffebcd',
		'blue'									=> '#0000ff',
		'blueviolet'						=> '#8a2be2',
		'brown'									=> '#a52a2a',
		'burlywood'							=> '#deb887',
		'cadetblue'							=> '#5f9ea0',
		'chartreuse'						=> '#7fff00',
		'chocolate'							=> '#d2691e',
		'coral'									=> '#ff7f50',
		'cornflowerblue'				=> '#6495ed',
		'cornsilk'							=> '#fff8dc',
		'crimson'								=> '#dc143c',
		'cyan'									=> '#00ffff',
		'darkblue'							=> '#00008b',
		'darkcyan'							=> '#008b8b',
		'darkgoldenrod'					=> '#b8860b',
		'darkgray'							=> '#a9a9a9',
		'darkgreen'							=> '#006400',
		'darkgrey'							=> '#a9a9a9',
		'darkkhaki'							=> '#bdb76b',
		'darkmagenta'						=> '#8b008b',
		'darkolivegreen'				=> '#556b2f',
		'darkorange'						=> '#ff8c00',
		'darkorchid'						=> '#9932cc',
		'darkred'								=> '#8b0000',
		'darksalmon'						=> '#e9967a',
		'darkseagreen'					=> '#8fbc8f',
		'darkslateblue'					=> '#483d8b',
		'darkslategray'					=> '#2f4f4f',
		'darkslategrey'					=> '#2f4f4f',
		'darkturquoise'					=> '#00ced1',
		'darkviolet'						=> '#9400d3',
		'deeppink'							=> '#ff1493',
		'deepskyblue'						=> '#00bfff',
		'dimgray'								=> '#696969',
		'dimgrey'								=> '#696969',
		'dodgerblue'						=> '#1e90ff',
		'firebrick'							=> '#b22222',
		'floralwhite'						=> '#fffaf0',
		'forestgreen'						=> '#228b22',
		'fuchsia'								=> '#ff00ff',
		'gainsboro'							=> '#dcdcdc',
		'ghostwhite'						=> '#f8f8ff',
		'gold'									=> '#ffd700',
		'goldenrod'							=> '#daa520',
		'gray'									=> '#808080',
		'green'									=> '#008000',
		'greenyellow'						=> '#adff2f',
		'grey'									=> '#808080',
		'honeydew'							=> '#f0fff0',
		'hotpink'								=> '#ff69b4',
		'indianred'							=> '#cd5c5c',
		'indigo'								=> '#4b0082',
		'ivory'									=> '#fffff0',
		'khaki'									=> '#f0e68c',
		'lavender'							=> '#e6e6fa',
		'lavenderblush'					=> '#fff0f5',
		'lawngreen'							=> '#7cfc00',
		'lemonchiffon'					=> '#fffacd',
		'lightblue'							=> '#add8e6',
		'lightcoral'						=> '#f08080',
		'lightcyan'							=> '#e0ffff',
		'lightgoldenrodyellow'	=> '#fafad2',
		'lightgray'							=> '#d3d3d3',
		'lightgreen'						=> '#90ee90',
		'lightgrey'							=> '#d3d3d3',
		'lightpink'							=> '#ffb6c1',
		'lightsalmon'						=> '#ffa07a',
		'lightseagreen'					=> '#20b2aa',
		'lightskyblue'					=> '#87cefa',
		'lightslategray'				=> '#778899',
		'lightslategrey'				=> '#778899',
		'lightsteelblue'				=> '#b0c4de',
		'lightyellow'						=> '#ffffe0',
		'lime'									=> '#00ff00',
		'limegreen'							=> '#32cd32',
		'linen'									=> '#faf0e6',
		'magenta'								=> '#ff00ff',
		'maroon'								=> '#800000',
		'mediumaquamarine'			=> '#66cdaa',
		'mediumblue'						=> '#0000cd',
		'mediumorchid'					=> '#ba55d3',
		'mediumpurple'					=> '#9370db',
		'mediumseagreen'				=> '#3cb371',
		'mediumslateblue'				=> '#7b68ee',
		'mediumspringgreen'			=> '#00fa9a',
		'mediumturquoise'				=> '#48d1cc',
		'mediumvioletred'				=> '#c71585',
		'midnightblue'					=> '#191970',
		'mintcream'							=> '#f5fffa',
		'mistyrose'							=> '#ffe4e1',
		'moccasin'							=> '#ffe4b5',
		'navajowhite'						=> '#ffdead',
		'navy'									=> '#000080',
		'oldlace'								=> '#fdf5e6',
		'olive'									=> '#808000',
		'olivedrab'							=> '#6b8e23',
		'orange'								=> '#ffa500',
		'orangered'							=> '#ff4500',
		'orchid'								=> '#da70d6',
		'palegoldenrod'					=> '#eee8aa',
		'palegreen'							=> '#98fb98',
		'paleturquoise'					=> '#afeeee',
		'palevioletred'					=> '#db7093',
		'papayawhip'						=> '#ffefd5',
		'peachpuff'							=> '#ffdab9',
		'peru'									=> '#cd853f',
		'pink'									=> '#ffc0cb',
		'plum'									=> '#dda0dd',
		'powderblue'						=> '#b0e0e6',
		'purple'								=> '#800080',
		'red'										=> '#ff0000',
		'rosybrown'							=> '#bc8f8f',
		'royalblue'							=> '#4169e1',
		'saddlebrown'						=> '#8b4513',
		'salmon'								=> '#fa8072',
		'sandybrown'						=> '#f4a460',
		'seagreen'							=> '#2e8b57',
		'seashell'							=> '#fff5ee',
		'sienna'								=> '#a0522d',
		'silver'								=> '#c0c0c0',
		'skyblue'								=> '#87ceeb',
		'slateblue'							=> '#6a5acd',
		'slategray'							=> '#708090',
		'slategrey'							=> '#708090',
		'snow'									=> '#fffafa',
		'springgreen'						=> '#00ff7f',
		'steelblue'							=> '#4682b4',
		'tan'										=> '#d2b48c',
		'teal'									=> '#008080',
		'thistle'								=> '#d8bfd8',
		'tomato'								=> '#ff6347',
		'turquoise'							=> '#40e0d0',
		'violet'								=> '#ee82ee',
		'wheat'									=> '#f5deb3',
		'white'									=> '#ffffff',
		'whitesmoke'						=> '#f5f5f5',
		'yellow'								=> '#ffff00',
		'yellowgreen'						=> '#9acd32'
	);

	/**
	 * @var array reverse array (value => name) of named SVG1.0 colors
	 */
	static private $_svgColors;

	/**
	* @var array reverse array (value => name) of named HTML4 colors
	*/
	static private $_html4Colors = array(
		'#000000' => 'black',
		'#000080' => 'navy',
		'#0000ff' => 'blue',
		'#008000' => 'green',
		'#008080' => 'teal',
		'#00ff00' => 'lime',
		'#00ffff' => 'aqua',
		'#800000' => 'maroon',
		'#800080' => 'purple',
		'#808000' => 'olive',
		'#808080' => 'gray',
		'#c0c0c0' => 'silver',
		'#ff0000' => 'red',
		'#ff00ff' => 'fuchsia',
		'#ffff00' => 'yellow',
		'#ffffff' => 'white',
	);

	static private $regex;
	
	/**@#+
	 * RGB color components
	 */
	/**
	 * @var array RGB color components. Used to check for RGB attributes.
	 */
	static private $rgb = array('red', 'green', 'blue');
	/**
	 * @var integer red component. 0 - 255
	 */
	private $red;
	/**
	 * @var integer green component. 0 - 255
	 */
	private $green;
	/**
	 * @var integer blue component. 0 - 255
	 */
	private $blue;
	/**@#-*/
	/**@#+
	 * HSL color components
	 */
	/**
	 * @var array HSL color components. Used to check for HSL attributes.
	 */
	static private $hsl = array('hue', 'saturation', 'lightness');
	/**
	 * @var float hue component. 0 - 360
	 */
	private $hue;
	/**
	 * @var float saturation component. 0 - 100
	 */
	private $saturation;
	/**
	 * @var float lightness component. 0 - 100
	 */
	private $lightness;
	/**@#-*/
	/**
	 * @var float alpha component. 0 - 1
	 */
	private $alpha = 1;

	/**
	 * Constructs an RGB or HSL color object, optionally with an alpha channel.
	 * RGB values must be between 0 and 255. Saturation and lightness values must
	 * be between 0 and 100. The alpha value must be between 0 and 1.
	 * The color can be specified as:
	 *  + a string that is an SVG color or of the form #rgb or #rrggbb
	 *  + an array with either 'red', 'green', and 'blue' keys, and optionally
	 * an alpha key.
	 *  + an array with 'hue', 'saturation', and 'lightness' keys, and optionally
	 * an alpha key.
	 * + an array of red, green, and blue values, and optionally an alpha value.
	 * @param mixed the color
	 * @return SassColor
	 */
	public function __construct($color) {
		if (is_string($color)) {
			$color = strtolower($color);
			if ($color === self::TRANSPARENT) {
				$this->red = 0;
				$this->green = 0;
				$this->blue = 0;
				$this->alpha = 0;
			}
			else {
				if (array_key_exists($color, self::$svgColors)) {
					$color = self::$svgColors[$color];
				}
				if (strlen($color) == 4) {
					preg_match(self::EXTRACT_3, $color, $matches);
					for ($i = 1; $i < 4; $i++) {
						$matches[$i] = str_repeat($matches[$i], 2);
					}
				}
				else {
					preg_match(self::EXTRACT_6, $color, $matches);
				}

				if (empty($matches)) {
					throw new SassColorException('Invalid {what}', array('{what}'=>'SassColor string'), SassScriptParser::$context->node);
				}
				$this->red   = intval($matches[1], 16);
				$this->green = intval($matches[2], 16);
				$this->blue  = intval($matches[3], 16);
				$this->alpha = 1;
			}
		}
		elseif (is_array($color)) {
			$scheme = $this->assertValid($color);
			if ($scheme == 'rgb') {
				$this->red   = $color['red'];
				$this->green = $color['green'];
				$this->blue  = $color['blue'];
				$this->alpha = (isset($color['alpha']) ? $color['alpha'] : 1);
			}
			elseif ($scheme == 'hsl') {
				$this->hue        = $color['hue'];
				$this->saturation = $color['saturation'];
				$this->lightness  = $color['lightness'];
				$this->alpha      = (isset($color['alpha']) ? $color['alpha'] : 1);
			}
			else {
				$this->red   = $color[0];
				$this->green = $color[1];
				$this->blue  = $color[2];
				$this->alpha = (isset($color[3]) ? $color[3] : 1);
			}
		}
		else {
			throw new SassColorException('{what} must be a {type}', array('{what}'=>'Color', '{type}'=>'array'), SassScriptParser::$context->node);
		}
	}
	
	/**
	 * Color addition
	 * @param mixed SassColor|SassNumber value to add
	 * @return sassColor the color result
	 */
	public function op_plus($other) {
		if ($other instanceof SassNumber) {
			if (!$other->isUnitless()) {
				throw new SassColorException('{what} must be a {type}', array('{what}'=>Phamlp::t('sass', 'Number'), '{type}'=>Phamlp::t('sass', 'unitless number')), SassScriptParser::$context->node);
			}
			$this->red   = $this->getRed()   + $other->value;
			$this->green = $this->getGreen() + $other->value;
			$this->blue  = $this->getBlue()  + $other->value;
		}
		elseif (!$other instanceof SassColor) {
			throw new SassColorException('{what} must be a {type}', array('{what}'=>'Argument', '{type}'=>'SassColor or SassNumber'), SassScriptParser::$context->node);
		}
		else {
			$this->red   = $this->getRed()   + $other->getRed();
			$this->green = $this->getGreen() + $other->getGreen();
			$this->blue  = $this->getBlue()  + $other->getBlue();
		}
		return $this;
	}

	/**
	 * Color subraction
	 * @param mixed value (SassColor or SassNumber) to subtract
	 * @return sassColor the color result
	 */
	public function op_minus($other) {
		if ($other instanceof SassNumber) {
			if (!$other->isUnitless()) {
				throw new SassColorException('{what} must be a {type}', array('{what}'=>Phamlp::t('sass', 'Number'), '{type}'=>Phamlp::t('sass', 'unitless number')), SassScriptParser::$context->node);
			}
			$this->red   = $this->getRed()   - $other->value;
			$this->green = $this->getGreen() - $other->value;
			$this->blue  = $this->getBlue()  - $other->value;
		}
		elseif (!$other instanceof SassColor) {
			throw new SassColorException('{what} must be a {type}', array('{what}'=>'Argument', '{type}'=>'SassColor or SassNumber'), SassScriptParser::$context->node);
		}
		else {
			$this->red   = $this->getRed()   - $other->getRed();
			$this->green = $this->getGreen() - $other->getGreen();
			$this->blue  = $this->getBlue()  - $other->getBlue();
		}
		return $this;
	}

	/**
	 * Color multiplication
	 * @param mixed SassColor|SassNumber value to multiply by
	 * @return sassColor the color result
	 */
	public function op_times($other) {
		if ($other instanceof SassNumber) {
			if (!$other->isUnitless()) {
				throw new SassColorException('{what} must be a {type}', array('{what}'=>Phamlp::t('sass', 'Number'), '{type}'=>Phamlp::t('sass', 'unitless number')), SassScriptParser::$context->node);
			}
			$this->red   = $this->getRed()   * $other->value;
			$this->green = $this->getGreen() * $other->value;
			$this->blue  = $this->getBlue()  * $other->value;
		}
		elseif (!$other instanceof SassColor) {
			throw new SassColorException('{what} must be a {type}', array('{what}'=>'Argument', '{type}'=>'SassColor or SassNumber'), SassScriptParser::$context->node);
		}
		else {
			$this->red   = $this->getRed()   * $other->getRed();
			$this->green = $this->getGreen() * $other->getGreen();
			$this->blue  = $this->getBlue()  * $other->getBlue();
		}
		return $this;
	}

	/**
	 * Color division
	 * @param mixed value (SassColor or SassNumber) to divide by
	 * @return sassColor the color result
	 */
	public function op_div($other) {
		if ($other instanceof SassNumber) {
			if (!$other->isUnitless()) {
				throw new SassColorException('{what} must be a {type}', array('{what}'=>Phamlp::t('sass', 'Number'), '{type}'=>Phamlp::t('sass', 'unitless number')), SassScriptParser::$context->node);
			}
			$this->red   = $this->getRed()   / $other->value;
			$this->green = $this->getGreen() / $other->value;
			$this->blue  = $this->getBlue()  / $other->value;
		}
		elseif (!$other instanceof SassColor) {
			throw new SassColorException('{what} must be a {type}', array('{what}'=>'Argument', '{type}'=>'SassColor or SassNumber'), SassScriptParser::$context->node);
		}
		else {
			$this->red   = $this->getRed()   / $other->getRed();
			$this->green = $this->getGreen() / $other->getGreen();
			$this->blue  = $this->getBlue()  / $other->getBlue();
		}
		return $this;
	}

	/**
	 * Color modulus
	 * @param mixed value (SassColor or SassNumber) to divide by
	 * @return sassColor the color result
	 */
	public function op_modulo($other) {
		if ($other instanceof SassNumber) {
			if (!$other->isUnitless()) {
				throw new SassColorException('{what} must be a {type}', array('{what}'=>Phamlp::t('sass', 'Number'), '{type}'=>Phamlp::t('sass', 'unitless number')), SassScriptParser::$context->node);
			}
			$this->red   = $this->getRed()   % $other->value;
			$this->green = $this->getGreen() % $other->value;
			$this->blue  = $this->getBlue()  % $other->value;
		}
		elseif (!$other instanceof SassColor) {
			throw new SassColorException('{what} must be a {type}', array('{what}'=>'Argument', '{type}'=>'SassColor or SassNumber'), SassScriptParser::$context->node);
		}
		else {
			$this->red   = $this->getRed()   % $other->getRed();
			$this->green = $this->getGreen() % $other->getGreen();
			$this->blue  = $this->getBlue()  % $other->getBlue();
		}
		return $this;
	}

	/**
	 * Color bitwise AND
	 * @param mixed value (SassColor or SassNumber) to bitwise AND with
	 * @return sassColor the color result
	 */
	public function op_bw_and($other) {
		if ($other instanceof SassNumber) {
			if (!$other->isUnitless()) {
				throw new SassColorException('{what} must be a {type}', array('{what}'=>Phamlp::t('sass', 'Number'), '{type}'=>Phamlp::t('sass', 'unitless number')), SassScriptParser::$context->node);
			}
			$this->red   = $this->getRed()   & $other->value;
			$this->green = $this->getGreen() & $other->value;
			$this->blue  = $this->getBlue()  & $other->value;
		}
		elseif (!$other instanceof SassColor) {
			throw new SassColorException('{what} must be a {type}', array('{what}'=>'Argument', '{type}'=>'SassColor or SassNumber'), SassScriptParser::$context->node);
		}
		else {
			$this->red   = $this->getRed()   & $other->getRed();
			$this->green = $this->getGreen() & $other->getGreen();
			$this->blue  = $this->getBlue()  & $other->getBlue();
		}
		return $this;
	}

	/**
	 * Color bitwise OR
	 * @param mixed value (SassColor or SassNumber) to bitwise OR with
	 * @return sassColor the color result
	 */
	public function op_bw_or($other) {
		if ($other instanceof SassNumber) {
			if (!$other->isUnitless()) {
				throw new SassColorException('{what} must be a {type}', array('{what}'=>Phamlp::t('sass', 'Number'), '{type}'=>Phamlp::t('sass', 'unitless number')), SassScriptParser::$context->node);
			}
			$this->red   = $this->getRed()   | $other->value;
			$this->green = $this->getGreen() | $other->value;
			$this->blue  = $this->getBlue()  | $other->value;
		}
		elseif (!$other instanceof SassColor) {
			throw new SassColorException('{what} must be a {type}', array('{what}'=>'Argument', '{type}'=>'SassColor or SassNumber'), SassScriptParser::$context->node);
		}
		else {
			$this->red   = $this->getRed()   | $other->getRed();
			$this->green = $this->getGreen() | $other->getGreen();
			$this->blue  = $this->getBlue()  | $other->getBlue();
		}
		return $this;
	}

	/**
	 * Color bitwise XOR
	 * @param mixed value (SassColor or SassNumber) to bitwise XOR with
	 * @return sassColor the color result
	 */
	public function op_bw_xor($other) {
		if ($other instanceof SassNumber) {
			if (!$other->isUnitless()) {
				throw new SassColorException('{what} must be a {type}', array('{what}'=>Phamlp::t('sass', 'Number'), '{type}'=>Phamlp::t('sass', 'unitless number')), SassScriptParser::$context->node);
			}
			$this->red   = $this->getRed()   ^ $other->value;
			$this->green = $this->getGreen() ^ $other->value;
			$this->blue  = $this->getBlue()  ^ $other->value;
		}
		elseif (!$other instanceof SassColor) {
			throw new SassColorException('{what} must be a {type}', array('{what}'=>'Argument', '{type}'=>'SassColor or SassNumber'), SassScriptParser::$context->node);
		}
		else {
			$this->red   = $this->getRed()   ^ $other->getRed();
			$this->green = $this->getGreen() ^ $other->getGreen();
			$this->blue  = $this->getBlue()  ^ $other->getBlue();
		}
		return $this;
	}

	/**
	 * Color bitwise NOT
	 * @return sassColor the color result
	 */
	public function op_not() {
			$this->red   = ~$this->getRed();
			$this->green = ~$this->getGreen();
			$this->blue  = ~$this->getBlue();
		return $this;
	}

	/**
	 * Color bitwise Shift Left
	 * @param sassNumber amount to shift left by
	 * @return sassColor the color result
	 */
	public function op_shiftl($other) {
		if (!$other instanceof SassNumber ||!$other->isUnitless()) {
			throw new SassColorException('{what} must be a {type}', array('{what}'=>Phamlp::t('sass', 'Number'), '{type}'=>Phamlp::t('sass', 'unitless number')), SassScriptParser::$context->node);
		}
		$this->red   = $this->getRed()   << $other->value;
		$this->green = $this->getGreen() << $other->value;
		$this->blue  = $this->getBlue()  << $other->value;
		return $this;
	}

	/**
	 * Color bitwise Shift Right
	 * @param sassNumber amount to shift right by
	 * @return sassColor the color result
	 */
	public function op_shiftr($other) {
		if (!$other instanceof SassNumber || !$other->isUnitless()) {
			throw new SassColorException('{what} must be a {type}', array('{what}'=>Phamlp::t('sass', 'Number'), '{type}'=>Phamlp::t('sass', 'unitless number')), SassScriptParser::$context->node);
		}
		$this->red   = $this->getRed()   >> $other->value;
		$this->green = $this->getGreen() >> $other->value;
		$this->blue  = $this->getBlue()  >> $other->value;
		return $this;
	}
	
	/**
	* Returns a copy of this color with one or more channels changed.
	* RGB or HSL attributes may be changed, but not both at once.
	* @param array attributes to change
	*/
	public function with($attributes) {
		if ($this->assertValid($attributes, false) === 'hsl') {
			$color = array_merge(array(
				'hue'        => $this->getHue(),
				'saturation' => $this->getSaturation(),
				'lightness'  => $this->getLightness(),
				'alpha'      => $this->alpha
			), $attributes);
		}
		else {
			$color = array_merge(array(
				'red'   => $this->getRed(),
				'green' => $this->getGreen(),
				'blue'  => $this->getBlue(),
				'alpha' => $this->alpha
				), $attributes);
		}
		return new SassColor($color);
	} 

	/**
	 * Returns the alpha component (opacity) of this color.
	 * @return float the alpha component (opacity) of this color.
	 */
	public function getAlpha() {
		return $this->alpha;
	}

	/**
	 * Returns the hue of this color.
	 * @return float the hue of this color.
	 */
	public function getHue() {
		if (is_null($this->hue)) {
			$this->rgb2hsl();
		}
		return $this->hue;
	}

	/**
	 * Returns the saturation of this color.
	 * @return float the saturation of this color.
	 */
	public function getSaturation() {
		if (is_null($this->saturation)) {
			$this->rgb2hsl();
		}
		return $this->saturation;
	}

	/**
	 * Returns the lightness of this color.
	 * @return float the lightness of this color.
	 */
	public function getLightness() {
		if (is_null($this->lightness)) {
			$this->rgb2hsl();
		}
		return $this->lightness;
	}

	/**
	 * Returns the blue component of this color.
	 * @return integer the blue component of this color.
	 */
	public function getBlue() {
		if (is_null($this->blue)) {
			$this->hsl2rgb();
		}
		$component = round(abs($this->blue));
		return ($component > 255 ? $component % 255 : $component);
	}

	/**
	 * Returns the green component of this color.
	 * @return integer the green component of this color.
	 */
	public function getGreen() {
		if (is_null($this->green)) {
			$this->hsl2rgb();
		}
		$component = round(abs($this->green));
		return ($component > 255 ? $component % 255 : $component);
	}

	/**
	 * Returns the red component of this color.
	 * @return integer the red component of this color.
	 */
	public function getRed() {
		if (is_null($this->red)) {
			$this->hsl2rgb();
		}
		$component = round(abs($this->red));
		return ($component > 255 ? $component % 255 : $component);
	}

	/**
	 * Returns an array with the RGB components of this color.
	 * @return array the RGB components of this color
	 */
	public function getRgb() {
		return array($this->red, $this->green, $this->blue);
	}

	/**
	 * Returns an array with the RGB and alpha components of this color.
	 * @return array the RGB and alpha components of this color
	 */
	public function getRgba() {
		return array($this->getRed(), $this->getGreen(), $this->getBlue(), $this->alpha);
	}

	/**
	 * Returns an array with the HSL components of this color.
	 * @return array the HSL components of this color
	 */
	public function getHsl() {
		return array($this->getHue(), $this->getSaturation(), $this->getLightness());
	}

	/**
	 * Returns an array with the HSL and alpha components of this color.
	 * @return array the HSL and alpha components of this color
	 */
	public function getHsla() {
		return array($this->getHue(), $this->getSaturation(), $this->getLightness(), $this->alpha);
	}

	/**
	 * Returns the value of this color.
	 * @return array the color
	 * @deprecated
	 */
	public function getValue() {
		return $this->rgb;
	}

	/**
	 * Returns whether this color object is translucent; that is, whether the alpha channel is non-1.
	 * @return boolean true if this color is translucent, false if not
	 */
	public function isTranslucent() {
		return $this->alpha < 1;
	}

	/**
	 * Converts the color to a string.
	 * @param boolean whether to use CSS3 SVG1.0 color names
 	 * @return string the color as a named color, rgba(r,g,g,a) or #rrggbb
	 */
	public function toString($css3 = false) {
		$rgba = $this->rgba;	
		
		if ($rgba[3] == 0) {
			return 'transparent';
		}
		elseif ($rgba[3] < 1) {
			return sprintf('rgba(%d,%d,%d,%1.2f)', $rgba[0], $rgba[1], $rgba[2], $rgba[3]);
		}
		else {
			$color = sprintf('#%02x%02x%02x', $rgba[0], $rgba[1], $rgba[2]);
		}
		if ($css3) {

		if (empty(self::$_svgColors)) {
			self::$_svgColors = array_flip(self::$svgColors);
		}
					return (array_key_exists($color, self::$svgColors) ?
				self::$_svgColors[$color] : $color);
		}
		else {
			return (array_key_exists($color, self::$_html4Colors) ?
				self::$_html4Colors[$color] : $color);
		}
	}
	
	/**
	 * Converts from HSL to RGB colorspace
	 * Algorithm from the CSS3 spec: {@link http://www.w3.org/TR/css3-color/#hsl-color}
	 * @uses hue2rgb()
	 */
	private function hsl2rgb() {
		$h = ($this->hue % 360)/360;
		$s = $this->saturation/100;
		$l = $this->lightness/100;
		
		$m1 = ($l <= 0.5 ? $l * ($s + 1) : $l + $s - $l * $s);
		$m2 = $l * 2 - $m1;
		
		$this->red   = $this->hue2rgb($m1, $m2, $h + 1/3);
		$this->green = $this->hue2rgb($m1, $m2, $h);
		$this->blue  = $this->hue2rgb($m1, $m2, $h - 1/3);
	}
	
	/**
	 * Converts from hue to RGB colorspace
	 */
	private function hue2rgb($m1, $m2, $h) {
		$h += ($h < 0 ? 1 : ($h > 1 ? -1 : 0));
		
		if ($h * 6 < 1) {
			$c = $m2 + ($m1 - $m2) * $h * 6;
		}
		elseif ($h * 2 < 1) {
			$c = $m1;
		}
		elseif ($h * 3 < 2) {
			$c = $m2 + ($m1 - $m2) * (2/3 - $h) * 6;
		}
		else {
			$c = $m2;
		}
		return $c * 255; 
	}
	
	/**
	 * Converts from RGB to HSL colorspace
	 * Algorithm adapted from {@link http://en.wikipedia.org/wiki/HSL_and_HSV#Conversion_from_RGB_to_HSL_or_HSV}
	 */
	private function rgb2hsl() {
		$rgb = array($this->red/255, $this->green/255, $this->blue/255);
		$max = max($rgb);
		$min = min($rgb);
		$c = $max - $min;
		
		// Lightness
		$l = ($max + $min)/2;
		$this->lightness = $l * 100;
		
		// Saturation
		$this->saturation = ($c ? ($l <= 0.5 ? $c/(2 * $l) : $c/(2 - 2 * $l)) : 0 ) * 100;
		
		// Hue
		switch($max) {
			case $min:
				$h = 0;
				break;
			case $rgb[0]:
				$h = (($rgb[1] - $rgb[2])/$c) % 6;
				break;
			case $rgb[1]:
				$h = (($rgb[2] - $rgb[0])/$c) + 2;
				break;
			case $rgb[2]:
				$h = (($rgb[0] - $rgb[1])/$c) + 4;
				break;
		}
		$this->hue = $h * 60;
	}
	
	/**
	* Asserts that the color space is valid.
	* Returns the name of the color space: 'rgb' if red, green, or blue keys given;
	* 'hsl' if hue, saturation or lightness keys given; null if a non-associative array
	* @param array the color to test
	* @param boolean whether all color space keys must be given
	* @return string name of the color space
	* @throws SassColorException if mixed color space keys given or not all
	* keys for a color space are required but not given (contructor)
	*/
	private function assertValid($color, $all = true) {
		if (array_key_exists('red', $color) || array_key_exists('green', $color) || array_key_exists('blue', $color)) {
			if (array_key_exists('hue', $color) || array_key_exists('saturation', $color) || array_key_exists('lightness', $color)) {
				throw new SassColorException('SassColor can not have HSL and RGB keys specified', array(), SassScriptParser::$context->node);
			}
			if ($all && (!array_key_exists('red', $color) || !array_key_exists('green', $color) || !array_key_exists('blue', $color))) {
				throw new SassColorException('SassColor must have all {colorSpace} keys specified', array('{colorSpace}'=>'RGB'), SassScriptParser::$context->node);
			}
			return 'rgb';
		}
		elseif (array_key_exists('hue', $color) || array_key_exists('saturation', $color) || array_key_exists('lightness', $color)) {
			if ($all && (!array_key_exists('hue', $color) || !array_key_exists('saturation', $color) || !array_key_exists('lightness', $color))) {
				throw new SassColorException('SassColor must have all {colorSpace} keys specified', array('{colorSpace}'=>'HSL'), SassScriptParser::$context->node);
			}
			return 'hsl';		
		}
		elseif ($all && sizeof($color) < 3) {
				throw new SassColorException('SassColor array must have at least 3 elements', array(), SassScriptParser::$context->node);
		}
	}

	/**
	 * Returns a value indicating if a token of this type can be matched at
	 * the start of the subject string.
	 * @param string the subject string
	 * @return mixed match at the start of the string or false if no match
	 */
	static public function isa($subject) {
		if (empty(self::$regex)) {
			self::$regex = str_replace('{CSS_COLORS}', join('|', array_reverse(array_keys(self::$svgColors))), self::MATCH);
		}
		return (preg_match(self::$regex, strtolower($subject), $matches) ?
			$matches[0] : false);
	}
}
