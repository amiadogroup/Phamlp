<?php
/**
 * SassScript functions class file.
 * 
 * Methods in this module are accessible from the SassScript context.
 * For example, you can write:
 *
 * $color = hsl(120, 100%, 50%)
 * and it will call SassFunctions::hsl().
 *
 * There are a few things to keep in mind when modifying this module.
 * First of all, the arguments passed are SassLiteral objects.
 * Literal objects are also expected to be returned.
 *
 * Most Literal objects support the SassLiteral->value accessor
 * for getting their values. Color objects, though, must be accessed using
 * SassColor::rgb().
 *
 * Second, making functions accessible from Sass introduces the temptation
 * to do things like database access within stylesheets.
 * This temptation must be resisted.
 * Keep in mind that Sass stylesheets are only compiled once and then left as
 * static CSS files. Any dynamic CSS should be left in <style> tags in the
 * HTML.
 * 
 * @author			Chris Yates <chris.l.yates@gmail.com>
 * @copyright 	Copyright (c) 2010 PBM Web Development
 * @license			http://phamlp.googlecode.com/files/license.txt
 * @package			PHamlP
 * @subpackage	Sass.script
 */
 
/**
 * SassScript functions class.
 * A collection of functions for use in SassSCript.
 * @package			PHamlP
 * @subpackage	Sass.script
 */
class SassScriptFunctions {
	const DECREASE = false;
	const INCREASE = true;
	
	/*
	 * Color Creation
	 */
	
	/**
	 * Creates a SassColor object from red, green, and blue values.
	 * @param SassNumber the red component.
	 * A number between 0 and 255 inclusive, or between 0% and 100% inclusive
	 * @param SassNumber the green component.
	 * A number between 0 and 255 inclusive, or between 0% and 100% inclusive
	 * @param SassNumber the blue component.
	 * A number between 0 and 255 inclusive, or between 0% and 100% inclusive
	 * @return new SassColor SassColor object
	 * @throws SassScriptFunctionException if red, green, or blue are out of bounds
	 */
	public static function rgb($red, $green, $blue) {
		return self::rgba($red, $green, $blue, new SassNumber(1));
	}
	
	/**
	 * Creates a SassColor object from red, green, and blue values and alpha 
	 * channel (opacity).
	 * There are two overloads:
	 * * rgba(red, green, blue, alpha)
	 * @param SassNumber the red component.
	 * A number between 0 and 255 inclusive, or between 0% and 100% inclusive
	 * @param SassNumber the green component.
	 * A number between 0 and 255 inclusive, or between 0% and 100% inclusive
	 * @param SassNumber the blue component.
	 * A number between 0 and 255 inclusive, or between 0% and 100% inclusive
	 * @param SassNumber The alpha channel. A number between 0 and 1.
	 *
	 * * rgba(color, alpha)
	 * @param SassColor a SassColor object
	 * @param SassNumber The alpha channel. A number between 0 and 1.
	 * 
	 * @return new SassColor SassColor object
	 * @throws SassScriptFunctionException if any of the red, green, or blue 
	 * color components are out of bounds, or or the color is not a color, or
	 * alpha is out of bounds
	 */
	public static function rgba() {
		switch (func_num_args()) {
			case 2:
				$color = func_get_arg(0);
				$alpha = func_get_arg(1);					
				SassLiteral::assertType($color, 'SassColor');
				SassLiteral::assertType($alpha, 'SassNumber');
				SassLiteral::assertInRange($alpha, 0, 1);
				return $color->with(array('alpha' => $alpha->value));
				break;
			case 4:
				$rgba = array();
				$components = func_get_args();
				$alpha = array_pop($components);
				foreach($components as $component) {
					SassLiteral::assertType($component, 'SassNumber');
					if ($component->units == '%') {
						SassLiteral::assertInRange($component, 0, 100, '%');
						$rgba[] = $component->value * 2.55;
					}
					else {
						SassLiteral::assertInRange($component, 0, 255);
						$rgba[] = $component->value;
					}
				}
				SassLiteral::assertType($alpha, 'SassNumber');
				SassLiteral::assertInRange($alpha, 0, 1);
				$rgba[] = $alpha->value;
				return new SassColor($rgba);
				break;
			default:
				throw new SassScriptFunctionException('Incorrect argument count for {method}; expected {expected}, received {received}', array('{method}' => __METHOD__, '{expected}' => '2 or 4', '{received}' => func_num_args()), SassScriptParser::$context->node);
		}		
	}

