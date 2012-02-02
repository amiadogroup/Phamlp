<?php
/* SVN FILE: $Id$ */
/**
 * Phamlp_Sass_Script_Literal class file.
 * @author			Chris Yates <chris.l.yates@gmail.com>
 * @copyright 	Copyright (c) 2010 PBM Web Development
 * @license			http://phamlp.googlecode.com/files/license.txt
 * @package			PHamlP
 * @subpackage	Sass.script.literals
 */

require_once('Phamlp_Sass_Script_LiteralExceptions.php');

/**
 * Phamlp_Sass_Script_Literal class.
 * Base class for all Sass literals.
 * Sass data types are extended from this class and these override the operation
 * methods to provide the appropriate semantics.
 * @package			PHamlP
 * @subpackage	Sass.script.literals
 */
abstract class Phamlp_Sass_Script_Literal {
	/**
	 * @var array maps class names to data types
	 */
	static private $typeOf = array(
		'Phamlp_Sass_Script_Literal_Boolean' => 'bool',
		'Phamlp_Sass_Script_Literal_Colour'  => 'color',
		'Phamlp_Sass_Script_Literal_Number'  => 'number',
		'Phamlp_Sass_Script_Literal_String'  => 'string'
	);
	
	/**
	 * @var mixed value of the literal type
	 */
  protected $value;

	/**
	 * class constructor
	 * @param string value of the literal type
	 * @return Phamlp_Sass_Script_Literal
	 */
	public function __construct($value = null, $context) {
		$this->value = $value;
		$this->context = $context;
	}

	/**
	 * Getter.
	 * @param string name of property to get
	 * @return mixed return value of getter function
	 */
	public function __get($name) {
		$getter = 'get' . ucfirst($name);
		if (method_exists($this, $getter)) {
			return $this->$getter();
		}
		else {
			throw new Phamlp_Sass_Script_LiteralException('No getter function for {what}', array('{what}'=>$name), array(), Phamlp_Sass_Script_Parser::$context->node);
		}
	}
	
	public function __toString() {
		return $this->toString();
	}

	/**
	 * Returns the boolean representation of the value of this
	 * @return boolean the boolean representation of the value of this
	 */
	public function toBoolean() {
		return (boolean)$this->value;
	}

	/**
	 * Returns the type of this
	 * @return string the type of this
	 */
	protected function getTypeOf() {
		return self::$typeOf[get_class($this)];
	}

	/**
	 * Returns the value of this
	 * @return mixed the value of this
	 */
	protected function getValue() {
		throw new Phamlp_Sass_Script_LiteralException('Child classes must override this method', array(), Phamlp_Sass_Script_Parser::$context->node);
	}
	
	/**
	 * Adds a child object to this.
	 * @param sassLiteral the child object
	 */
	public function addChild($sassLiteral) {
		$this->children[] = $sassLiteral;
	}

	/**
	 * SassScript '+' operation.
	 * @param sassLiteral value to add
	 * @return sassString the string values of this and other with no seperation
	 */
	public function op_plus($other) {
		return new Phamlp_Sass_Script_Literal_String($this->toString().$other->toString());
	}

	/**
	 * SassScript '-' operation.
	 * @param Phamlp_Sass_Script_Literal value to subtract
	 * @return sassString the string values of this and other seperated by '-'
	 */
	public function op_minus($other) {
		return new Phamlp_Sass_Script_Literal_String($this->toString().'-'.$other->toString());
	}

	/**
	 * SassScript '*' operation.
	 * @param Phamlp_Sass_Script_Literal value to multiply by
	 * @return sassString the string values of this and other seperated by '*'
	 */
	public function op_times($other) {
		return new Phamlp_Sass_Script_Literal_String($this->toString().'*'.$other->toString());
	}

	/**
	 * SassScript '/' operation.
	 * @param Phamlp_Sass_Script_Literal value to divide by
	 * @return sassString the string values of this and other seperated by '/'
	 */
	public function op_div($other) {
		return new Phamlp_Sass_Script_Literal_String($this->toString().'/'.$other->toString());
	}

	/**
	 * SassScript '%' operation.
	 * @param Phamlp_Sass_Script_Literal value to take the modulus of
	 * @return Phamlp_Sass_Script_Literal result
	 * @throws Exception if modulo not supported for the data type
	 */
	public function op_modulo($other) {
		throw new Phamlp_Sass_Script_LiteralException('{class} does not support {operation}.', array('{class}'=>get_class($this), '{operation}'=>Phamlp::t('sass', 'Modulus')), Phamlp_Sass_Script_Parser::$context->node);
	}

