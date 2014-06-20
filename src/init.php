<?php
/**
 * The following codes are important to properly run the application.
 * Avoid changing the values.
 */
defined('DS')?:define('DS', DIRECTORY_SEPARATOR);
define('FRAMEWORK_PATH',dirname(__FILE__));
define('APP_PATH',SITE_ROOT.DS.'app'.DS);
define('CONTROLLER_PATH',APP_PATH.'controller'.DS);
define('VIEW_PATH',APP_PATH.'view'.DS);
define('CONFIG_PATH',  APP_PATH.'config'.DS);
define('MODEL_PATH',APP_PATH.DS.'model'.DS);

define('CSS_URL','css/');
define('JS_URL','js'.DS);

//An array of class paths available in the app
$CLASS_PATHS = array(MODEL_PATH,CONTROLLER_PATH);

//An array to hold the essential classes
$CORE_CLASSES = include 'diviya_classes.php';

require_once 'functions.php';
loadClass('Application');