	/**
	 * Creates a SassColor object from hue, saturation, and lightness.
	 * Uses the algorithm from the
	 * {@link http://www.w3.org/TR/css3-color/#hsl-color CSS3 spec}.
	 * @param float The hue of the color in degrees.
	 * Should be between 0 and 360 inclusive
	 * @param mixed The saturation of the color as a percentage.
	 * Must be between '0%' and 100%, inclusive
	 * @param mixed The lightness of the color as a percentage.
	 * Must be between 0% and 100%, inclusive
	 * @return new SassColor The resulting color
	 * @throws SassScriptFunctionException if saturation or lightness are out of bounds
	 */
	public static function hsl($h, $s, $l) {
		return self::hsla($h, $s, $l, new SassNumber(1));
	}

	/**
	 * Creates a SassColor object from hue, saturation, lightness and alpha 
	 * channel (opacity).
	 * @param SassNumber The hue of the color in degrees.
	 * Should be between 0 and 360 inclusive
	 * @param SassNumber The saturation of the color as a percentage.
	 * Must be between 0% and 100% inclusive
	 * @param SassNumber The lightness of the color as a percentage.
	 * Must be between 0% and 100% inclusive
	 * @param float The alpha channel. A number between 0 and 1. 
	 * @return new SassColor The resulting color
	 * @throws SassScriptFunctionException if saturation, lightness or alpha are
	 * out of bounds
	 */
	public static function hsla($h, $s, $l, $a) {
		SassLiteral::assertType($h, 'SassNumber');
		SassLiteral::assertType($s, 'SassNumber');
		SassLiteral::assertType($l, 'SassNumber');
		SassLiteral::assertType($a, 'SassNumber');
		SassLiteral::assertInRange($s, 0, 100, '%');
		SassLiteral::assertInRange($l, 0, 100, '%');
		SassLiteral::assertInRange($a, 0,   1);
		return new SassColor(array('hue'=>$h, 'saturation'=>$s, 'lightness'=>$l, 'alpha'=>$a));
	}
	
	/*
	 * Color Information
	 */

	/**
	 * Returns the red component of a color.
	 * @param SassColor The color
	 * @return new SassNumber The red component of color
	 * @throws SassScriptFunctionException If $color is not a color
	 */
	public static function red($color) {
		SassLiteral::assertType($color, 'SassColor');
		return new SassNumber($color->red);
	}

	/**
	 * Returns the green component of a color.
	 * @param SassColor The color
	 * @return new SassNumber The green component of color
	 * @throws SassScriptFunctionException If $color is not a color
	 */
	public static function green($color) {
		SassLiteral::assertType($color, 'SassColor');
		return new SassNumber($color->green);
	}
	
	/**
	 * Returns the blue component of a color.
	 * @param SassColor The color
	 * @return new SassNumber The blue component of color
	 * @throws SassScriptFunctionException If $color is not a color
	 */
	public static function blue($color) {
		SassLiteral::assertType($color, 'SassColor');
		return new SassNumber($color->blue);
	}

	/**
	 * Returns the hue component of a color.
	 * @param SassColor The color
	 * @return new SassNumber The hue component of color
	 * @throws SassScriptFunctionException If $color is not a color
	 */
	public static function hue($color) {
		SassLiteral::assertType($color, 'SassColor');
		return new SassNumber($color->hue);
	}

