<?php
/*
 +-----------------------------------------------------------------------------+
 | Edoger PHP Framework (EdogerPHP)                                            |
 +-----------------------------------------------------------------------------+
 | Copyright (c) 2014 - 2016 QingShan Luo                                      |
 +-----------------------------------------------------------------------------+
 | The MIT License (MIT)                                                       |
 |                                                                             |
 | Permission is hereby granted, free of charge, to any person obtaining a     |
 | copy of this software and associated documentation files (the “Software”),  |
 | to deal in the Software without restriction, including without limitation   |
 | the rights to use, copy, modify, merge, publish, distribute, sublicense,    |
 | and/or sell copies of the Software, and to permit persons to whom the       |
 | Software is furnished to do so, subject to the following conditions:        |
 |                                                                             |
 | The above copyright notice and this permission notice shall be included in  |
 | all copies or substantial portions of the Software.                         |
 |                                                                             |
 | THE SOFTWARE IS PROVIDED “AS IS”, WITHOUT WARRANTY OF ANY KIND,             |
 | EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF          |
 | MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.      |
 | IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, |
 | DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR       |
 | OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE   |
 | USE OR OTHER DEALINGS IN THE SOFTWARE.                                      |
 +-----------------------------------------------------------------------------+
 |  License: MIT                                                               |
 +-----------------------------------------------------------------------------+
 |  Authors: QingShan Luo <shanshan.lqs@gmail.com>                             |
 +-----------------------------------------------------------------------------+
 */
namespace Edoger\Database\Mysql\Builder;

use PDO;

/**
 * -----------------------------------------------------------------------------
 * equal
 * notEqual
 * isNull
 * isNotNull
 * less
 * lessEqual
 * greater
 * greaterEqual
 * inArray
 * notInArray
 * between
 * notBetween
 * like
 * notLike
 * regexp
 * notRegexp
 * exists
 * notExists
 * equalSubquery
 * notEqualSubquery
 * lessSubquery
 * lessEqualSubquery
 * greaterSubquery
 * greaterEqualSubquery
 * equalAnySubquery
 * notEqualAnySubquery
 * lessAnySubquery
 * lessEqualAnySubquery
 * greaterAnySubquery
 * greaterEqualAnySubquery
 * equalAllSubquery
 * notEqualAllSubquery
 * lessAllSubquery
 * lessEqualAllSubquery
 * greaterAllSubquery
 * greaterEqualAllSubquery
 * groupAnd
 * groupOr
 * -----------------------------------------------------------------------------
 */
class Filter
{
	/**
	 * -------------------------------------------------------------------------
	 * [$mode description]
	 * -------------------------------------------------------------------------
	 * 
	 * @var string
	 */
	public $mode 	= '';

	/**
	 * -------------------------------------------------------------------------
	 * [$where description]
	 * -------------------------------------------------------------------------
	 * 
	 * @var array
	 */
	public $where 	= [];

	/**
	 * -------------------------------------------------------------------------
	 * [equal description]
	 * -------------------------------------------------------------------------
	 * 
	 * @param  string $field [description]
	 * @param  string $value [description]
	 * @return [type]        [description]
	 */
	public function equal(string $field, string $value)
	{
		$this -> where[] = [1, $field, ' = ?', $value];
		return $this;
	}

	/**
	 * -------------------------------------------------------------------------
	 * [notEqual description]
	 * -------------------------------------------------------------------------
	 * 
	 * @param  string $field [description]
	 * @param  string $value [description]
	 * @return [type]        [description]
	 */
	public function notEqual(string $field, string $value)
	{
		$this -> where[] = [1, $field, ' != ?', $value];
		return $this;
	}

	/**
	 * -------------------------------------------------------------------------
	 * [isNull description]
	 * -------------------------------------------------------------------------
	 * 
	 * @param  string  $field [description]
	 * @return boolean        [description]
	 */
	public function isNull(string $field)
	{
		$this -> where[] = [2, $field, ' IS NULL'];
		return $this;
	}