	/**
	 * Bitwise AND the value of other and this value
	 * @param string value to bitwise AND with
	 * @return string result
	 * @throws Exception if bitwise AND not supported for the data type
	 */
	public function op_bw_and($other) {
		throw new Phamlp_Sass_Script_LiteralException('{class} does not support {operation}.', array('{class}'=>get_class($this), '{operation}'=>Phamlp::t('sass', 'Bitwise AND')), Phamlp_Sass_Script_Parser::$context->node);
	}

	/**
	 * Bitwise OR the value of other and this value
	 * @param Phamlp_Sass_Script_Literal_Number value to bitwise OR with
	 * @return string result
	 * @throws Exception if bitwise OR not supported for the data type
	 */
	public function op_bw_or($other) {
		throw new Phamlp_Sass_Script_LiteralException('{class} does not support {operation}.', array('{class}'=>get_class($this), '{operation}'=>Phamlp::t('sass', 'Bitwise OR')), Phamlp_Sass_Script_Parser::$context->node);
	}

	/**
	 * Bitwise XOR the value of other and the value of this
	 * @param Phamlp_Sass_Script_Literal_Number value to bitwise XOR with
	 * @return string result
	 * @throws Exception if bitwise XOR not supported for the data type
	 */
	public function op_bw_xor($other) {
		throw new Phamlp_Sass_Script_LiteralException('{class} does not support {operation}.', array('{class}'=>get_class($this), '{operation}'=>Phamlp::t('sass', 'Bitwise XOR')), Phamlp_Sass_Script_Parser::$context->node);
	}

	/**
	 * Bitwise NOT the value of other and the value of this
	 * @param Phamlp_Sass_Script_Literal_Number value to bitwise NOT with
	 * @return string result
	 * @throws Exception if bitwise NOT not supported for the data type
	 */
	public function op_bw_not() {
		throw new Phamlp_Sass_Script_LiteralException('{class} does not support {operation}.', array('{class}'=>get_class($this), '{operation}'=>Phamlp::t('sass', 'Bitwise NOT')), Phamlp_Sass_Script_Parser::$context->node);
	}

	/**
	 * Shifts the value of this left by the number of bits given in value
	 * @param Phamlp_Sass_Script_Literal_Number amount to shift left by
	 * @return string result
	 * @throws Exception if bitwise Shift Left not supported for the data type
	 */
	public function op_shiftl($other) {
		throw new Phamlp_Sass_Script_LiteralException('{class} does not support {operation}.', array('{class}'=>get_class($this), '{operation}'=>Phamlp::t('sass', 'Bitwise Shift Left')), Phamlp_Sass_Script_Parser::$context->node);
	}

	/**
	 * Shifts the value of this right by the number of bits given in value
	 * @param Phamlp_Sass_Script_Literal_Number amount to shift right by
	 * @return string result
	 * @throws Exception if bitwise Shift Right not supported for the data type
	 */
	public function op_shiftr($other) {
		throw new Phamlp_Sass_Script_LiteralException('{class} does not support {operation}.', array('{class}'=>get_class($this), '{operation}'=>Phamlp::t('sass', 'Bitwise Shift Right')), Phamlp_Sass_Script_Parser::$context->node);
	}

	/**
	 * The SassScript and operation.
	 * @param sassLiteral the value to and with this
	 * @return Phamlp_Sass_Script_Literal other if this is boolean true, this if false
	 */
	public function op_and($other) {
		return ($this->toBoolean() ? $other : $this);
	}
	
	/**
	 * The SassScript or operation.
	 * @param sassLiteral the value to or with this
	 * @return Phamlp_Sass_Script_Literal this if this is boolean true, other if false
	 */
	public function op_or($other) {
		return ($this->toBoolean() ? $this : $other);
	}
	
	/**
	 * The SassScript xor operation.
	 * @param sassLiteral the value to xor with this
	 * @return Phamlp_Sass_Script_Literal_Boolean Phamlp_Sass_Script_Literal_Boolean object with the value true if this or
	 * other, but not both, are true, false if not
	 */
	public function op_xor($other) {
		return new Phamlp_Sass_Script_Literal_Boolean($this->toBoolean() xor $other->toBoolean());
	}
	
	/**
	 * The SassScript not operation.
	 * @return Phamlp_Sass_Script_Literal_Boolean Phamlp_Sass_Script_Literal_Boolean object with the value true if the
	 * boolean of this is false or false if it is true
	 */
	public function op_not() {
		return new Phamlp_Sass_Script_Literal_Boolean(!$this->toBoolean());
	}
	
	/**
	 * The SassScript > operation.
	 * @param sassLiteral the value to compare to this
	 * @return Phamlp_Sass_Script_Literal_Boolean Phamlp_Sass_Script_Literal_Boolean object with the value true if the values
	 * of this is greater than the value of other, false if it is not
	 */
	public function op_gt($other) {
		return new Phamlp_Sass_Script_Literal_Boolean($this->value > $other->value);
	}
	
