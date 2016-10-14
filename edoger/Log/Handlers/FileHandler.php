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
namespace Edoger\Log\Handlers;

class FileHandler
{
	private $_file = '';
	
	public function __construct(array $config)
	{
		$this->_file = $config['dir'].'/'.date($config['format']).'.log';
	}

	public function save(int $level, string $name, string $date, string $message)
	{
		error_log('['.$date.']['.$name.']'.$message.PHP_EOL, 3, $this->_file);
	}
}