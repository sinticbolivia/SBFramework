<?php
$dbh 		= SB_Factory::getDbh();
$permissions = array(
		array('group' => 'content', 'permission' => 'manage_menu_content', 'label'	=> __('Manage Content Menus', 'menu')),
);
$local_permissions = sb_get_permissions(false);
foreach($permissions as $perm)
{
	if( in_array($perm['permission'], $local_permissions) ) continue;
	$dbh->Insert('permissions', $perm);
}