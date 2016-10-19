<?php
/**
 *+------------------------------------------------------------------------------------------------+
 *| Edoger PHP Framework                                                                           |
 *+------------------------------------------------------------------------------------------------+
 *| A simple and efficient PHP framework.                                                          |
 *+------------------------------------------------------------------------------------------------+
 *| @package   edoger-php-src                                                                      |
 *| @license   MIT                                                                                 |
 *| @link      https://www.edoger.com/                                                             |
 *| @copyright Copyright (c) 2014 - 2016, QingShan Luo                                             |
 *| @version   1.0.0 Alpha                                                                         |
 *+------------------------------------------------------------------------------------------------+
 *| @author    Qingshan Luo <shanshan.lqs@gmail.com>                                               |
 *+------------------------------------------------------------------------------------------------+
 */
$conf = [];
// -------------------------------------------------------------------------------------------------

$conf['cookie_secret_key']		= 'ORqCwo4wmMNJhutnB4CSYq6m9KqQAvoH';
$conf['cookie_expire']			= 86400;
$conf['cookie_path']			= '/';
$conf['cookie_domain']			= '';
$conf['cookie_secure']			= false;
$conf['cookie_httponly']		= false;

$conf['default_controller']		= 'index';
$conf['default_action']			= 'index';

$conf['model_namespace']		= '\\App\\Model\\';
$conf['controller_namespace']	= '\\App\\Controller\\';
$conf['view_directory']			= APP_PATH.'/View';
$conf['view_extension_name']	= 'phtml';

// -------------------------------------------------------------------------------------------------
return $conf;
