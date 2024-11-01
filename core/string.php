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
abstract class WshopString {
	
	protected static $incrementStyles = array(
		'dash' => array(
			'#-(\d+)$#',
			'-%d'
		),
		'default' => array(
			array('#\((\d+)\)$#', '#\(\d+\)$#'),
			array(' (%d)', '(%d)'),
		),
	);

	public static function splitCamelCase($string) {
		return preg_split('/(?<=[^A-Z_])(?=[A-Z])|(?<=[A-Z])(?=[A-Z][^A-Z_])/x', $string);
	}

	public static function increment($string, $style = 'default', $n = 0) {
		$styleSpec = isset(self::$incrementStyles[$style]) ? self::$incrementStyles[$style] : self::$incrementStyles['default'];

		if (is_array($styleSpec[0])) {
			$rxSearch = $styleSpec[0][0];
			$rxReplace = $styleSpec[0][1];
		}
		else {
			$rxSearch = $rxReplace = $styleSpec[0];
		}

		if (is_array($styleSpec[1])) {
			$newFormat = $styleSpec[1][0];
			$oldFormat = $styleSpec[1][1];
		}
		else {
			$newFormat = $oldFormat = $styleSpec[1];
		}

		if (preg_match($rxSearch, $string, $matches)) {
			$n = empty($n) ? ($matches[1] + 1) : $n;
			$string = preg_replace($rxReplace, sprintf($oldFormat, $n), $string);
		}
		else {
			$n = empty($n) ? 2 : $n;
			$string .= sprintf($newFormat, $n);
		}

		return $string;
	}

	public static function strpos($str, $search, $offset = false) {
		if ($offset === false) {
			return utf8_strpos($str, $search);
		}
		else {
			return utf8_strpos($str, $search, $offset);
		}
	}

	public static function strrpos($str, $search, $offset = 0) {
		return utf8_strrpos($str, $search, $offset);
	}

	public static function substr($str, $offset, $length = false) {
		if ($length === false) {
			return utf8_substr($str, $offset);
		}
		else {
			return utf8_substr($str, $offset, $length);
		}
	}

	public static function strtolower($str) {
		return utf8_strtolower($str);
	}

	public static function strtoupper($str) {
		return utf8_strtoupper($str);
	}

	public static function strlen($str) {
		return utf8_strlen($str);
	}

	public static function str_ireplace($search, $replace, $str, $count = null) {
		mimport('phputf8.str_ireplace');
		if ($count === false) {
			return utf8_ireplace($search, $replace, $str);
		}
		else {
			return utf8_ireplace($search, $replace, $str, $count);
		}
	}

	public static function str_split($str, $split_len = 1) {
		mimport('phputf8.str_split');
		return utf8_str_split($str, $split_len);
	}

	public static function strcasecmp($str1, $str2, $locale = false) {
		if ($locale) {
			$locale0 = setlocale(LC_COLLATE, 0);
			if (!$locale = setlocale(LC_COLLATE, $locale)) {
				$locale = $locale0;
			}

			if (!stristr($locale, 'UTF-8') && stristr($locale, '_') && preg_match('~\.(\d+)$~', $locale, $m)) {
				$encoding = 'CP' . $m[1];
			}
			elseif (stristr($locale, 'UTF-8')) {
				$encoding = 'UTF-8';
			}
			else {
				$encoding = 'nonrecodable';
			}

			if ($encoding == 'UTF-8' || $encoding == 'nonrecodable') {
				return strcoll(utf8_strtolower($str1), utf8_strtolower($str2));
			}
			else {
				return strcoll(
					self::transcode(utf8_strtolower($str1), 'UTF-8', $encoding),
					self::transcode(utf8_strtolower($str2), 'UTF-8', $encoding)
				);
			}
		}
		else {
			return utf8_strcasecmp($str1, $str2);
		}
	}

	public static function strcmp($str1, $str2, $locale = false) {
		if ($locale) {
			$locale0 = setlocale(LC_COLLATE, 0);
			if (!$locale = setlocale(LC_COLLATE, $locale)) {
				$locale = $locale0;
			}

			if (!stristr($locale, 'UTF-8') && stristr($locale, '_') && preg_match('~\.(\d+)$~', $locale, $m)) {
				$encoding = 'CP' . $m[1];
			}
			elseif (stristr($locale, 'UTF-8')) {
				$encoding = 'UTF-8';
			}
			else {
				$encoding = 'nonrecodable';
			}

			if ($encoding == 'UTF-8' || $encoding == 'nonrecodable') {
				return strcoll($str1, $str2);
			}
			else {
				return strcoll(self::transcode($str1, 'UTF-8', $encoding), self::transcode($str2, 'UTF-8', $encoding));
			}
		}
		else {
			return strcmp($str1, $str2);
		}
	}

