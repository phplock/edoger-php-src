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
namespace Edoger\Log\Handler;

use Edoger\Log\LoggerHandlerInterface;

class File implements LoggerHandlerInterface
{
	private $_file = '';
	
	public function __construct(array $config)
	{
		if (!is_dir($config['dir'])) {
			mkdir($config['dir'], 0777, true);
		}

		$this->_file = $config['dir'].'/'.date($config['format']).'.'.$config['ext'];
	}

	public function save(int $level, string $name, string $date, string $message)
	{
		error_log('['.$date.']['.$name.']'.$message.PHP_EOL, 3, $this->_file);
	}
}