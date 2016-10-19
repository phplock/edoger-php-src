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

// Controller and model namespace name.
$conf['model_namespace']		= '\\App\\Model\\';
$conf['controller_namespace']	= '\\App\\Controller\\';

// View directory and view extension name.
$conf['view_directory']			= APP_PATH.'/View';
$conf['view_extension_name']	= 'phtml';

// -------------------------------------------------------------------------------------------------
return $conf;
