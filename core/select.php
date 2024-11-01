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
abstract class WopshopHtmlSelect
{
	/**
	 * Default values for options. Organized by option group.
	 *
	 * @var     array
	 * @since   1.0.0
	 */
	static protected $optionDefaults = array(
		'option' => array('option.attr' => null, 'option.disable' => 'disable', 'option.id' => null, 'option.key' => 'value',
			'option.key.toHtml' => true, 'option.label' => null, 'option.label.toHtml' => true, 'option.text' => 'text',
			'option.text.toHtml' => true, 'option.class' => 'class', 'option.onclick' => 'onclick'));

	/**
	 * Generates a yes/no radio list.
	 *
	 * @param   string  $name      The value of the HTML name attribute
	 * @param   array   $attribs   Additional HTML attributes for the <select> tag
	 * @param   string  $selected  The key that is selected
	 * @param   string  $yes       Language key for Yes
	 * @param   string  $no        Language key for no
	 * @param   mixed   $id        The id for the field or false for no id
	 *
	 * @return  string  HTML for the radio list
	 *
	 * @since   1.0.0
	 * @see     JFormFieldRadio
	 */
	public static function booleanlist($name, $attribs = array(), $selected = null, $yes = 'Yes', $no = 'No', $id = false)
	{
		$arr = array(WopshopHtml::_('select.option', '0', $no), WopshopHtml::_('select.option', '1', $yes));

		return WopshopHtml::_('select.radiolist', $arr, $name, $attribs, 'value', 'text', (int) $selected, $id);
	}

	/**
	 * Generates an HTML selection list.
	 *
	 * @param   array    $data       An array of objects, arrays, or scalars.
	 * @param   string   $name       The value of the HTML name attribute.
	 * @param   mixed    $attribs    Additional HTML attributes for the <select> tag. This
	 *                               can be an array of attributes, or an array of options. Treated as options
	 *                               if it is the last argument passed. Valid options are:
	 *                               Format options, see {@see WopshopHtml::$formatOptions}.
	 *                               Selection options, see {@see HtmlSelect::options()}.
	 *                               list.attr, string|array: Additional attributes for the select
	 *                               element.
	 *                               id, string: Value to use as the select element id attribute.
	 *                               Defaults to the same as the name.
	 *                               list.select, string|array: Identifies one or more option elements
	 *                               to be selected, based on the option key values.
	 * @param   string   $optKey     The name of the object variable for the option value. If
	 *                               set to null, the index of the value array is used.
	 * @param   string   $optText    The name of the object variable for the option text.
	 * @param   mixed    $selected   The key that is selected (accepts an array or a string).
	 * @param   mixed    $idtag      Value of the field id or null by default
	 *
	 * @return  string  HTML for the select list.
	 *
	 * @since   1.0.0
	 */
	public static function genericlist($data, $name, $attribs = null, $optKey = 'value', $optText = 'text', $selected = null, $idtag = false)
	{
		// Set default options
		$options = array_merge(WopshopHtml::$formatOptions, array('format.depth' => 0, 'id' => false));

		if (is_array($attribs) && func_num_args() == 3)
		{
			// Assume we have an options array
			$options = array_merge($options, $attribs);
		}
		else
		{
			// Get options from the parameters
			$options['id'] = $idtag;
			$options['list.attr'] = $attribs;
			$options['option.key'] = $optKey;
			$options['option.text'] = $optText;
			$options['list.select'] = $selected;
		}

		$attribs = '';

		if (isset($options['list.attr']))
		{
			$attribs = $options['list.attr'];

			if ($attribs != '')
			{
				$attribs = ' ' . $attribs;
			}
		}

		$id = $options['id'] !== false ? $options['id'] : $name;
		$id = str_replace(array('[', ']'), '', $id);

		$html =  '<select' . ($id !== '' ? ' id="' . esc_attr($id) . '"' : '') . ' name="' . esc_attr($name) . '"' . $attribs . '>' . $options['format.eol']
			. static::options($data, $options) .  '</select>' . $options['format.eol'];

		return $html;
	}

