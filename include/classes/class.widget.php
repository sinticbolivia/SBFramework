<?php
abstract class SB_Widget extends SB_Object
{
	protected $id;
	public $title;
	/**
	 * @var SB_Database
	 */
	public $dbh;
	
	public function __construct($title)
	{
		$this->dbh 		= SB_Factory::getDbh();
		$this->title 	= $title;
		$widgets 		=& SB_Globals::GetVar('widgets');
		$widgets[get_class($this)]['instances']++;
		$this->id		= strtolower(get_class($this)) . '-' . $widgets[get_class($this)]['instances'];
	}
	public function Settings(){}
	abstract public function Render($args = array());
}
/**
 * @brief Register a widget
 * @param string $class 
 * @return  bool
 */
function sb_register_widget($class)
{
	if( !class_exists($class) )
		return false;
	if( !SB_Globals::GetVar('widgets') )
	{
		SB_Globals::SetVar('widgets', array());
	}
	$widgets =& SB_Globals::GetVar('widgets');
	$widgets[$class] = array('instances' => 0);
	
	return true;
}
/**
 * @brief Shows a widget
 * 
 * @param string $class 
 * @param array $args 
 * @return  bool
 */
function sb_show_widget($class, $args = array())
{
	$widgets = SB_Globals::GetVar('widgets');
	if( !isset($widgets[$class]) )
		return false;
	
	$instance = new $class();
	$instance->Render($args);
	
	return true;
}
/**
 * Add a widget instance to a widget area
 * 
 * @param string 	$area		The widget area name
 * @param SB_Widget $widget  	The widget instance
 * @param array 	$args 		Arguments for widget instance
 * @return bool
 */
function sb_widget_add2area($area, SB_Widget $widget, $args = array())
{
	$areas =& SB_Globals::GetVar('widget_areas');
	if( !$areas )
	{
		SB_Globals::SetVar('widget_areas', array());
		$areas =& SB_Globals::GetVar('widget_areas');
	}
	if( !isset($areas[$area]) )
	{
		$areas[$area] = array();
	}
	$areas[$area][] = array('instance' => $widget, 'args' => $args);
	
	return true;
}
function sb_widget_area($area)
{
	$areas = SB_Globals::GetVar('widget_areas');
	if( !$areas )
		return false;
	if( !isset($areas[$area]) )
		return false;
	$the_area = $areas[$area];
	foreach($the_area as $widget )
	{
		$widget['instance']->Render((array)$widget['args']);
	}
	return true;
}