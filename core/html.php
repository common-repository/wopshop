<?php
/**
* @version      1.0.0 01.06.2016
* @author       MAXXmarketing GmbH
* @package      WOPshop
* @copyright    Copyright (C) 2010 http://www.wop-agentur.com. All rights reserved.
* @license      GNU/GPL
* @class        WshopAddon
*/

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

abstract class WopshopHTML
{
	/**
	 * Option values related to the generation of HTML output. Recognized
	 * options are:
	 *     fmtDepth, integer. The current indent depth.
	 *     fmtEol, string. The end of line string, default is linefeed.
	 *     fmtIndent, string. The string to use for indentation, default is
	 *     tab.
	 *
	 * @var    array
	 * @since  1.0.0
	 */
	public static $formatOptions = array('format.depth' => 0, 'format.eol' => "\n", 'format.indent' => "\t");

	/**
	 * An array to hold included paths
	 *
	 * @var    array
	 * @since  1.0.0
	 */
	protected static $includePaths = array();

	/**
	 * An array to hold method references
	 *
	 * @var    array
	 * @since  1.0.0
	 */
	protected static $registry = array();

	/**
	 * Method to extract a key
	 *
	 * @param   string  $key  The name of helper method to load, (prefix).(class).function
	 *                        prefix and class are optional and can be used to load custom html helpers.
	 *
	 * @return  array  Contains lowercase key, prefix, file, function.
	 *
	 * @since   1.0.0
	 */
	protected static function extract($key)
	{
		$key = preg_replace('#[^A-Z0-9_\.]#i', '', $key);

		// Check to see whether we need to load a helper file
		$parts = explode('.', $key);

		$prefix = (count($parts) == 3 ? array_shift($parts) : 'Html');
		$file = (count($parts) == 2 ? array_shift($parts) : '');
		$func = array_shift($parts);

		return array(strtolower($prefix . '.' . $file . '.' . $func), $prefix, $file, $func);
	}

	/**
	 * Class loader method
	 *
	 * Additional arguments may be supplied and are passed to the sub-class.
	 * Additional include paths are also able to be specified for third-party use
	 *
	 * @param   string  $key  The name of helper method to load, (prefix).(class).function
	 *                        prefix and class are optional and can be used to load custom
	 *                        html helpers.
	 *
	 * @return  mixed  JWopshopHtml::call($function, $args) or False on error
	 *
	 * @since   1.0.0
	 * @throws  InvalidArgumentException
	 */
	public static function _($key)
	{
		list($key, $prefix, $file, $func) = static::extract($key);

		if (array_key_exists($key, static::$registry))
		{
			$function = static::$registry[$key];
			$args = func_get_args();

			// Remove function name from arguments
			array_shift($args);

			return static::call($function, $args);
		}

		$className = 'Wopshop' . $prefix . ucfirst($file);

		if (!class_exists($className))
		{
			//$path = Path::find(static::$includePaths, strtolower($file) . '.php');
                        $path = dirname(__FILE__) . "/" . strtolower($file) .  '.php';

			if ($path)
			{
				require_once $path;

				if (!class_exists($className))
				{
					throw new InvalidArgumentException(sprintf('%s not found.', $className), 500);
				}
			}
			else
			{
				throw new InvalidArgumentException(sprintf('%s %s not found.', $prefix, $file), 500);
			}
		}

		$toCall = array($className, $func);

		if (is_callable($toCall))
		{
			static::register($key, $toCall);
			$args = func_get_args();

			// Remove function name from arguments
			array_shift($args);

			return static::call($toCall, $args);
		}
		else
		{
			throw new InvalidArgumentException(sprintf('%s::%s not found.', $className, $func), 500);
		}
	}

	/**
	 * Registers a function to be called with a specific key
	 *
	 * @param   string  $key       The name of the key
	 * @param   string  $function  Function or method
	 *
	 * @return  boolean  True if the function is callable
	 *
	 * @since   1.0.0
	 */
	public static function register($key, $function)
	{
		list($key) = static::extract($key);

		if (is_callable($function))
		{
			static::$registry[$key] = $function;

			return true;
		}

		return false;
	}

	/**
	 * Removes a key for a method from registry.
	 *
	 * @param   string  $key  The name of the key
	 *
	 * @return  boolean  True if a set key is unset
	 *
	 * @since   1.0.0
	 */
	public static function unregister($key)
	{
		list($key) = static::extract($key);

		if (isset(static::$registry[$key]))
		{
			unset(static::$registry[$key]);

			return true;
		}

		return false;
	}

	/**
	 * Test if the key is registered.
	 *
	 * @param   string  $key  The name of the key
	 *
	 * @return  boolean  True if the key is registered.
	 *
	 * @since   1.0.0
	 */
	public static function isRegistered($key)
	{
		list($key) = static::extract($key);

		return isset(static::$registry[$key]);
	}

	/**
	 * Function caller method
	 *
	 * @param   callable  $function  Function or method to call
	 * @param   array     $args      Arguments to be passed to function
	 *
	 * @return  mixed   Function result or false on error.
	 *
	 * @see     http://php.net/manual/en/function.call-user-func-array.php
	 * @since   1.0.0
	 * @throws  InvalidArgumentException
	 */
	protected static function call($function, $args)
	{
		if (!is_callable($function))
		{
			throw new InvalidArgumentException('Function not supported', 500);
		}

		// PHP 5.3 workaround
		$temp = array();

		foreach ($args as &$arg)
		{
			$temp[] = &$arg;
		}

		return call_user_func_array($function, $temp);
	}

	/**
	 * Set format related options.
	 *
	 * Updates the formatOptions array with all valid values in the passed array.
	 *
	 * @param   array  $options  Option key/value pairs.
	 *
	 * @return  void
	 *
	 * @see     JWopshopHtml::$formatOptions
	 * @since   1.0.0
	 */
	public static function setFormatOptions($options)
	{
		foreach ($options as $key => $val)
		{
			if (isset(static::$formatOptions[$key]))
			{
				static::$formatOptions[$key] = $val;
			}
		}
	}
        
    /**
     * Generate bootstrap popover
     *
     * @param string $title Title of bootstrap popover
     *
     * @param string $content Content of bootstrap popover
     *
     * @param string $position Position of bootstrap popover (top, bottom, left, right, auto + direction) default value = top
     *
     * @return string Span with glyph icon and data to display bootstrap popover
     *
     * @since 1.0.0
     */
        public static function popover($title, $content, $position = 'top')
        {
            
            return $popover = '
                <span
                    class="glyphicon glyphicon-question-sign wshop-icon wshop-popover-icon"
                    data-toggle="popover"
                    data-placement="'.esc_attr($position).'"
                    data-trigger="hover"
                    title="'.esc_attr($title).'"
                    data-content="'.esc_attr($content).'">
                </span>
            ';
        }
        
        /**
         * Generate tooltip
         * 
         * @since 1.0.0
         */
        public static function tooltip($title)
        {
            return '<span class="wshop-tooltip">&#9432;
                <span class="tooltiptext">'.esc_html($title).'</span>
              </span>';
        }
}