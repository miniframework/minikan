<?php
/**
 * define app HOME path
 */
date_default_timezone_set('Asia/Shanghai');
defined('RUNPATH') || define('RUNPATH', dirname(__FILE__)."/..");
defined('MINI_EXCEPTION_HANDLER') || define('MINI_EXCEPTION_HANDLER',true);
defined('MINI_ERROR_HANDLER') || define('MINI_ERROR_HANDLER',true);
defined('MINI_DEBUG') || define('MINI_DEBUG', true);

/**
 * include php miniframework import file
*/
$mini=dirname(__FILE__).'/../../../mini/mini.class.php';
include_once $mini;
/**
 * set config path
 */
$config = RUNPATH."/config/config.xml";

mini::run(RUNPATH,$config)->assembly(RUNPATH."/config/~autoload.php");