	/**
	 * Returns the saturation component of a color.
	 * @param SassColor The color
	 * @return new SassNumber The saturation component of color
	 * @throws SassScriptFunctionException If $color is not a color
	 */
	public static function saturation($color) {
		SassLiteral::assertType($color, 'SassColor');
		return new SassNumber($color->saturation);
	}

	/**
	 * Returns the lightness component of a color.
	 * @param SassColor The color
	 * @return new SassNumber The lightness component of color
	 * @throws SassScriptFunctionException If $color is not a color
	 */
	public static function lightness($color) {
		SassLiteral::assertType($color, 'SassColor');
		return new SassNumber($color->lightness);
	}

	/**
	 * Returns the alpha component (opacity) of a color.
	 * @param SassColor The color
	 * @return new SassNumber The alpha component (opacity) of color
	 * @throws SassScriptFunctionException If $color is not a color
	 */
	public static function alpha($color) {
		SassLiteral::assertType($color, 'SassColor');
		return new SassNumber($color->alpha);
	}

	/**
	 * Returns the alpha component (opacity) of a color.
	 * @param SassColor The color
	 * @return new SassNumber The alpha component (opacity) of color
	 * @throws SassScriptFunctionException If $color is not a color
	 */
	public static function opacity($color) {
		SassLiteral::assertType($color, 'SassColor');
		return new SassNumber($color->alpha);
	}
	
	/*
	 * Color Adjustments
	 */

	/**
	 * Changes the hue of a color while retaining the lightness and saturation.
	 * @param SassColor The color to adjust
	 * @param SassNumber The amount to adjust the color by
	 * @return new SassColor The adjusted color
	 * @throws SassScriptFunctionException If $color is not a color or
	 * $degrees is not a number
	 */
	public static function adjust_hue($color, $degrees) {
		SassLiteral::assertType($color, 'SassColor');
		SassLiteral::assertType($degrees, 'SassNumber');
		return $color->with(array('hue' => $color->hue + $degrees->value));
	}

	/**
	 * Makes a color lighter.
	 * @param SassColor The color to lighten
	 * @param SassNumber The amount to lighten the color by
	 * @param SassBoolean Whether the amount is a proportion of the current value
	 * (true) or the total range (false).
	 * The default is false - the amount is a proportion of the total range.
	 * If the color lightness value is 40% and the amount is 50%,
	 * the resulting color lightness value is 90% if the amount is a proportion
	 * of the total range, whereas it is 60% if the amount is a proportion of the
	 * current value.
	 * @return new SassColor The lightened color
	 * @throws SassScriptFunctionException If $color is not a color or
	 * $amount is not a number
	 * @see lighten_rel
	 */
	public static function lighten($color, $amount, $ofCurrent = false) {
		return self::adjust($color, $amount, $ofCurrent, 'lightness', self::INCREASE, 0, 100, '%');
	}

	/**
	 * Makes a color darker.
	 * @param SassColor The color to darken
	 * @param SassNumber The amount to darken the color by
	 * @param SassBoolean Whether the amount is a proportion of the current value
	 * (true) or the total range (false).
	 * The default is false - the amount is a proportion of the total range.
	 * If the color lightness value is 80% and the amount is 50%,
	 * the resulting color lightness value is 30% if the amount is a proportion
	 * of the total range, whereas it is 40% if the amount is a proportion of the
	 * current value.
	 * @return new SassColor The darkened color
	 * @throws SassScriptFunctionException If $color is not a color or
	 * $amount is not a number
	 * @see adjust
	 */
	public static function darken($color, $amount, $ofCurrent = false) {
		return self::adjust($color, $amount, $ofCurrent, 'lightness', self::DECREASE, 0, 100, '%');
	}

