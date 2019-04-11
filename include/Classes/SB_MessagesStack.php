<?php
namespace SinticBolivia\SBFramework\Classes;

class SB_MessagesStack
{
	protected static $messages	= array();
	
	/**
	 * Add a message to stack
	 * 
	 * @param <string> $msg 
	 * @param <string> $type info|success|warning|error|danger
	 * @return  
	 */
	public static function AddMessage($msg, $type = 'info')
	{
		self::Start();
		SB_MessagesStack::$messages =& $_SESSION['messages'];
		SB_MessagesStack::$messages[] = array('msg' => $msg, 'type' => $type);
	}
	public static function AddError($error)
	{
		self::AddMessage($error, 'error');
	}
	public static function AddSuccess($message)
	{
		self::AddMessage($message, 'success');
	}
	public static function AddWarning($warning){self::AddMessage($warning, 'warning');}
	public static function ShowMessages()
	{
		self::Start();
		SB_MessagesStack::$messages =& $_SESSION['messages'];
		foreach(SB_MessagesStack::$messages as $index => $item)
		{
			printf("<div class=\"alert alert-%s\">%s</div>", $item['type'] == 'error' ? 'danger' : $item['type'], $item['msg']);
			unset(SB_MessagesStack::$messages[$index]);
		}
	}
	protected static function Start()
	{
		//print_r($_SESSION['messages']);
		if( !isset($_SESSION['messages']) || !is_array($_SESSION['messages']) )
		{
			$_SESSION['messages'] = array();
		}
	}
}