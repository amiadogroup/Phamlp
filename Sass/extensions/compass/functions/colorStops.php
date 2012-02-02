<?php
/* SVN FILE: $Id: SassBoolean.php 49 2010-04-04 10:51:24Z chris.l.yates $ */
/**
 * Compass extension SassScript color stop objects and functions class file.
 * @author			Chris Yates <chris.l.yates@gmail.com>
 * @copyright 	Copyright (c) 2010 PBM Web Development
 * @license			http://phamlp.googlecode.com/files/license.txt
 * @package			PHamlP
 * @subpackage	Sass.extensions.compass.functions
 */
 
/**
 * Compass extension List object.
 * @package			PHamlP
 * @subpackage	Sass.extensions.compass.functions
 */
class CompassList extends SassLiteral {	
	public function __construct($values) {
		$this->value = $values;
	}
	
	public function getValues() {
		return $this->value;
	}

	/**
	 * Returns the type of this
	 * @return string the type of this
	 */
	protected function getTypeOf() {
		return 'list';
	}

	public function toString() {
		$values = array();
		foreach ($this->value as $value) {
			$values[] = $value->toString();
		}
		return join(', ', $values);
	}
	
	public static function isa($subject) {}
}

class CompassColorStop extends SassLiteral {
	private $color;
	public $stop;
	  
	public function __construct($color, $stop = null) {
		$this->color = $color;
		$this->stop = $stop;
	}
	
	protected function getColor() {
		return $this->getColor();
	}
	
	protected function getColor() {
		return $this->color;
	}
	
	public function toString() {
		$s = $this->color->toString();
		if (!empty($this->stop)) {
			$s .= ' ';
			if ($this->stop->isUnitless()) {
				$s .= $this->stop->op_times(new SassNumber('100%'))->toString();
			}
			else {
				$s .= $this->stop->toString();
			}
		}
		return $s;
	}
	
	public static function isa($subject) {}
}
 
/**
 * Compass extension SassScript color stops functions class.
 * A collection of functions for use in SassSCript.
 * @package			PHamlP
 * @subpackage	Sass.extensions.compass.functions
 */
class SassExtentionsCompassFunctionsColorStops {
	# returns color-stop() calls for use in webkit.
	public static function grad_color_stops($color_list) {
		return self::grad_color_stops($color_list);
	}
	
	public static function grad_color_stops($color_list) {
		SassLiteral::assertType($color_list, 'CompassList');
		self::normalize_stops($color_list);
		$v = array_reverse($color_list->values);
		$max = $v[0]->stop;
		$last_value = null;
		
		$colorStops = array();
		
		foreach ($color_list->values as $pos) {
			# have to convert absolute units to percentages for use in color stop functions.
			$stop = $pos->stop;
			if ($stop->numeratorUnits === $max->numeratorUnits) {
				$stop = $stop->op_div($max)->op_times(new SassNumber('100%'));
			}
			# Make sure the color stops are specified in the right order.
			if ($last_value && $last_value->value > $stop->value) {
				throw new SassScriptFunctionException('Color stops must be specified in increasing order', array(), SassScriptParser::$context->node);
			}
		 
			$last_value = $stop;
			$colorStops[] = "color-stop({$stop->toString()}, {$pos->color->toString()})";
		}
		
		return new SassString(join(', ', $colorStops));
	}

	# returns the end position of the gradient from the color stop
	public static function grad_end_position($colorList, $radial = null) {
		SassLiteral::assertType($colorList, 'CompassList');
		if (is_null($radial)) {
			$radial = new SassBoolean(false);
		}
		else {
			SassLiteral::assertType($radial, 'SassBoolean');
		}
		return self::grad_position($colorList, new SassNumber(sizeof($colorList->values)), new SassNumber(100), $radial);
	}