	/**
	 * Makes a color more saturated.
	 * @param SassColor The color to saturate
	 * @param SassNumber The amount to saturate the color by
	 * @param SassBoolean Whether the amount is a proportion of the current value
	 * (true) or the total range (false).
	 * The default is false - the amount is a proportion of the total range.
	 * If the color saturation value is 40% and the amount is 50%,
	 * the resulting color saturation value is 90% if the amount is a proportion
	 * of the total range, whereas it is 60% if the amount is a proportion of the
	 * current value.
	 * @return new SassColor The saturated color
	 * @throws SassScriptFunctionException If $color is not a color or
	 * $amount is not a number
	 * @see adjust
	 */
	public static function saturate($color, $amount, $ofCurrent = false) {
		return self::adjust($color, $amount, $ofCurrent, 'saturation', self::INCREASE, 0, 100, '%');
	}

	/**
	 * Makes a color less saturated.
	 * @param SassColor The color to desaturate
	 * @param SassNumber The amount to desaturate the color by
	 * @param SassBoolean Whether the amount is a proportion of the current value
	 * (true) or the total range (false).
	 * The default is false - the amount is a proportion of the total range.
	 * If the color saturation value is 80% and the amount is 50%,
	 * the resulting color saturation value is 30% if the amount is a proportion
	 * of the total range, whereas it is 40% if the amount is a proportion of the
	 * current value.
	 * @return new SassColor The desaturateed color
	 * @throws SassScriptFunctionException If $color is not a color or
	 * $amount is not a number
	 * @see adjust
	 */
	public static function desaturate($color, $amount, $ofCurrent = false) {
		return self::adjust($color, $amount, $ofCurrent, 'saturation', self::DECREASE, 0, 100, '%');
	}

	/**
	 * Makes a color more opaque.
	 * @param SassColor The color to opacify
	 * @param SassNumber The amount to opacify the color by
	 * If this is a unitless number between 0 and 1 the adjustment is absolute,
	 * if it is a percentage the adjustment is relative.
	 * If the color alpha value is 0.4
	 * if the amount is 0.5 the resulting color alpha value  is 0.9,
	 * whereas if the amount is 50% the resulting color alpha value  is 0.6.
	 * @return new SassColor The opacified color
	 * @throws SassScriptFunctionException If $color is not a color or
	 * $amount is not a number
	 * @see opacify_rel
	 */
	public static function opacify($color, $amount, $ofCurrent = false) {
		$units = self::units($amount);
		return self::adjust($color, $amount, $ofCurrent, 'alpha', self::INCREASE, 0, ($units === '%' ? 100 : 1), $units);
	}

	/**
	 * Makes a color more transparent.
	 * @param SassColor The color to transparentize
	 * @param SassNumber The amount to transparentize the color by.
	 * If this is a unitless number between 0 and 1 the adjustment is absolute,
	 * if it is a percentage the adjustment is relative.
	 * If the color alpha value is 0.8
	 * if the amount is 0.5 the resulting color alpha value  is 0.3,
	 * whereas if the amount is 50% the resulting color alpha value  is 0.4.
	 * @return new SassColor The transparentized color
	 * @throws SassScriptFunctionException If $color is not a color or
	 * $amount is not a number
	 */
	public static function transparentize($color, $amount, $ofCurrent = false) {
		$units = self::units($amount);
		return self::adjust($color, $amount, $ofCurrent, 'alpha', self::DECREASE, 0, ($units === '%' ? 100 : 1), $units);
	}

	/**
	 * Makes a color more opaque.
	 * Alias for {@link opacify}.
	 * @param SassColor The color to opacify
	 * @param SassNumber The amount to opacify the color by
	 * @param SassBoolean Whether the amount is a proportion of the current value
	 * (true) or the total range (false).
	 * @return new SassColor The opacified color
	 * @throws SassScriptFunctionException If $color is not a color or
	 * $amount is not a number
	 * @see opacify
	 */
	public static function fade_in($color, $amount, $ofCurrent = false) {
		return self::opacify($color, $amount, $ofCurrent);
	}

