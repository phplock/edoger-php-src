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
 *
 * @author [name] <[<email address>]>
 * @var [type] [<description>]
 * @package [level 1]\[level 2]\[etc.]
 * @param [type] $[name] [<description>]
 * @return [type] [<description>]
 * @version [<vector>] [<description>]
 * @copyright [description]
 * @category [description]
 * @deprecated [<version>] [<description>]
 * @example [URI] [<description>]
 * @global [type] [name | description]
 * @internal [<description>]
 * @link [URI] [<description>]
 * @license [<url>] [name]
 * @see [URI | FQSEN] [<description>]
 * @since [version> [<description>]
 * @subpackage [name]
 * @throws [type] [<description>]
 * @todo [description]
 * @uses [FQSEN] [<description>]
 *

 */

/**
 * --------------------------------------------------------------------------------
 * The Edoger PHP Framework Root Directory.
 * --------------------------------------------------------------------------------
 * 
 * This is the initial search directory for automatic loader.
 */
const EDOGER_ROOT = __DIR__;


/**
 * --------------------------------------------------------------------------------
 * Registered Automatic Loader.
 * --------------------------------------------------------------------------------
 * 
 * Automatic loader is responsible for loading the various components provided by 
 * the framework.
 */
spl_autoload_register(

	function(string $class){
		
		//	Create the class file path.
		$path = preg_replace(
			['/\\\\/', '/^Edoger/'],
			['/', EDOGER_ROOT],
			$class
			) . '.php';

		if (file_exists($path)) {
			require $path;
		}
		
	}, true, true);


/**
 * --------------------------------------------------------------------------------
 * Gets The Framework Kernel Instance Object.
 * --------------------------------------------------------------------------------
 * 
 * This should be the preferred method of scheduling framework in the application.
 *
 * @return Edoger\Core\Kernel
 */
function edoger()
{
	return Edoger\Core\Kernel::core();
}
