<?php
/**
 * Class to add customers important dates
 * 
 * @author marcelo
 *
 */
class LT_ThemeLittleBoysHooksCustomers
{
	public function __construct()
	{
		$this->AddActions();
	}
	protected function AddActions()
	{
		if( lt_is_admin() )
		{
			
		}
	}
	
}
new LT_ThemeLittleBoysHooksCustomers();