	/**
	 * The SassScript >= operation.
	 * @param sassLiteral the value to compare to this
	 * @return Phamlp_Sass_Script_Literal_Boolean Phamlp_Sass_Script_Literal_Boolean object with the value true if the values
	 * of this is greater than or equal to the value of other, false if it is not
	 */
	public function op_gte($other) {
		return new Phamlp_Sass_Script_Literal_Boolean($this->value >= $other->value);
	}
	
	/**
	 * The SassScript < operation.
	 * @param sassLiteral the value to compare to this
	 * @return Phamlp_Sass_Script_Literal_Boolean Phamlp_Sass_Script_Literal_Boolean object with the value true if the values
	 * of this is less than the value of other, false if it is not
	 */
	public function op_lt($other) {
		return new Phamlp_Sass_Script_Literal_Boolean($this->value < $other->value);
	}
	
	/**
	 * The SassScript <= operation.
	 * @param sassLiteral the value to compare to this
	 * @return Phamlp_Sass_Script_Literal_Boolean Phamlp_Sass_Script_Literal_Boolean object with the value true if the values
	 * of this is less than or equal to the value of other, false if it is not
	 */
	public function op_lte($other) {
		return new Phamlp_Sass_Script_Literal_Boolean($this->value <= $other->value);
	}
	
	/**
	 * The SassScript == operation.
	 * @param sassLiteral the value to compare to this
	 * @return Phamlp_Sass_Script_Literal_Boolean Phamlp_Sass_Script_Literal_Boolean object with the value true if this and
	 * other are equal, false if they are not
	 */
	public function op_eq($other) {
		return new Phamlp_Sass_Script_Literal_Boolean($this == $other);
	}
	
	/**
	 * The SassScript != operation.
	 * @param sassLiteral the value to compare to this
	 * @return Phamlp_Sass_Script_Literal_Boolean Phamlp_Sass_Script_Literal_Boolean object with the value true if this and
	 * other are not equal, false if they are
	 */
	public function op_neq($other) {
		return new Phamlp_Sass_Script_Literal_Boolean(!$this->op_eq($other)->toBoolean());
	}
	
	/**
	 * The SassScript default operation (e.g. $a $b, "foo" "bar").
	 * @param sassLiteral the value to concatenate with a space to this
	 * @return sassString the string values of this and other seperated by " "
	 */
	public function op_concat($other) {
		return new Phamlp_Sass_Script_Literal_String($this->toString().' '.$other->toString());
	}

	/**
	 * SassScript ',' operation.
	 * @param sassLiteral the value to concatenate with a comma to this
	 * @return sassString the string values of this and other seperated by ","
	 */
	public function op_comma($other) {
		return new Phamlp_Sass_Script_Literal_String($this->toString().', '.$other->toString());
	}
	
	/**
	 * Asserts that the literal is the expected type 
	 * @param Phamlp_Sass_Script_Literal the literal to test
	 * @param string expected type
	 * @throws Phamlp_Sass_Script_FunctionException if value is not the expected type
	 */
	public static function assertType($literal, $type) {
		if (!$literal instanceof $type) {
			throw new Phamlp_Sass_Script_FunctionException('{what} must be a {type}', array('{what}'=>($literal instanceof Phamlp_Sass_Script_Literal ? $literal->typeOf : 'literal'), '{type}'=>$type), Phamlp_Sass_Script_Parser::$context->node);
		}
	}
	
	/**
	 * Asserts that the value of a literal is within the expected range 
	 * @param Phamlp_Sass_Script_Literal the literal to test
	 * @param float the minimum value
	 * @param float the maximum value
	 * @param string the units.
	 * @throws Phamlp_Sass_Script_FunctionException if value is not the expected type
	 */
	 public static function assertInRange($literal, $min, $max, $units = '') {
	 	 if ($literal->value < $min || $literal->value > $max) {
			throw new Phamlp_Sass_Script_FunctionException('{what} must be {inRange}', array('{what}'=>$literal->typeOf, '{inRange}'=>Phamlp::t('sass', 'between {min} and {max} inclusive', array('{min}'=>$min.$units, '{max}'=>$max.$units))), Phamlp_Sass_Script_Parser::$context->node);
		}
	}

	/**
	 * Returns a string representation of the value.
	 * @return string string representation of the value.
	 */
	abstract public function toString();

	/**
	 * Returns a value indicating if a token of this type can be matched at
	 * the start of the subject string.
	 * @param string the subject string
	 * @return mixed match at the start of the string or false if no match
	 */
	abstract public static function isa($subject);
}
