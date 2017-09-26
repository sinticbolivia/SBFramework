<?php
function sb_build_admin_menu()
{
	global $content_types;
	
	SB_Menu::addMenuPage('<span class="glyphicon glyphicon-home"></span> ' . __('Home', 'lt'), SB_Route::_('index.php'), 'dashboard', 'manage_backend', 0);
	if( is_array($content_types) )
	{
		foreach($content_types as $key => $type)
		{
			if( $key == 'page' || $key == 'post' ) continue;
			SB_Menu::addMenuPage($type['labels']['menu_label'], SB_Route::_('index.php?mod=content&type='.$key), 
								'menu-content-type-'.$key, 'manage_backend');
			if( isset($type['section']) )
			{
				SB_Menu::addMenuChild('menu-content-type-'.$key, 
										$type['labels']['menu_label'], 
										SB_Route::_('index.php?mod=content&type='.$key),
										'-menu-content-type-'.$key,
										'manage_backend');
				SB_Menu::addMenuChild('menu-content-type-'.$key, $type['section']['labels']['menu_label'], 
										SB_Route::_('index.php?mod=content&view=section.default&fo='.$type['section']['for_object']),
										'menu-section-type-'.$type['section']['for_object'],
										'manage_backend');
			}
		}		
	}
	SB_Menu::addMenuPage('<span class="glyphicon glyphicon-th"></span> ' . __('Management', 'lt'), 
							'javascript:;', 
							'menu-management', 
							'manage_backend', 5);
	SB_Menu::addMenuPage('<span class="glyphicon glyphicon-wrench"></span> ' . __('Settings', 'lt'), 
							SB_Route::_('settings.php'), 'menu-settings', 'manage_settings', 10);
	
	SB_Menu::addMenuChild('menu-settings', 
			'<span class="glyphicon glyphicon-cog"></span>'.__('Settings', 'lt'), SB_Route::_('settings.php'), 'menu-general-settings', 'manage_general_settings');
	//SB_Menu::addMenuChild('menu-settings', SB_Text::_('Plantillas'), SB_Route::_('index.php?mod=templates'), 'menu-templates', 'manage_templates');
	SB_Menu::addMenuChild('menu-settings', 
			'<span class="glyphicon glyphicon-th"></span>'.__('Modules', 'lt'), SB_Route::_('index.php?mod=modules'), 'menu-modules', 'manage_modules');
	SB_Menu::addMenuChild('menu-settings',
			'<span class="glyphicon glyphicon-th"></span>'.__('Templates', 'lt'), 
			SB_Route::_('templates.php'), 'menu-templates', 'manage_templates');
	SB_Module::do_action('admin_menu');
	//SB_Menu::buildMainMenu();
}