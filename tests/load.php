<?php
if (! defined('BASEPATH'))
    exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 5.1.6 or newer
 *
 * @package CodeIgniter
 * @author ExpressionEngine Dev Team
 * @copyright Copyright (c) 2008 - 2011, EllisLab, Inc.
 * @license http://codeigniter.com/user_guide/license.html
 * @link http://codeigniter.com
 * @since Version 1.0
 * @filesource
 *
 *
 *
 *
 *
 *
 */
    
// ------------------------------------------------------------------------

/**
 * System Initialization File
 *
 * Loads the base classes and executes the request.
 *
 * @package CodeIgniter
 * @subpackage codeigniter
 * @category Front-controller
 * @author ExpressionEngine Dev Team
 * @link http://codeigniter.com/user_guide/
 */

/**
 * CodeIgniter Version
 *
 * @var string
 *
 */
define('CI_VERSION', '2.1.4');

/**
 * CodeIgniter Branch (Core = TRUE, Reactor = FALSE)
 *
 * @var boolean
 *
 */
define('CI_CORE', FALSE);

/*
 * ------------------------------------------------------ Load the global functions ------------------------------------------------------
 */
require (BASEPATH . 'core/Common.php');

/*
 * ------------------------------------------------------ Load the framework constants ------------------------------------------------------
 */
if (defined('ENVIRONMENT') and file_exists(APPPATH . 'config/' . ENVIRONMENT . '/constants.php')) {
    require (APPPATH . 'config/' . ENVIRONMENT . '/constants.php');
} else {
    require (APPPATH . 'config/constants.php');
}

/*
 * ------------------------------------------------------ Define a custom error handler so we can log PHP errors ------------------------------------------------------
 */
set_error_handler('_exception_handler');

if (! is_php('5.3')) {
    @set_magic_quotes_runtime(0); // Kill magic quotes
}

/*
 * ------------------------------------------------------ Set the subclass_prefix ------------------------------------------------------ Normally the "subclass_prefix" is set in the config file. The subclass prefix allows CI to know if a core class is being extended via a library in the local application "libraries" folder. Since CI allows config items to be overriden via data set in the main index. php file, before proceeding we need to know if a subclass_prefix override exists. If so, we will set this value now, before any classes are loaded Note: Since the config file data is cached it doesn't hurt to load it here.
 */
if (isset($assign_to_config['subclass_prefix']) and $assign_to_config['subclass_prefix'] != '') {
    get_config(array(
        'subclass_prefix' => $assign_to_config['subclass_prefix']
    ));
}

/*
 * ------------------------------------------------------ Set a liberal script execution time limit ------------------------------------------------------
 */
if (function_exists("set_time_limit") == TRUE and @ini_get("safe_mode") == 0) {
    @set_time_limit(300);
}

/*
 * ------------------------------------------------------ Instantiate the hooks class ------------------------------------------------------
 */
$EXT = & load_class('Hooks', 'core');

/*
 * ------------------------------------------------------ Is there a "pre_system" hook? ------------------------------------------------------
 */
$EXT->_call_hook('pre_system');

/*
 * ------------------------------------------------------ Instantiate the config class ------------------------------------------------------
 */
$CFG = & load_class('Config', 'core');

// Do we have any manually set config items in the index.php file?
if (isset($assign_to_config)) {
    $CFG->_assign_to_config($assign_to_config);
}
$GLOBALS['CFG'] = & $CFG;
/*
 * ------------------------------------------------------ Instantiate the UTF-8 class ------------------------------------------------------ Note: Order here is rather important as the UTF-8 class needs to be used very early on, but it cannot properly determine if UTf-8 can be supported until after the Config class is instantiated.
 */

$UNI = & load_class('Utf8', 'core');
$GLOBALS['UNI'] = & $UNI;

/*
 * ------------------------------------------------------ Instantiate the URI class ------------------------------------------------------
 */
$URI = & load_class('URI', 'core');
$GLOBALS['URI'] = & $URI;
/*
 * ------------------------------------------------------ Instantiate the routing class and set the routing ------------------------------------------------------
 */

$RTR = & load_class('Router', 'core');

/*
 * ----------------------------------------------------- Load the security class for xss and csrf support -----------------------------------------------------
 */
$SEC = & load_class('Security', 'core');
$GLOBALS['SEC'] = & $SEC;

/*
 * ------------------------------------------------------ Load the Input class and sanitize globals ------------------------------------------------------
 */
$IN = & load_class('Input', 'core');
$GLOBALS['IN'] = & $IN;

/*
 * ------------------------------------------------------ Load the Language class ------------------------------------------------------
 */
$LANG = & load_class('Lang', 'core');
$GLOBALS['LANG'] = & $LANG;

/*
 * ------------------------------------------------------ Load the app controller and local controller ------------------------------------------------------
 */
// Load the base controller class
require BASEPATH . 'core/Controller.php';

function &get_instance()
{
    $the_instance = & CI_Controller::get_instance();
    if ($the_instance)
        return $the_instance;
    new CI_Controller();
    return CI_Controller::get_instance();
}

if (file_exists(APPPATH . 'core/' . $CFG->config['subclass_prefix'] . 'Controller.php')) {
    require APPPATH . 'core/' . $CFG->config['subclass_prefix'] . 'Controller.php';
}

/*
 * ------------------------------------------------------ Close the DB connection if one exists ------------------------------------------------------
 */
if (class_exists('CI_DB') and isset($CI->db)) {
    $CI->db->close();
}


/* End of file CodeIgniter.php */
/* Location: ./system/core/CodeIgniter.php */