	/**
	 * Generates a selection list of integers.
	 *
	 * @param   integer  $start     The start integer
	 * @param   integer  $end       The end integer
	 * @param   integer  $inc       The increment
	 * @param   string   $name      The value of the HTML name attribute
	 * @param   mixed    $attribs   Additional HTML attributes for the <select> tag, an array of
	 *                              attributes, or an array of options. Treated as options if it is the last
	 *                              argument passed.
	 * @param   mixed    $selected  The key that is selected
	 * @param   string   $format    The printf format to be applied to the number
	 *
	 * @return  string   HTML for the select list
	 *
	 * @since   1.0.0
	 */
	public static function integerlist($start, $end, $inc, $name, $attribs = null, $selected = null, $format = '')
	{
		// Set default options
		$options = array_merge(WopshopHtml::$formatOptions, array('format.depth' => 0, 'option.format' => '', 'id' => null));

		if (is_array($attribs) && func_num_args() == 5)
		{
			// Assume we have an options array
			$options = array_merge($options, $attribs);

			// Extract the format and remove it from downstream options
			$format = $options['option.format'];
			unset($options['option.format']);
		}
		else
		{
			// Get options from the parameters
			$options['list.attr'] = $attribs;
			$options['list.select'] = $selected;
		}

		$start = (int) $start;
		$end   = (int) $end;
		$inc   = (int) $inc;

		$data = array();

		for ($i = $start; $i <= $end; $i += $inc)
		{
			$data[$i] = $format ? sprintf($format, $i) : $i;
		}

		// Tell genericlist() to use array keys
		$options['option.key'] = null;

		return WopshopHtml::_('select.genericlist', $data, $name, $options);
	}

	/**
	 * Create an object that represents an option in an option list.
	 *
	 * @param   string   $value    The value of the option
	 * @param   string   $text     The text for the option
	 * @param   mixed    $optKey   If a string, the returned object property name for
	 *                             the value. If an array, options. Valid options are:
	 *                             attr: String|array. Additional attributes for this option.
	 *                             Defaults to none.
	 *                             disable: Boolean. If set, this option is disabled.
	 *                             label: String. The value for the option label.
	 *                             option.attr: The property in each option array to use for
	 *                             additional selection attributes. Defaults to none.
	 *                             option.disable: The property that will hold the disabled state.
	 *                             Defaults to "disable".
	 *                             option.key: The property that will hold the selection value.
	 *                             Defaults to "value".
	 *                             option.label: The property in each option array to use as the
	 *                             selection label attribute. If a "label" option is provided, defaults to
	 *                             "label", if no label is given, defaults to null (none).
	 *                             option.text: The property that will hold the the displayed text.
	 *                             Defaults to "text". If set to null, the option array is assumed to be a
	 *                             list of displayable scalars.
	 * @param   string   $optText  The property that will hold the the displayed text. This
	 *                             parameter is ignored if an options array is passed.
	 * @param   boolean  $disable  Not used.
	 *
	 * @return  object
	 *
	 * @since   1.0.0
	 */
	public static function option($value, $text = '', $optKey = 'value', $optText = 'text', $disable = false)
	{
		$options = array('attr' => null, 'disable' => false, 'option.attr' => null, 'option.disable' => 'disable', 'option.key' => 'value',
			'option.label' => null, 'option.text' => 'text');

		if (is_array($optKey))
		{
			// Merge in caller's options
			$options = array_merge($options, $optKey);
		}
		else
		{
			// Get options from the parameters
			$options['option.key'] = $optKey;
			$options['option.text'] = $optText;
			$options['disable'] = $disable;
		}

		$obj = new stdClass;
		$obj->{$options['option.key']}  = $value;
		$obj->{$options['option.text']} = trim($text) ? $text : $value;

		/*
		 * If a label is provided, save it. If no label is provided and there is
		 * a label name, initialise to an empty string.
		 */
		$hasProperty = $options['option.label'] !== null;

		if (isset($options['label']))
		{
			$labelProperty = $hasProperty ? $options['option.label'] : 'label';
			$obj->$labelProperty = $options['label'];
		}
		elseif ($hasProperty)
		{
			$obj->{$options['option.label']} = '';
		}

		// Set attributes only if there is a property and a value
		if ($options['attr'] !== null)
		{
			$obj->{$options['option.attr']} = $options['attr'];
		}

		// Set disable only if it has a property and a value
		if ($options['disable'] !== null)
		{
			$obj->{$options['option.disable']} = $options['disable'];
		}

		return $obj;
	}