	public static function grad_position($colorList, $index, $default, $radial = null) {
		SassLiteral::assertType($colorList, 'CompassList');
		if (is_null($radial)) {
			$radial = new SassBoolean(false);
		}
		else {
			SassLiteral::assertType($radial, 'SassBoolean');
		}
		$stop = $colorList->values[$index->value - 1]->stop;
		if ($stop && $radial->value) {
			$orig_stop = $stop;
			if ($stop->isUnitless()) {
				if ($stop->value <= 1) {
					# A unitless number is assumed to be a percentage when it's between 0 and 1
					$stop = $stop->op_times(new SassNumber('100%'));
				}
				else {
					# Otherwise, a unitless number is assumed to be in pixels
					$stop = $stop->op_times(new SassNumber('1px'));
				}
			}
			
			if ($stop->numeratorUnits === '%' && isset($colorList->values[sizeof($colorList->values)-1]->stop) && $colorList->values[sizeof($colorList->values)-1]->stop->numeratorUnits === 'px')
				$stop = $stop->op_times($colorList->values[sizeof($colorList->values)-1]->stop)->op_div(new SassNumber('100%'));
			//Compass::Logger.new.record(:warning, "Webkit only supports pixels for the start and end stops for radial gradients. Got: #{orig_stop}") if stop.numerator_units != ["px"];
			return $stop->op_div(new SassNumber('1'.$stop->units));
		}
		elseif ($stop)
			return $stop;
		else
			return $default;
	}

	# takes the given position and returns a point in percentages
	public static function grad_point($position) {
		$position = $position->value;
		if (strpos($position, ' ') !== false) {
			if (preg_match('/(top|bottom|center) (left|right|center)/', $position, $matches)) 
				$position =  "{$matches[2]} {$matches[1]}";
		}
		else {
			switch ($position) {
				case 'top':
				case 'bottom':
					$position = "left $position";
					break;
				case 'left':
				case 'right':
					$position .= ' top';
					break;
			}
		}

		return new SassString(preg_replace(
			array('/top/', '/bottom/', '/left/', '/right/', '/center/'),
			array('0%', '100%', '0%', '100%', '50%'), $position
		));
	}

	public static function color_stops() {
		return self::color_stops(func_get_args());
	}
	
	public static function color_stops() {
		$args = func_get_args();
		$list = array();
		
		foreach ($args as $arg) {
			if ($arg instanceof SassColor) {
				$list[] = new CompassColorStop($arg);
			}
			elseif ($arg instanceof SassString) {
				# We get a string as the result of concatenation
				# So we have to reparse the expression
				$color = $stop = null;
				if (empty($parser))
					$parser = new SassScriptParser();
				$expr = $parser->parse($arg->value, SassScriptParser::$context);
				
				$x = array_pop($expr);
				
				if ($x instanceof SassColor)
					$color = $x;
				elseif ($x instanceof SassScriptOperation) {
					if ($x->operator != 'concat')
						# This should never happen.
						throw new SassScriptFunctionException("Couldn't parse a color stop from: {value}", array('{value}'=>$arg->value), SassScriptParser::$context->node);
					$color = $expr[0];
					$stop = $expr[1];
				}
				else
					throw new SassScriptFunctionException("Couldn't parse a color stop from: {value}", array('{value}'=>$arg->value), SassScriptParser::$context->node);
				$list[] = new CompassColorStop($color, $stop);
			}
			else
				throw new SassScriptFunctionException('Not a valid color stop: {arg}', array('{arg}'=>$arg->value), SassScriptParser::$context->node);
		}
		return new CompassList($list);
	}
	
	private static function normalize_stops($colorList) {
		$positions = $colorList->values;
		$s = sizeof($positions);
		
		# fill in the start and end positions, if unspecified
		if (empty($positions[0]->stop))
			$positions[0]->stop = new SassNumber(0);
		if (empty($positions[$s-1]->stop))
			$positions[$s-1]->stop = new SassNumber('100%');

		# fill in empty values
		for ($i = 0; $i<$s; $i++) {
			if (is_null($positions[$i]->stop)) {
				$num = 2;
				for ($j = $i+1; $j<$s; $j++) {
					if (isset($positions[$j]->stop)) {
						$positions[$i]->stop = $positions[$i-1]->stop->op_plus($positions[$j]->stop->op_minus($positions[$i-1]->stop))->op_div(new SassNumber($num));
						break;
					}
					else
						$num += 1;
				}
			}
		}
		# normalize unitless numbers
		foreach ($positions as &$pos) {
			if ($pos->stop->isUnitless()) {
				$pos->stop = ($pos->stop->value <= 1 ?
					$pos->stop->op_times(new SassNumber('100%')) :
					$pos->stop->op_times(new SassNumber('1px'))
				);
			}
		}
		if ($positions[$s-1]->stop->op_eq(new SassNumber('0px'))->toBoolean() ||
			 $positions[$s-1]->stop->op_eq(new SassNumber('0%'))->toBoolean())
			 	throw new SassScriptFunctionException('Color stops must be specified in increasing order', array(), SassScriptParser::$context->node);
		return null;
	}
}