	/**
	 * -------------------------------------------------------------------------
	 * [isNotNull description]
	 * -------------------------------------------------------------------------
	 * 
	 * @param  string  $field [description]
	 * @return boolean        [description]
	 */
	public function isNotNull(string $field)
	{
		$this -> where[] = [2, $field, ' IS NOT NULL'];
		return $this;
	}

	/**
	 * -------------------------------------------------------------------------
	 * [less description]
	 * -------------------------------------------------------------------------
	 * 
	 * @param  string $field [description]
	 * @param  string $value [description]
	 * @return [type]        [description]
	 */
	public function less(string $field, string $value)
	{
		$this -> where[] = [1, $field, ' < ?', $value];
		return $this;
	}

	/**
	 * -------------------------------------------------------------------------
	 * [lessEqual description]
	 * -------------------------------------------------------------------------
	 * 
	 * @param  string $field [description]
	 * @param  string $value [description]
	 * @return [type]        [description]
	 */
	public function lessEqual(string $field, string $value)
	{
		$this -> where[] = [1, $field, ' <= ?', $value];
		return $this;
	}

	/**
	 * -------------------------------------------------------------------------
	 * [greater description]
	 * -------------------------------------------------------------------------
	 * 
	 * @param  string $field [description]
	 * @param  string $value [description]
	 * @return [type]        [description]
	 */
	public function greater(string $field, string $value)
	{
		$this -> where[] = [1, $field, ' > ?', $value];
		return $this;
	}

	/**
	 * -------------------------------------------------------------------------
	 * [greaterEqual description]
	 * -------------------------------------------------------------------------
	 * 
	 * @param  string $field [description]
	 * @param  string $value [description]
	 * @return [type]        [description]
	 */
	public function greaterEqual(string $field, string $value)
	{
		$this -> where[] = [1, $field, ' >= ?', $value];
		return $this;
	}

	/**
	 * -------------------------------------------------------------------------
	 * [inArray description]
	 * -------------------------------------------------------------------------
	 * 
	 * @param  string $field [description]
	 * @param  array  $value [description]
	 * @return [type]        [description]
	 */
	public function inArray(string $field, array $value)
	{
		if (!empty($value)) {
			$value = array_filter(
				array_unique($value, SORT_STRING),
				function($v){
					return is_string($v) || is_numeric($v);
				}
				);
			if (!empty($value)) {
				$size = count($value);
				if ($size === 1) {
					$this -> equal($field, reset($value));
				} else {
					$this -> where[] = [
						1,
						$field,
						' IN (' . implode(',', array_fill(0, $size, '?')) . ')',
						$value
					];
				}
			}
		}
		return $this;
	}

	/**
	 * -------------------------------------------------------------------------
	 * [notInArray description]
	 * -------------------------------------------------------------------------
	 * 
	 * @param  string $field [description]
	 * @param  array  $value [description]
	 * @return [type]        [description]
	 */
	public function notInArray(string $field, array $value)
	{
		if (!empty($value)) {
			$value = array_filter(
				array_unique($value, SORT_STRING),
				function($v){
					return is_string($v) || is_numeric($v);
				}
				);
			if (!empty($value)) {
				$size = count($value);
				if ($size === 1) {
					$this -> notEqual($field, reset($value));
				} else {
					$this -> where[] = [
						1,
						$field,
						' NOT IN (' . implode(',', array_fill(0, $size, '?')) . ')',
						$value
					];
				}
			}
		}
		return $this;
	}

	/**
	 * -------------------------------------------------------------------------
	 * [between description]
	 * -------------------------------------------------------------------------
	 * 
	 * @param  string $field [description]
	 * @param  string $start [description]
	 * @param  string $end   [description]
	 * @return [type]        [description]
	 */
	public function between(string $field, string $start, string $end)
	{
		$this -> where[] = [4, $field, ' BETWEEN ? AND ?', $start, $end];
		return $this;
	}