	/**
	 * Generates the option tags for an HTML select list (with no select tag
	 * surrounding the options).
	 *
	 * @param   array    $arr        An array of objects, arrays, or values.
	 * @param   mixed    $optKey     If a string, this is the name of the object variable for
	 *                               the option value. If null, the index of the array of objects is used. If
	 *                               an array, this is a set of options, as key/value pairs. Valid options are:
	 *                               -Format options, {@see WopshopHtml::$formatOptions}.
	 *                               -groups: Boolean. If set, looks for keys with the value
	 *                                "&lt;optgroup>" and synthesizes groups from them. Deprecated. Defaults
	 *                                true for backwards compatibility.
	 *                               -list.select: either the value of one selected option or an array
	 *                                of selected options. Default: none.
	 *                               -option.id: The property in each option array to use as the
	 *                                selection id attribute. Defaults to none.
	 *                               -option.key: The property in each option array to use as the
	 *                                selection value. Defaults to "value". If set to null, the index of the
	 *                                option array is used.
	 *                               -option.label: The property in each option array to use as the
	 *                                selection label attribute. Defaults to null (none).
	 *                               -option.text: The property in each option array to use as the
	 *                               displayed text. Defaults to "text". If set to null, the option array is
	 *                               assumed to be a list of displayable scalars.
	 *                               -option.attr: The property in each option array to use for
	 *                                additional selection attributes. Defaults to none.
	 *                               -option.disable: The property that will hold the disabled state.
	 *                                Defaults to "disable".
	 *                               -option.key: The property that will hold the selection value.
	 *                                Defaults to "value".
	 *                               -option.text: The property that will hold the the displayed text.
	 *                               Defaults to "text". If set to null, the option array is assumed to be a
	 *                               list of displayable scalars.
	 * @param   string   $optText    The name of the object variable for the option text.
	 * @param   mixed    $selected   The key that is selected (accepts an array or a string)
	 *
	 * @return  string  HTML for the select list
	 *
	 * @since   1.0.0
	 */
	public static function options($arr, $optKey = 'value', $optText = 'text', $selected = null)
	{
		$options = array_merge(
			WopshopHtml::$formatOptions,
			static::$optionDefaults['option'],
			array('format.depth' => 0, 'groups' => true, 'list.select' => null, 'list.translate' => false)
		);

		if (is_array($optKey))
		{
			// Set default options and overwrite with anything passed in
			$options = array_merge($options, $optKey);
		}
		else
		{
			// Get options from the parameters
			$options['option.key'] = $optKey;
			$options['option.text'] = $optText;
			$options['list.select'] = $selected;
		}

		$html = '';
		$baseIndent = str_repeat($options['format.indent'], $options['format.depth']);

		foreach ($arr as $elementKey => &$element)
		{
			$attr = '';
			$extra = '';
			$label = '';
			$id = '';

			if (is_array($element))
			{
				$key = $options['option.key'] === null ? $elementKey : $element[$options['option.key']];
				$text = $element[$options['option.text']];

//				if (isset($element[$options['option.attr']]))
//				{
//					$attr = $element[$options['option.attr']];
//				}
//
//				if (isset($element[$options['option.id']]))
//				{
//					$id = $element[$options['option.id']];
//				}
//
//				if (isset($element[$options['option.label']]))
//				{
//					$label = $element[$options['option.label']];
//				}

				if (isset($element[$options['option.disable']]) && $element[$options['option.disable']])
				{
					$extra .= ' disabled="disabled"';
				}
			}
			elseif (is_object($element))
			{
				$key = $options['option.key'] === null ? $elementKey : $element->{$options['option.key']};
				$text = $element->{$options['option.text']};

//				if (isset($element->{$options['option.attr']}))
//				{
//					$attr = $element->{$options['option.attr']};
//				}
//
//				if (isset($element->{$options['option.id']}))
//				{
//					$id = $element->{$options['option.id']};
//				}
//
//				if (isset($element->{$options['option.label']}))
//				{
//					$label = $element->{$options['option.label']};
//				}

				if (isset($element->{$options['option.disable']}) && $element->{$options['option.disable']})
				{
					$extra .= ' disabled="disabled"';
				}

//				if (isset($element->{$options['option.class']}) && $element->{$options['option.class']})
//				{
//					$extra .= ' class="' . $element->{$options['option.class']} . '"';
//				}
//
//				if (isset($element->{$options['option.onclick']}) && $element->{$options['option.onclick']})
//				{
//					$extra .= ' onclick="' . $element->{$options['option.onclick']} . '"';
//				}
			}
			else
			{
				// This is a simple associative array
				$key = $elementKey;
				$text = $element;
			}

			/*
			 * The use of options that contain optgroup HTML elements was
			 * somewhat hacked for J1.5. J1.6 introduces the grouplist() method
			 * to handle this better. The old solution is retained through the
			 * "groups" option, which defaults true in J1.6, but should be
			 * deprecated at some point in the future.
			 */

			$key = (string) $key;

			if ($options['groups'] && $key == '<OPTGROUP>')
			{
				$html .= $baseIndent . '<optgroup label="' . $text . '">' . $options['format.eol'];
				$baseIndent = str_repeat($options['format.indent'], ++$options['format.depth']);
			}
			elseif ($options['groups'] && $key == '</OPTGROUP>')
			{
				$baseIndent = str_repeat($options['format.indent'], --$options['format.depth']);
				$html .= $baseIndent . '</optgroup>' . $options['format.eol'];
			}
			else
			{
				// If no string after hyphen - take hyphen out
				$splitText = explode(' - ', $text, 2);
				$text = $splitText[0];

				if (isset($splitText[1]) && $splitText[1] != "" && !preg_match('/^[\s]+$/', $splitText[1]))
				{
					$text .= ' - ' . $splitText[1];
				}


				if ($options['option.label.toHtml'])
				{
					$label = htmlentities($label);
				}

				$attr = trim($attr);

				$extra = ($id ? ' id="' . $id . '"' : '') . ($label ? ' label="' . $label . '"' : '') . ($attr ? ' ' . $attr : '') . $extra;

				if (is_array($options['list.select']))
				{
					foreach ($options['list.select'] as $val)
					{
						$key2 = is_object($val) ? $val->{$options['option.key']} : $val;

						if ($key == $key2)
						{
							$extra .= ' selected="selected"';
							break;
						}
					}
				}
				elseif ((string) $key == (string) $options['list.select'])
				{
					$extra .= ' selected="selected"';
				}

				// Generate the option, encoding as required
				$html .= '<option value="' . esc_attr($key) . '"'
					. $extra . '>';
				$html .= esc_html($text);
				$html .= '</option>' . $options['format.eol'];
			}
		}

		return $html;
	}