	/**
	 * Makes a color more transparent.
	 * Alias for {@link transparentize}.
	 * @param SassColor The color to transparentize
	 * @param SassNumber The amount to transparentize the color by
	 * @param SassBoolean Whether the amount is a proportion of the current value
	 * (true) or the total range (false).
	 * @return new SassColor The transparentized color
	 * @throws SassScriptFunctionException If $color is not a color or
	 * $amount is not a number
	 * @see transparentize
	 */
	public static function fade_out($color, $amount, $ofCurrent = false) {
		return self::transparentize($color, $amount, $ofCurrent);
	}
	
	/**
	 * Returns the complement of a color.
	 * Rotates the hue by 180 degrees.
	 * @param SassColor The color
	 * @return new SassColor The comlemented color
	 * @uses adjust_hue()
	 */
	public static function complement($color) {
		return self::adjust_hue($color, new SassNumber('180deg'));
	}

	/**
	 * Greyscale for non-english speakers.
	 * @param SassColor The color
	 * @return new SassColor The greyscale color
	 * @see desaturate
	 */
	public static function grayscale($color) {
		return self::desaturate($color, new SassNumber(100));
	}

	/**
	 * Converts a color to greyscale.
	 * Reduces the saturation to zero.
	 * @param SassColor The color
	 * @return new SassColor The greyscale color
	 * @see desaturate
	 */
	public static function greyscale($color) {
		return self::desaturate($color, new SassNumber(100));
	}

	/**
	 * Mixes two colors together.
	 * Takes the average of each of the RGB components, optionally weighted by the
	 * given percentage. The opacity of the colors is also considered when
	 * weighting the components.
	 * The weight specifies the amount of the first color that should be included
	 * in the returned color. The default, 50%, means that half the first color
	 * and half the second color should be used. 25% means that a quarter of the
	 * first color and three quarters of the second color should be used.
	 * For example:
	 *   mix(#f00, #00f) => #7f007f
	 *   mix(#f00, #00f, 25%) => #3f00bf
	 *   mix(rgba(255, 0, 0, 0.5), #00f) => rgba(63, 0, 191, 0.75)
	 *
	 * @param SassColor The first color
	 * @param SassColor The second color
	 * @param float Percentage of the first color to use
	 * @return new SassColor The mixed color
	 * @throws SassScriptFunctionException If $color1 or $color2 is
	 * not a color
	 */
	public static function mix($color1, $color2, $weight = null) {
		if (is_null($weight)) $weight = new SassNumber('50%');
		SassLiteral::assertType($color1, 'SassColor');
		SassLiteral::assertType($color2, 'SassColor');
		SassLiteral::assertType($weight, 'SassNumber');
		SassLiteral::assertInRange($weight, 0, 100, '%');
		
		/*
		 * This algorithm factors in both the user-provided weight
		 * and the difference between the alpha values of the two colors
		 * to decide how to perform the weighted average of the two RGB values.
		 *
		 * It works by first normalizing both parameters to be within [-1, 1],
		 * where 1 indicates "only use color1", -1 indicates "only use color 0",
		 * and all values in between indicated a proportionately weighted average.
		 *
		 * Once we have the normalized variables w and a,
		 * we apply the formula (w + a)/(1 + w*a)
		 * to get the combined weight (in [-1, 1]) of color1.
		 * This formula has two especially nice properties:
		 *
		 * * When either w or a are -1 or 1, the combined weight is also that number
		 *  (cases where w * a == -1 are undefined, and handled as a special case).
		 *
		 * * When a is 0, the combined weight is w, and vice versa
		 *
		 * Finally, the weight of color1 is renormalized to be within [0, 1]
		 * and the weight of color2 is given by 1 minus the weight of color1.
		 */
		
		$p = $weight->value/100;
		$w = $p * 2 - 1;
		$a = $color1->alpha - $color2->alpha;

		$w1 = ((($w * $a == -1) ? $w : ($w + $a)/(1 + $w * $a)) + 1) / 2;
		$w2 = 1 - $w1;

		$rgb1 = $color1->rgb();
		$rgb2 = $color2->rgb();
		$rgba = array();
		foreach ($rgb1 as $key=>$value) {
			$rgba[$key] = $value * $w1 + $rgb2[$key] * $w2;
		} // foreach
		$rgba[] = $color1->alpha * $p + $color2->alpha * (1 - $p);
		return new SassColor($rgba);
	}
	
