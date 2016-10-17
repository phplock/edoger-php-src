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
namespace Edoger\Http\Session\Handler;

use SessionHandlerInterface;

class File implements SessionHandlerInterface
{
	private $_timeout;
	private $_dir;

	public function __construct(array $config)
	{
		$this->_timeout	= $config['timeout'];
		$this->_dir		= $config['dir'];
	}

	public function open($path, $name)
	{
		if (!is_dir($this->_dir)) {
			mkdir($this->_dir, 0777, true);
		}

		return true;
	}

	public function close()
	{
		return true;
	}

	public function read($sid)
	{
		$file = $this->_dir.'/session_'.$sid;
		if (file_exists($file)) {
			$data = file_get_contents($file);
			if ($data !== false) {
				return $data;
			}
		}

		return '';
	}

	public function write($sid, $data)
	{
		$file = $this->_dir.'/session_'.$sid;
		return file_put_contents($file, $data) !== false;
	}

	public function destroy($sid)
	{
		$file = $this->_dir.'/session_'.$sid;
		if (file_exists($file)) {
			unlink($file);
		}

		return true;
	}

	public function gc($maxLifeTime)
	{
		$files = scandir($this->_dir);
		if (is_array($files)) {
			$now = time();
			foreach ($files as $file) {
				if (substr($file, 0, 8) === 'session_') {
					$file = $this->_dir.'/'.$file;
					if (filemtime($file) + $this->_timeout < $now && file_exists($file)) {
						unlink($file);
					}
				}
			}
		}

		return true;
	}
}