	public static function strcspn($str, $mask, $start = null, $length = null) {
		mimport('phputf8.strcspn');
		if ($start === false && $length === false) {
			return utf8_strcspn($str, $mask);
		}
		elseif ($length === false) {
			return utf8_strcspn($str, $mask, $start);
		}
		else {
			return utf8_strcspn($str, $mask, $start, $length);
		}
	}

	public static function stristr($str, $search) {
		mimport('phputf8.stristr');
		return utf8_stristr($str, $search);
	}

	public static function strrev($str) {
		mimport('phputf8.strrev');
		return utf8_strrev($str);
	}

	public static function strspn($str, $mask, $start = null, $length = null) {
		mimport('phputf8.strspn');
		if ($start === null && $length === null) {
			return utf8_strspn($str, $mask);
		}
		elseif ($length === null) {
			return utf8_strspn($str, $mask, $start);
		}
		else {
			return utf8_strspn($str, $mask, $start, $length);
		}
	}

	public static function substr_replace($str, $repl, $start, $length = null) {
		if ($length === false) {
			return utf8_substr_replace($str, $repl, $start);
		}
		else {
			return utf8_substr_replace($str, $repl, $start, $length);
		}
	}

	public static function ltrim($str, $charlist = false) {
		if (empty($charlist) && $charlist !== false) {
			return $str;
		}

		mimport('phputf8.trim');
		if ($charlist === false) {
			return utf8_ltrim($str);
		}
		else {
			return utf8_ltrim($str, $charlist);
		}
	}

	public static function rtrim($str, $charlist = false) {
		if (empty($charlist) && $charlist !== false) {
			return $str;
		}

		mimport('phputf8.trim');
		if ($charlist === false) {
			return utf8_rtrim($str);
		}
		else {
			return utf8_rtrim($str, $charlist);
		}
	}

	public static function trim($str, $charlist = false) {
		if (empty($charlist) && $charlist !== false) {
			return $str;
		}

		mimport('phputf8.trim');
		if ($charlist === false) {
			return utf8_trim($str);
		}
		else {
			return utf8_trim($str, $charlist);
		}
	}

	public static function ucfirst($str, $delimiter = null, $newDelimiter = null) {
		mimport('phputf8.ucfirst');
		if ($delimiter === null) {
			return utf8_ucfirst($str);
		}
		else {
			if ($newDelimiter === null) {
				$newDelimiter = $delimiter;
			}
			return implode($newDelimiter, array_map('utf8_ucfirst', explode($delimiter, $str)));
		}
	}

	public static function ucwords($str) {
		mimport('phputf8.ucwords');
		return utf8_ucwords($str);
	}

	private static function _iconvErrorHandler($number, $message) {
		throw new ErrorException($message, 0, $number);
	}

	public static function transcode($source, $from_encoding, $to_encoding) {
		if (is_string($source)) {
			set_error_handler(array(__CLASS__, '_iconvErrorHandler'), E_NOTICE);
			try {
				$iconv = iconv($from_encoding, $to_encoding . '//TRANSLIT//IGNORE', $source);
			}
			catch (ErrorException $e) {
				$iconv = iconv($from_encoding, $to_encoding . '//IGNORE', $source);
			}
			restore_error_handler();
			return $iconv;
		}

		return null;
	}

//	public static function valid($str) {
//        return utf8_is_valid($str);
//	}
//
//	public static function compliant($str) {
//		return utf8_compliant($str);
//	}

	public static function parse_url($url) {
		$result = array();
		$entities = array('%21', '%2A', '%27', '%28', '%29', '%3B', '%3A', '%40', '%26', '%3D', '%24', '%2C', '%2F', '%3F', '%25', '%23', '%5B', '%5D');
		$replacements = array('!', '*', "'", "(", ")", ";", ":", "@", "&", "=", "$", ",", "/", "?", "%", "#", "[", "]");
		$encodedURL = str_replace($entities, $replacements, urlencode($url));
		$encodedParts = parse_url($encodedURL);
		
		foreach ($encodedParts as $key => $value) {
			$result[$key] = urldecode($value);
		}
		return $result;
	}
}