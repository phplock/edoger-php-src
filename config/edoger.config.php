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

// This string is used to encrypt your security cookie, please do not leak it.
// You should always change it. If you are the first installation, please replace it immediately.
$conf['cookie_secret_key']		= 'ORqCwo4wmMNJhutnB4CSYq6m9KqQAvoH';

// This is the default controller name, action name and view name for the system.
$conf['default_controller']		= 'index';
$conf['default_action']			= 'index';
$conf['default_view']			= 'index';

// Controller and model and user extend namespace name.
$conf['model_namespace']		= '\\App\\Model\\';
$conf['controller_namespace']	= '\\App\\Controller\\';
$conf['user_extend_namespace']	= '\\App\\Extend\\';

// View directory and view extension name.
$conf['view_directory']			= APP_PATH.'/View';
$conf['view_extension_name']	= 'phtml';

// Database configuration information.
// If you need to use the database, then these must be configured.
$conf['mysql_driver']			= 'pdo';

$conf['mysql_host']				= '';
$conf['mysql_port']				= '';
$conf['mysql_socket']			= '';
$conf['mysql_dbname']			= '';
$conf['mysql_charset']			= '';
$conf['mysql_username']			= '';
$conf['mysql_password']			= '';

$conf['mysql_prefix']			= '';

// -------------------------------------------------------------------------------------------------
return $conf;
