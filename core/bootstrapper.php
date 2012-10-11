<?php defined('IN_APP') or die('Get out of here');

/**
 *    Scaffold v0.1
 *    by the Codin' Co.
 *
 *    Here lies the basic bootstrapper, which instantiates all the classes and loads
 *    all of the files up properly. Here goes!
 */

//  Strip magic quotes if it's enabled
if(get_magic_quotes_gpc() === 1) {
    $_GET = json_decode(stripslashes(json_encode($_GET, JSON_HEX_APOS)), true);
    $_POST = json_decode(stripslashes(json_encode($_POST, JSON_HEX_APOS)), true);
    $_COOKIE = json_decode(stripslashes(json_encode($_COOKIE, JSON_HEX_APOS)), true);
    $_REQUEST = json_decode(stripslashes(json_encode($_REQUEST, JSON_HEX_APOS)), true);
}

//  Set the default timezone to London if you don't have any set
if(!ini_get('date.timezone')) {
    date_default_timezone_set('Europe/London');
}
 
//  Load the rest of the config
$config = array();
$files = array('template', 'environment', 'language', 'routes', 'database', 'misc', 'crypt', 'session', 'file', 'csrf', 'email', 'error', 'image', 'cache');

//  Try loading the config files
//  If they don't work, log to an array for now

//  Get our badFiles array ready
$badFiles = array();

//  Loop all the config files to check they're good
foreach($files as $file) {
    $filename = BASE . 'config/' . $file . '.php';
    
    if(file_exists($filename)) {
        $config[] = $file;
        include $filename;
    } else {
        $badFiles[] = $filename;
    }
}

//  Include the functions
include_once CORE_BASE . 'functions.php';

//  Include the helper classes
$helpers = array('email', 'typography');
foreach($helpers as $helper) {
    //  Load, but don't instantiate
    fetch(CORE_BASE . 'helpers/' . $helper . '.php');
}

//  Our core classes to load
$classes = array('cache', 'globals', 'config', 'validator', 'file', 'error', 'crypt', 'database', 'session', 'csrf', 'response', 'ajax', 'image', 'input', 'url', 'routes', 'template', 'curl', 'helper');

//  Just load our class and we'll do the rest
$scaffoldPath = CORE_BASE . 'classes/scaffold.php';

//  Load Scaffold
if(file_exists($scaffoldPath)) {
    include_once $scaffoldPath;
    $scaffold = new Scaffold($config, $classes);
    
    if($config['env']['cli'] === true and PHP_SAPI === 'cli') {
        include_once CORE_BASE . 'cli/bootstrapper.php';
    } else {
        //  Load the default controller
        include_once CORE_BASE . 'defaults/controller.php';
        $controller = new Controller;
    }
} else {
    $badFiles[] = $scaffoldPath;
    die('Scaffold class not loaded. Sky is falling.');
}

//  When any errors get thrown, call our error class
//set_exception_handler(array('Error', 'exception'));
//set_error_handler(array('Error', 'native'));
//register_shutdown_function(array('Error', 'shutdown'));
