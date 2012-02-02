<?php
/* SVN FILE: $Id$ */
/**
 * Phamlp_Sass_Tree_Node exception classes.
 * @author			Chris Yates <chris.l.yates@gmail.com>
 * @copyright 	Copyright (c) 2010 PBM Web Development
 * @license			http://phamlp.googlecode.com/files/license.txt
 * @package			PHamlP
 * @subpackage	Sass.tree
 */

/**
 * Phamlp_Sass_Tree_NodeException class.
 * @package			PHamlP
 * @subpackage	Sass.tree
 */
class Phamlp_Sass_Tree_NodeException extends Phamlp_Sass_Exception {}

/**
 * Phamlp_Sass_Tree_ContextException class.
 * @package			PHamlP
 * @subpackage	Sass.tree
 */
class Phamlp_Sass_Tree_ContextException extends Phamlp_Sass_Tree_NodeException {}

/**
 * Phamlp_Sass_Tree_Node_CommentException class.
 * @package			PHamlP
 * @subpackage	Sass.tree
 */
class Phamlp_Sass_Tree_Node_CommentException extends Phamlp_Sass_Tree_NodeException {}

/**
 * SassDebugNodeException class.
 * @package			PHamlP
 * @subpackage	Sass.tree
 */
class SassDebugNodeException extends Phamlp_Sass_Tree_NodeException {}

/**
 * Phamlp_Sass_Tree_Node_DirectiveException class.
 * @package			PHamlP
 * @subpackage	Sass.tree
 */
class Phamlp_Sass_Tree_Node_DirectiveException extends Phamlp_Sass_Tree_NodeException {}

/**
 * SassExtendNodeException class.
 * @package			PHamlP
 * @subpackage	Sass.tree
 */
class SassExtendNodeException extends Phamlp_Sass_Tree_NodeException {}

/**
 * SassForNodeException class.
 * @package			PHamlP
 * @subpackage	Sass.tree
 */
class SassForNodeException extends Phamlp_Sass_Tree_NodeException {}

/**
 * SassIfNodeException class.
 * @package			PHamlP
 * @subpackage	Sass.tree
 */
class SassIfNodeException extends Phamlp_Sass_Tree_NodeException {}

/**
 * Phamlp_Sass_Tree_Node_ImportException class.
 * @package			PHamlP
 * @subpackage	Sass.tree
 */
class Phamlp_Sass_Tree_Node_ImportException extends Phamlp_Sass_Tree_NodeException {}

/**
 * SassMixinDefinitionNodeException class.
 * @package			PHamlP
 * @subpackage	Sass.tree
 */
class SassMixinDefinitionNodeException extends Phamlp_Sass_Tree_NodeException {}

/**
 * SassMixinNodeException class.
 * @package			PHamlP
 * @subpackage	Sass.tree
 */
class SassMixinNodeException extends Phamlp_Sass_Tree_NodeException {}

/**
 * Phamlp_Sass_Tree_Node_PropertyException class.
 * @package			PHamlP
 * @subpackage	Sass.tree
 */
class Phamlp_Sass_Tree_Node_PropertyException extends Phamlp_Sass_Tree_NodeException {}

/**
 * SassRuleNodeException class.
 * @package			PHamlP
 * @subpackage	Sass.tree
 */
class SassRuleNodeException extends Phamlp_Sass_Tree_NodeException {}

/**
 * Phamlp_Sass_Tree_Node_VariableException class.
 * @package			PHamlP
 * @subpackage	Sass.tree
 */
class Phamlp_Sass_Tree_Node_VariableException extends Phamlp_Sass_Tree_NodeException {}

/**
 * SassWhileNodeException class.
 * @package			PHamlP
 * @subpackage	Sass.tree
 */
class SassWhileNodeException extends Phamlp_Sass_Tree_NodeException {}