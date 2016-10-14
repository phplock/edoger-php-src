<?php
/**
 *+------------------------------------------------------------------------------------------------+
 *| Edoger PHP Framework                                                                           |
 *+------------------------------------------------------------------------------------------------+
 *| A simple route analysis and matching module.                                                   |
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

//	Version string.
const EDOGER_VERSION					= '1.0.0 Alpha';

//	Log levels.
const EDOGER_LEVEL_DEBUG				= 1;
const EDOGER_LEVEL_INFO					= 2;
const EDOGER_LEVEL_NOTICE				= 4;
const EDOGER_LEVEL_WARNING				= 8;
const EDOGER_LEVEL_ERROR				= 16;
const EDOGER_LEVEL_CRITICAL				= 32;
const EDOGER_LEVEL_ALERT				= 64;
const EDOGER_LEVEL_EMERGENCY			= 128;
const EDOGER_LEVEL_UNKNOWN				= 256;

//	Error levels.
const EDOGER_ERROR_NOTFOUND_CONTROLLER	= 101;
const EDOGER_ERROR_NOTFOUND_EXTEND		= 102;
const EDOGER_ERROR_NOTFOUND_ROUTE		= 103;
const EDOGER_ERROR_NOTFOUND_VIEW		= 104;
const EDOGER_ERROR_NOTFOUND_MIDDLEWARE	= 105;
const EDOGER_ERROR_NOTFOUND_FILE		= 106;

const EDOGER_ERROR_CONFIG				= 801;
const EDOGER_ERROR_RUNTIME				= 802;
const EDOGER_ERROR_ARGUMENT				= 803;
const EDOGER_ERROR_METHOD				= 804;
const EDOGER_ERROR_CONNECT				= 805;