	/**
	 * -------------------------------------------------------------------------
	 * [notBetween description]
	 * -------------------------------------------------------------------------
	 * 
	 * @param  string $field [description]
	 * @param  string $start [description]
	 * @param  string $end   [description]
	 * @return [type]        [description]
	 */
	public function notBetween(string $field, string $start, string $end)
	{
		$this -> where[] = [4, $field, ' NOT BETWEEN ? AND ?', $start, $end];
		return $this;
	}

	/**
	 * -------------------------------------------------------------------------
	 * [like description]
	 * -------------------------------------------------------------------------
	 * 
	 * @param  string $field [description]
	 * @param  string $value [description]
	 * @return [type]        [description]
	 */
	public function like(string $field, string $value)
	{
		if (preg_match('/[_%]/', $value)) {
			$this -> where[] = [1, $field, ' LIKE ?', $value];
		} else {
			$this -> equal($field, $value);
		}
		return $this;
	}

	/**
	 * -------------------------------------------------------------------------
	 * [notLike description]
	 * -------------------------------------------------------------------------
	 * 
	 * @param  string $field [description]
	 * @param  string $value [description]
	 * @return [type]        [description]
	 */
	public function notLike(string $field, string $value)
	{
		if (preg_match('/[_%]/', $value)) {
			$this -> where[] = [1, $field, ' NOT LIKE ?', $value];
		} else {
			$this -> notEqual($field, $value);
		}
		return $this;
	}

	/**
	 * -------------------------------------------------------------------------
	 * [regexp description]
	 * -------------------------------------------------------------------------
	 * 
	 * @param  string       $field  [description]
	 * @param  string       $value  [description]
	 * @param  bool|boolean $binary [description]
	 * @return [type]               [description]
	 */
	public function regexp(string $field, string $value, bool $binary = false)
	{
		if ($binary) {
			$this -> where[] = [1, $field, ' REGEXP BINARY ?', $value];
		} else {
			$this -> where[] = [1, $field, ' REGEXP ?', $value];
		}
		return $this;
	}

	/**
	 * -------------------------------------------------------------------------
	 * [notRegexp description]
	 * -------------------------------------------------------------------------
	 * 
	 * @param  string       $field  [description]
	 * @param  string       $value  [description]
	 * @param  bool|boolean $binary [description]
	 * @return [type]               [description]
	 */
	public function notRegexp(string $field, string $value, bool $binary = false)
	{
		if ($binary) {
			$this -> where[] = [1, $field, ' NOT REGEXP BINARY ?', $value];
		} else {
			$this -> where[] = [1, $field, ' NOT REGEXP ?', $value];
		}
		return $this;
	}

	/**
	 * -------------------------------------------------------------------------
	 * [exists description]
	 * -------------------------------------------------------------------------
	 * 
	 * @param  callable $callback [description]
	 * @param  [type]   $param    [description]
	 * @return [type]             [description]
	 */
	public function exists(callable $callback, $param = null)
	{

	}

	/**
	 * -------------------------------------------------------------------------
	 * [notExists description]
	 * -------------------------------------------------------------------------
	 * 
	 * @param  callable $callback [description]
	 * @param  [type]   $param    [description]
	 * @return [type]             [description]
	 */
	public function notExists(callable $callback, $param = null)
	{
		
	}

	/**
	 * -------------------------------------------------------------------------
	 * [equalSubquery description]
	 * -------------------------------------------------------------------------
	 * 
	 * @param  string   $field    [description]
	 * @param  callable $callback [description]
	 * @param  [type]   $param    [description]
	 * @return [type]             [description]
	 */
	public function equalSubquery(string $field, callable $callback, $param = null)
	{

	}

	/**
	 * -------------------------------------------------------------------------
	 * [notEqualSubquery description]
	 * -------------------------------------------------------------------------
	 * 
	 * @param  string   $field    [description]
	 * @param  callable $callback [description]
	 * @param  [type]   $param    [description]
	 * @return [type]             [description]
	 */
	public function notEqualSubquery(string $field, callable $callback, $param = null)
	{

	}