	/**
	 * Adjusts the color
	 * @param SassColor the color to adjust
	 * @param SassNumber the amount to adust by
	 * @param boolean whether the amount is a proportion of the current value or
	 * the total range
	 * @param string the attribute to adjust
	 * @param boolean whether to decrease (false) or increase (true) the value of the attribute
	 * @param float minimum value the amount can be
	 * @param float maximum value the amount can bemixed
	 * @param string amount units
	 */
	private static function adjust($color, $amount, $ofCurrent, $attribute, $op, $min, $max, $units='') {
		SassLiteral::assertType($color, 'SassColor');
		SassLiteral::assertType($amount, 'SassNumber');
		SassLiteral::assertInRange($amount, $min, $max, $units);
		if (!is_bool($ofCurrent)) {
			SassLiteral::assertType($ofCurrent, 'SassBoolean');
			$ofCurrent = $ofCurrent->value;
		}
		
		$amount = $amount->value * (($attribute === 'alpha' && $ofCurrent && $units === '') ? 100 : 1); 
			
		return $color->with(array(
			$attribute => self::inRange((
				$ofCurrent ?
				$color->$attribute * (1 + ($amount * ($op === self::INCREASE ? 1 : -1))/100) :
				$color->$attribute + ($amount * ($op === self::INCREASE ? 1 : -1))
			), $min, $max)
		));		
	}
	
	/*
	 * Number Functions
	 */
	
	/**
	 * Finds the absolute value of a number.
	 * For example:
	 *		 abs(10px) => 10px
	 *		 abs(-10px) => 10px
	 *
	 * @param SassNumber The number to round
	 * @return SassNumber The absolute value of the number
	 * @throws SassScriptFunctionException If $number is not a number
	 */
	public static function abs($number) {
		SassLiteral::assertType($number, 'SassNumber');
		return new SassNumber(abs($number->value).$number->units);
	}

	/**
	 * Rounds a number up to the nearest whole number.
	 * For example:
	 *		 ceil(10.4px) => 11px
	 *		 ceil(10.6px) => 11px
	 *
	 * @param SassNumber The number to round
	 * @return new SassNumber The rounded number
	 * @throws SassScriptFunctionException If $number is not a number
	 */
	public static function ceil($number) {
		SassLiteral::assertType($number, 'SassNumber');
		return new SassNumber(ceil($number->value).$number->units);
	}

	/**
	 * Rounds down to the nearest whole number.
	 * For example:
	 *		 floor(10.4px) => 10px
	 *		 floor(10.6px) => 10px
	 *
	 * @param SassNumber The number to round
	 * @return new SassNumber The rounded number
	 * @throws SassScriptFunctionException If $value is not a number
	 */
	public static function floor($number) {
		SassLiteral::assertType($number, 'SassNumber');
		return new SassNumber(floor($number->value).$number->units);
	}

	/**
	 * Rounds a number to the nearest whole number.
	 * For example:
	 *		 round(10.4px) => 10px
	 *		 round(10.6px) => 11px
	 *
	 * @param SassNumber The number to round
	 * @return new SassNumber The rounded number
	 * @throws SassScriptFunctionException If $number is not a number
	 */
	public static function round($number) {
		SassLiteral::assertType($number, 'SassNumber');
		return new SassNumber(round($number->value).$number->units);
	}
	
	/**
	 * Returns true if two numbers are similar enough to be added, subtracted,
	 * or compared.
	 * @param SassNumber The first number to test
	 * @param SassNumber The second number to test
	 * @return new SassBoolean True if the numbers are similar
	 * @throws SassScriptFunctionException If $number1 or $number2 is not
	 * a number
	 */
	public static function comparable($number1, $number2) {
		SassLiteral::assertType($number1, 'SassNumber');
		SassLiteral::assertType($number2, 'SassNumber');
		return new SassBoolean($number1->isComparableTo($number2));
	}
	
