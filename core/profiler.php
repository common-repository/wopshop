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

class WopshopProfiler extends WopshopWobject
{
	protected $_start = 0;

	protected $_prefix = '';

	protected $_buffer = null;

	protected $_previous_time = 0.0;

	protected $_previous_mem = 0.0;

	protected $_iswin = false;

	protected static $instances = array();

	public function __construct($prefix = '') {
		$this->_start = $this->getmicrotime();
		$this->_prefix = $prefix;
		$this->_buffer = array();
		$this->_iswin = (substr(PHP_OS, 0, 3) == 'WIN');
	}

	public static function getInstance($prefix = '') {
		if (empty(self::$instances[$prefix]))
		{
			self::$instances[$prefix] = new WopshopProfiler($prefix);
		}

		return self::$instances[$prefix];
	}

	public function mark($label) {
		$current = self::getmicrotime() - $this->_start;
		if (function_exists('memory_get_usage'))
		{
			$current_mem = memory_get_usage() / 1048576;
			$mark = sprintf(
				'<code>%s %.3f seconds (+%.3f); %0.2f MB (%s%0.3f) - %s</code>',
				$this->_prefix,
				$current,
				$current - $this->_previous_time,
				$current_mem,
				($current_mem > $this->_previous_mem) ? '+' : '', $current_mem - $this->_previous_mem,
				$label
			);
		}
		else
		{
			$mark = sprintf('<code>%s %.3f seconds (+%.3f) - %s</code>', $this->_prefix, $current, $current - $this->_previous_time, $label);
		}

		$this->_previous_time = $current;
		$this->_previous_mem = $current_mem;
		$this->_buffer[] = $mark;

		return $mark;
	}

	public static function getmicrotime() {
		list ($usec, $sec) = explode(' ', microtime());

		return ((float) $usec + (float) $sec);
	}

	public function getMemory() {
		if (function_exists('memory_get_usage'))
		{
			return memory_get_usage();
		}
		else
		{
			// Initialise variables.
			$output = array();
			$pid = getmypid();

			if ($this->_iswin)
			{
				// Windows workaround
				@exec('tasklist /FI "PID eq ' . $pid . '" /FO LIST', $output);
				if (!isset($output[5]))
				{
					$output[5] = null;
				}
				return substr($output[5], strpos($output[5], ':') + 1);
			}
			else
			{
				@exec("ps -o rss -p $pid", $output);
				return $output[1] * 1024;
			}
		}
	}

	public function getBuffer() {
		return $this->_buffer;
	}
}
