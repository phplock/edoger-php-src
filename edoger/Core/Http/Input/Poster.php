<?php
/**
 * Edoger PHP Framework (EdogerPHP)
 * 
 * A simple and efficient PHP framework.
 *
 * By REENT (Qingshan Luo)
 * Version 1.0.0
 *
 * http://www.edoger.com/
 *
 * The MIT License (MIT)
 * Copyright (c) 2016 REENT (Qingshan Luo)
 * Permission is hereby granted, free of charge, to any person obtaining a 
 * copy of this software and associated documentation files (the “Software”), 
 * to deal in the Software without restriction, including without limitation 
 * the rights to use, copy, modify, merge, publish, distribute, sublicense, 
 * and/or sell copies of the Software, and to permit persons to whom the 
 * Software is furnished to do so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in 
 * all copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED “AS IS”, WITHOUT WARRANTY OF ANY KIND, 
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF 
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. 
 * IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, 
 * DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR 
 * OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE 
 * USE OR OTHER DEALINGS IN THE SOFTWARE.
 */
namespace Edoger\Core\Http\Input;

/**
 * ================================================================================
 *
 * ================================================================================
 */
class Poster
{
	/**
	 * ----------------------------------------------------------------------------
	 * [fetch description]
	 * ----------------------------------------------------------------------------
	 * 
	 * @param  [type] $key      [description]
	 * @param  [type] $def      [description]
	 * @param  [type] $filter   [description]
	 * @param  [type] $modifier [description]
	 * @return [type]           [description]
	 */
	public function fetch($key, $def = null, $filter = null, $modifier = null)
	{
		$data = [null, 0];

		if (isset($_POST[$key])) {
			if ($filter !== null && !Filter::call($filter, $_POST[$key])) {
				$data[0] = $def;
				$data[1] = 2;
			} else {
				if ($modifier === null) {
					$data[0] = $_POST[$key];
				} else {
					$data[0] = Modifier::call($modifier, $_POST[$key]);
					if ($data[1] === null) {
						$data[0] = 4;
					}
				}
			}
		} else {
			$data[0] = $def;
			$data[1] = 1;
		}

		return $data;
	}

	/**
	 * ----------------------------------------------------------------------------
	 * [search description]
	 * ----------------------------------------------------------------------------
	 * 
	 * @param  array  $keys     [description]
	 * @param  [type] $def      [description]
	 * @param  [type] $filter   [description]
	 * @param  [type] $modifier [description]
	 * @return [type]           [description]
	 */
	public static function search(array $keys, $def = null, $filter = null, $modifier = null)
	{
		$data = [null, 0, ''];

		foreach ($keys as $v) {
			if (isset($_POST[$v])) {
				
				$data[2] = $v;

				if ($filter !== null && !Filter::call($filter, $_POST[$v])) {
					$data[0] = $def;
					$data[1] = 2;
				} else {
					if ($modifier === null) {
						$data[0] = $_POST[$v];
					} else {
						$data[0] = Modifier::call($modifier, $_POST[$v]);
						if ($data[1] === null) {
							$data[0] = 4;
						}
					}
				}

				return $data;
			}
		}

		$data[0] = $def;
		$data[1] = 1;

		return $data;
	}

	/**
	 * ----------------------------------------------------------------------------
	 * [has description]
	 * ----------------------------------------------------------------------------
	 * 
	 * @param  string  $key [description]
	 * @return boolean      [description]
	 */
	public function has(string $key)
	{
		return isset($_POST[$key]);
	}
}