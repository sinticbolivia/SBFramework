<?php
namespace SinticBolivia\SBFramework\Classes;
use SinticBolivia\SBFramework\Classes\SB_Object;
use SinticBolivia\SBFramework\Classes\SB_Factory;
use SinticBolivia\SBFramework\Classes\SB_Globals;

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