	/**
	 * -------------------------------------------------------------------------
	 * [lessSubquery description]
	 * -------------------------------------------------------------------------
	 * 
	 * @param  string   $field    [description]
	 * @param  callable $callback [description]
	 * @param  [type]   $param    [description]
	 * @return [type]             [description]
	 */
	public function lessSubquery(string $field, callable $callback, $param = null)
	{

	}

	/**
	 * -------------------------------------------------------------------------
	 * [lessEqualSubquery description]
	 * -------------------------------------------------------------------------
	 * 
	 * @param  string   $field    [description]
	 * @param  callable $callback [description]
	 * @param  [type]   $param    [description]
	 * @return [type]             [description]
	 */
	public function lessEqualSubquery(string $field, callable $callback, $param = null)
	{

	}

	/**
	 * -------------------------------------------------------------------------
	 * [greaterSubquery description]
	 * -------------------------------------------------------------------------
	 * 
	 * @param  string   $field    [description]
	 * @param  callable $callback [description]
	 * @param  [type]   $param    [description]
	 * @return [type]             [description]
	 */
	public function greaterSubquery(string $field, callable $callback, $param = null)
	{

	}

	/**
	 * -------------------------------------------------------------------------
	 * [greaterEqualSubquery description]
	 * -------------------------------------------------------------------------
	 * 
	 * @param  string   $field    [description]
	 * @param  callable $callback [description]
	 * @param  [type]   $param    [description]
	 * @return [type]             [description]
	 */
	public function greaterEqualSubquery(string $field, callable $callback, $param = null)
	{

	}

	/**
	 * -------------------------------------------------------------------------
	 * [equalAnySubquery description]
	 * -------------------------------------------------------------------------
	 * 
	 * @param  string   $field    [description]
	 * @param  callable $callback [description]
	 * @param  [type]   $param    [description]
	 * @return [type]             [description]
	 */
	public function equalAnySubquery(string $field, callable $callback, $param = null)
	{

	}

	/**
	 * -------------------------------------------------------------------------
	 * [notEqualAnySubquery description]
	 * -------------------------------------------------------------------------
	 * 
	 * @param  string   $field    [description]
	 * @param  callable $callback [description]
	 * @param  [type]   $param    [description]
	 * @return [type]             [description]
	 */
	public function notEqualAnySubquery(string $field, callable $callback, $param = null)
	{

	}

	/**
	 * -------------------------------------------------------------------------
	 * [lessAnySubquery description]
	 * -------------------------------------------------------------------------
	 * 
	 * @param  string   $field    [description]
	 * @param  callable $callback [description]
	 * @param  [type]   $param    [description]
	 * @return [type]             [description]
	 */
	public function lessAnySubquery(string $field, callable $callback, $param = null)
	{

	}

	/**
	 * -------------------------------------------------------------------------
	 * [lessEqualAnySubquery description]
	 * -------------------------------------------------------------------------
	 * 
	 * @param  string   $field    [description]
	 * @param  callable $callback [description]
	 * @param  [type]   $param    [description]
	 * @return [type]             [description]
	 */
	public function lessEqualAnySubquery(string $field, callable $callback, $param = null)
	{

	}

	/**
	 * -------------------------------------------------------------------------
	 * [greaterAnySubquery description]
	 * -------------------------------------------------------------------------
	 * 
	 * @param  string   $field    [description]
	 * @param  callable $callback [description]
	 * @param  [type]   $param    [description]
	 * @return [type]             [description]
	 */
	public function greaterAnySubquery(string $field, callable $callback, $param = null)
	{

	}

	/**
	 * -------------------------------------------------------------------------
	 * [greaterEqualAnySubquery description]
	 * -------------------------------------------------------------------------
	 * 
	 * @param  string   $field    [description]
	 * @param  callable $callback [description]
	 * @param  [type]   $param    [description]
	 * @return [type]             [description]
	 */
	public function greaterEqualAnySubquery(string $field, callable $callback, $param = null)
	{

	}