	/**
	 * Generates an HTML radio list.
	 *
	 * @param   array    $data       An array of objects
	 * @param   string   $name       The value of the HTML name attribute
	 * @param   string   $attribs    Additional HTML attributes for the <select> tag
	 * @param   mixed    $optKey     The key that is selected
	 * @param   string   $optText    The name of the object variable for the option value
	 * @param   string   $selected   The name of the object variable for the option text
	 * @param   boolean  $idtag      Value of the field id or null by default
	 *
	 * @return  string  HTML for the select list
	 *
	 * @since   1.0.0
	 */
	public static function radiolist($data, $name, $attribs = null, $optKey = 'value', $optText = 'text', $selected = null, $idtag = false, $translate = false, $inline = true) {

		$id_text = $idtag ? $idtag : $name;

		$html = '<div class="controls">';

		foreach ($data as $obj) {
			$k = $obj->$optKey;
			$t = $obj->$optText;
			$id = (isset($obj->id) ? $obj->id : null);

			$extra = '';
			$id = $id ? $obj->id : $id_text . $k;

			if (is_array($selected)) {
				foreach ($selected as $val) {
					$k2 = is_object($val) ? $val->$optKey : $val;

					if ($k == $k2) {
						$extra .= ' selected="selected" ';
						break;
					}
				}
			} else {
				$extra .= ((string) $k == (string) $selected ? ' checked="checked" ' : '');
			}
            
            if ($inline){
                $labelClass = "radio-inline";
            } else {
                $labelClass = "radio";
            }
            
			$html .= "\n\t" . '<label for="' . $id . '" id="' . $id . '-lbl" class="'.$labelClass.'">';
			$html .= "\n\t\n\t" . '<input type="radio" name="' . $name . '" id="' . $id . '" value="' . $k . '" ' . $extra
				. $attribs . ' >' . $t;
			$html .= "\n\t" . '</label>';
		}

		$html .= "\n";
		$html .= '</div>';
		$html .= "\n";

		return $html;
	}
}
