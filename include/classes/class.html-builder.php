<?php
class SB_HtmlBuilder
{
	/**
	 * Write a html input tag
	 * 
	 * @param string $type  text|password|hidden
	 * @param string $name The input tag name
	 * @param string $value The input tag value
	 * @param string $id The input tag id
	 * @param array $attrs The input tag attributes
	 * @return string
	 */
	public static function writeInput($type, $name, $value = '', $id = null, array $attrs = array())
	{
		$the_attrs = '';
		foreach($attrs as $i => $attr)
		{
			$the_attrs .= "$i=\"$attr\" ";
		}
		return sprintf("<input type=\"%s\" id=\"%s\" name=\"%s\" value=\"%s\" %s />", $type, $id ? $id : $name, $name, $value, $the_attrs);
	}
	/**
	 * Write a html textarea tag
	 * 
	 * @param string $name The textarea tag name
	 * @param string $value The textarea tag value
	 * @param string $id The textarea tag id, if it's not setted up, the name will be used
	 * @param array $attrs The textarea tag attributes
	 * @return string
	 */
	public static function writeTextArea($name, $value = '', $id = null, array $attrs = array())
	{
		$the_attrs = '';
		foreach($attrs as $i => $attr)
		{
			$the_attrs .= "$i=\"$attr\" ";
		}
		return sprintf("<textarea id=\"%s\" name=\"%s\" %s>%s</textarea>\n", ($id) ? $id : $name, $name, $the_attrs, $value);
	}
	public static function writeDropdown($name, $dataset = array(), $dataset_fields = array(), $id = null, $attrs = array())
	{
		$dropdown = '<select id="'.(($id) ? $id : $name).'" name="'.$name.'">%s</select>';
		$ops = '';
		foreach($dataset as $op)
		{
			$_op = (array)$op;
			if( count($dataset_fields) > 0 )
			{
				$ops .= sprintf("<option value=\"%s\">%s</option>", $_op[$dataset_fields['field_value']], $_op[$dataset_fields['field_text']]);
			}
			else
			{
				$ops .= sprintf("<option value=\"%s\">%s</option>", $_op['field_value'], $_op['field_text']);
			}
		}
		return sprintf($dropdown, $ops);
	}
	public static function writeIconUrl($icon, $size = '48x48', $dir = null)
	{
		return sprintf("%s/images/icons/%s/%s", FRAMEWORK_URL, $size, $icon);
	}
	public static function writeToolButton($link, $text, $icon, $size = '48x48', $dir = null, $title = null, $atts = null)
	{
		$icon_link = self::writeIconUrl($icon, $size, $dir);
		return sprintf("<a class=\"btn tool-btn\" href=\"%s\" title=\"%s\" $atts><img src=\"%s\" alt=\"\" /><span>%s</span></a>", 
						$link, ($title) ? $title : $text, $icon_link, $text);
	}
}