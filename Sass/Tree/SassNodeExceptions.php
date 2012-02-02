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
 * Phamlp_Sass_Tree_CommentNodeException class.
 * @package			PHamlP
 * @subpackage	Sass.tree
 */
class Phamlp_Sass_Tree_CommentNodeException extends Phamlp_Sass_Tree_NodeException {}

/**
 * SassDebugNodeException class.
 * @package			PHamlP
 * @subpackage	Sass.tree
 */
class SassDebugNodeException extends Phamlp_Sass_Tree_NodeException {}

/**
 * SassDirectiveNodeException class.
 * @package			PHamlP
 * @subpackage	Sass.tree
 */
class SassDirectiveNodeException extends Phamlp_Sass_Tree_NodeException {}

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
 * SassImportNodeException class.
 * @package			PHamlP
 * @subpackage	Sass.tree
 */
class SassImportNodeException extends Phamlp_Sass_Tree_NodeException {}

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
 * SassPropertyNodeException class.
 * @package			PHamlP
 * @subpackage	Sass.tree
 */
class SassPropertyNodeException extends Phamlp_Sass_Tree_NodeException {}

/**
 * SassRuleNodeException class.
 * @package			PHamlP
 * @subpackage	Sass.tree
 */
class SassRuleNodeException extends Phamlp_Sass_Tree_NodeException {}

/**
 * SassVariableNodeException class.
 * @package			PHamlP
 * @subpackage	Sass.tree
 */
class SassVariableNodeException extends Phamlp_Sass_Tree_NodeException {}

/**
 * SassWhileNodeException class.
 * @package			PHamlP
 * @subpackage	Sass.tree
 */
class SassWhileNodeException extends Phamlp_Sass_Tree_NodeException {}