	/**
	 * Converts a decimal number to a percentage.
	 * For example:
	 *		 percentage(100px / 50px) => 200%
	 *
	 * @param SassNumber The decimal number to convert to a percentage
	 * @return new SassNumber The number as a percentage
	 * @throws SassScriptFunctionException If $number isn't a unitless number
	 */
	public static function percentage($number) {
		if (!$number instanceof SassNumber || $number->hasUnits()) {
			throw new SassScriptFunctionException('{what} must be a {type}', array('{what}'=>'number', '{type}'=>'unitless SassNumber'), SassScriptParser::$context->node);
		}
		$number->value *= 100;
		$number->units = '%';
		return $number;
	}

	/**
	 * Inspects the unit of the number, returning it as a quoted string.
	 * Alias for units.
	 * @param SassNumber The number to inspect
	 * @return new SassString The units of the number
	 * @throws SassScriptFunctionException If $number is not a number
	 * @see units
	 */
	public static function unit($number) {
		return self::units($number);
	}

	/**
	 * Inspects the units of the number, returning it as a quoted string.
	 * @param SassNumber The number to inspect
	 * @return new SassString The units of the number
	 * @throws SassScriptFunctionException If $number is not a number
	 */
	public static function units($number) {
		SassLiteral::assertType($number, 'SassNumber');
		return new SassString($number->units);
	}

	/**
	 * Inspects the unit of the number, returning a boolean indicating if it is
	 * unitless.
	 * @param SassNumber The number to inspect
	 * @return new SassBoolean True if the number is unitless, false if it has units.
	 * @throws SassScriptFunctionException If $number is not a number
	 */
	public static function unitless() {
		SassLiteral::assertType($number, 'SassNumber');
		return new SassBoolean(!$number->hasUnits());
	}
	
	/*
	 * String Functions
	 */

	/**
	 * Add quotes to a string if the string isn't quoted,
	 * or returns the same string if it is.
	 * @param string String to quote
	 * @return new SassString Quoted string
	 * @throws SassScriptFunctionException If $string is not a string
	 * @see unquote
	 */
	public static function quote($string) {
		SassLiteral::assertType($string, 'SassString');
		return new SassString('"'.$string->value.'"');
	}

	/**
	 * Removes quotes from a string if the string is quoted, or returns the same
	 * string if it's not.
	 * @param string String to unquote
	 * @return new SassString Unuoted string
	 * @throws SassScriptFunctionException If $string is not a string
	 * @see quote
	 */
	public static function unquote($string) {
		SassLiteral::assertType($string, 'SassString');
		return new SassString($string->value);
	}

	/**
	 * Returns the variable whose name is the string.
	 * @param string String to unquote
	 * @return 
	 * @throws SassScriptFunctionException If $string is not a string
	 */
	public static function get_var($string) {
		SassLiteral::assertType($string, 'SassString');
		return new SassString($string->toVar());
	}
	
	/*
	 * Misc. Functions
	 */
	
	/**
	 * Inspects the type of the argument, returning it as an unquoted string.
	 * @param SassLiteral The object to inspect
	 * @return new SassString The type of object
	 * @throws SassScriptFunctionException If $obj is not an instance of a
	 * SassLiteral
	 */
	public static function type_of($obj) {
		SassLiteral::assertType($obj, SassLiteral);
		return new SassString($obj->typeOf);
	}
	
	/**
	 * Ensures the value is within the given range, clipping it if needed.
	 * @param float the value to test
	 * @param float the minimum value
	 * @param float the maximum value
	 * @return the value clipped to the range
	 */
	 private static function inRange($value, $min, $max) {
	 	 return ($value < $min ? $min : ($value > $max ? $max : $value));
	}
}