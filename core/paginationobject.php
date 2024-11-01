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

class WopshopPaginationObject
{
	/**
	 * @var    string  The link text.
	 * @since  1.0.0
	 */
	public $text;

	/**
	 * @var    integer  The number of rows as a base offset.
	 * @since  1.0.0
	 */
	public $base;

	/**
	 * @var    string  The link URL.
	 * @since  1.0.0
	 */
	public $link;

	/**
	 * @var    integer  The prefix used for request variables.
	 * @since  1.0.0
	 */
	public $prefix;

	/**
	 * @var    boolean  Flag whether the object is the 'active' page
	 * @since  1.0.0
	 */
	public $active;

	/**
	 * Class constructor.
	 *
	 * @param   string   $text    The link text.
	 * @param   string   $prefix  The prefix used for request variables.
	 * @param   integer  $base    The number of rows as a base offset.
	 * @param   string   $link    The link URL.
	 * @param   boolean  $active  Flag whether the object is the 'active' page
	 *
	 * @since   1.0.0
	 */
	public function __construct($text, $prefix = '', $base = null, $link = null, $active = false)
	{
		$this->text   = $text;
		$this->prefix = $prefix;
		$this->base   = $base;
		$this->link   = $link;
		$this->active = $active;
	}
}