	/**
	 * -------------------------------------------------------------------------
	 * [equalAllSubquery description]
	 * -------------------------------------------------------------------------
	 * 
	 * @param  string   $field    [description]
	 * @param  callable $callback [description]
	 * @param  [type]   $param    [description]
	 * @return [type]             [description]
	 */
	public function equalAllSubquery(string $field, callable $callback, $param = null)
	{

	}

	/**
	 * -------------------------------------------------------------------------
	 * [notEqualAllSubquery description]
	 * -------------------------------------------------------------------------
	 * 
	 * @param  string   $field    [description]
	 * @param  callable $callback [description]
	 * @param  [type]   $param    [description]
	 * @return [type]             [description]
	 */
	public function notEqualAllSubquery(string $field, callable $callback, $param = null)
	{

	}

	/**
	 * -------------------------------------------------------------------------
	 * [lessAllSubquery description]
	 * -------------------------------------------------------------------------
	 * 
	 * @param  string   $field    [description]
	 * @param  callable $callback [description]
	 * @param  [type]   $param    [description]
	 * @return [type]             [description]
	 */
	public function lessAllSubquery(string $field, callable $callback, $param = null)
	{

	}

	/**
	 * -------------------------------------------------------------------------
	 * [lessEqualAllSubquery description]
	 * -------------------------------------------------------------------------
	 * 
	 * @param  string   $field    [description]
	 * @param  callable $callback [description]
	 * @param  [type]   $param    [description]
	 * @return [type]             [description]
	 */
	public function lessEqualAllSubquery(string $field, callable $callback, $param = null)
	{

	}

	/**
	 * -------------------------------------------------------------------------
	 * [greaterAllSubquery description]
	 * -------------------------------------------------------------------------
	 * 
	 * @param  string   $field    [description]
	 * @param  callable $callback [description]
	 * @param  [type]   $param    [description]
	 * @return [type]             [description]
	 */
	public function greaterAllSubquery(string $field, callable $callback, $param = null)
	{

	}

	/**
	 * -------------------------------------------------------------------------
	 * [greaterEqualAllSubquery description]
	 * -------------------------------------------------------------------------
	 * 
	 * @param  string   $field    [description]
	 * @param  callable $callback [description]
	 * @param  [type]   $param    [description]
	 * @return [type]             [description]
	 */
	public function greaterEqualAllSubquery(string $field, callable $callback, $param = null)
	{

	}

	/**
	 * -------------------------------------------------------------------------
	 * [groupAnd description]
	 * -------------------------------------------------------------------------
	 * 
	 * @param  callable $callback [description]
	 * @param  [type]   $param    [description]
	 * @return [type]             [description]
	 */
	public function groupAnd(callable $callback, $param = null)
	{
		$filter = clone $this;
		call_user_func($callback, $filter, $param);

		if (!empty($filter -> where)) {
			$filter -> mode = 'and';
			$this -> where[] = $filter;
		}

		return $this;
	}
	
	/**
	 * -------------------------------------------------------------------------
	 * [groupOr description]
	 * -------------------------------------------------------------------------
	 * 
	 * @param  callable $callback [description]
	 * @param  [type]   $param    [description]
	 * @return [type]             [description]
	 */
	public function groupOr(callable $callback, $param = null)
	{
		$filter = clone $this;
		call_user_func($callback, $filter, $param);

		if (!empty($filter -> where)) {
			$filter -> mode = 'or';
			$this -> where[] = $filter;
		}

		return $this;
	}


	/**
	 * -------------------------------------------------------------------------
	 * [__clone description]
	 * -------------------------------------------------------------------------
	 * 
	 * @return [type] [description]
	 */
	public function __clone()
	{
		$this -> mode 	= '';
		$this -> where 	= [];
	}
}