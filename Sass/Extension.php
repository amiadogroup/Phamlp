<?php
/**
 * Phamlp
 * 
 * @author Jonatan Männchen <jonatan.maennchen@amiadogroup.com>
 * @copyright Copyright (c) 2012 Amiado Group AG
 * @license https://raw.github.com/amiado-maennchen/Phamlp/master/LICENCE
 * @package PHamlP
 */

/**
 * Phamlp_Sass_Extension class.
 * 
 * @package PHamlP
 * @subpackage Sass
 */
abstract class Phamlp_Sass_Extension {
        /**
         * Construct with some Options
         *
         * @param mixed $options
         * @return void
         */
        abstract public function __construct($options = null);
        
        /**
         * Delivers Framework-Path to Parser
         *
         * @return array
         */
        abstract public function getFrameworkPaths();
        
        /**
         * Delivers Functions-Path to Parser
         * 
         * @example Return in this Format:
         * array<array<string Namespace, string Path>>
         * 
         * @return array
         */
        abstract public function getFunctionsPaths();
}