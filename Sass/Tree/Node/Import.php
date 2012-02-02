<?php
/* SVN FILE: $Id$ */
/**
 * Phamlp_Sass_Tree_Node_Import class file.
 * @author			Chris Yates <chris.l.yates@gmail.com>
 * @copyright 	Copyright (c) 2010 PBM Web Development
 * @license			http://phamlp.googlecode.com/files/license.txt
 * @package			PHamlP
 * @subpackage	Sass.tree
 */

/**
 * Phamlp_Sass_Tree_Node_Import class.
 * Represents a CSS Import.
 * @package			PHamlP
 * @subpackage	Sass.tree
 */
class Phamlp_Sass_Tree_Node_Import extends Phamlp_Sass_Tree_Node {
	const IDENTIFIER = '@';
	const MATCH = '/^@import\s+(.+)/i';
	const MATCH_CSS = '/^(.+\.css|url\(.+\)|.+" \w+|"http)/im';
	const FILES = 1;

	/**
	 * @var array files to import
	 */
	private $files = array();

	/**
	 * Phamlp_Sass_Tree_Node_Import.
	 * @param object source token
	 * @return Phamlp_Sass_Tree_Node_Import
	 */
	public function __construct($token) {
		parent::__construct($token);
		preg_match(self::MATCH, $token->source, $matches);
		foreach (explode(',', $matches[self::FILES]) as $file) {
			$this->files[] = trim($file);
		}		
	}

	/**
	 * Parse this node.
	 * If the node is a CSS import return the CSS import rule.
	 * Else returns the rendered tree for the file.
	 * @param Phamlp_Sass_Tree_Context the context in which this node is parsed
	 * @return array the parsed node
	 */
	public function parse($context) {
		$imported = array();
		foreach ($this->files as $file) {
			if (preg_match(self::MATCH_CSS, $file)) {
				return "@import {$file}";
			}
			else {
				$file = trim($file, '\'"');
				$tree = Phamlp_Sass_File::getTree(
					Phamlp_Sass_File::getFile($file, $this->parser), $this->parser);
				if (empty($tree)) {
					throw new Phamlp_Sass_Tree_Node_ImportException('Unable to create document tree for {file}', array('{file}'=>$file), $this);
				}
				else {
					$imported = array_merge($imported, $tree->parse($context)->children);
				}
			}
		}
		return $imported;
	}
}