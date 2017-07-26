<?php
SB_Language::loadLanguage(LANGUAGE, 'content', dirname(__FILE__) . SB_DS . 'locale');
SB_Module::RunSQL('content');
$permissions = array(
		array('group' => 'content', 'permission' => 'manage_sections', 'label'	=> 'Gestionar secciones'),
		array('group' => 'content','permission' => 'create_section', 'label'	=> 'Crear seccion'),
		array('group' => 'content','permission' => 'edit_section', 'label'	=> 'Editar seccion'),
		array('group' => 'content','permission' => 'delete_section', 'label'	=> 'Borrar seccion'),
		array('group' => 'content','permission' => 'manage_content', 'label'	=> 'Gestionar contenido'),
		array('group' => 'content','permission' => 'create_content', 'label'	=> 'Crear contenido'),
		array('group' => 'content','permission' => 'edit_content', 'label'	=> 'Editar contenido'),
		array('group' => 'content','permission' => 'delete_content', 'label'	=> 'Borrar contenido'),
		array('group' => 'content','permission' => 'manage_categories', 'label'	=> 'Gestionar categorias'),
		array('group' => 'content','permission' => 'create_category', 'label'	=> 'Crear categoria'),
		array('group' => 'content','permission' => 'edit_category', 'label'	=> 'Editar categoria'),
		array('group' => 'content','permission' => 'delete_category', 'label'	=> 'Borrar categoria'),
		//##blog permissions
		array('group' => 'content','permission' => 'manage_posts', 'label'	=> __('Gestion del Blog', 'content')),
		array('group' => 'content','permission' => 'manage_post_categories', 'label' => __('Gestion Categorias (Blog)', 'content')),
		array('group' => 'content','permission' => 'create_post_category', 'label' => __('Create Category (Blog)', 'content')),
		array('group' => 'content','permission' => 'edit_post_category', 'label' => __('Edit Category (Blog)', 'content')),
		array('group' => 'content','permission' => 'delete_post_category', 'label' => __('Delete Category (Blog)', 'content')),
);
sb_add_permissions($permissions);
//##run updates
require_once dirname(__FILE__) . SB_DS . 'updates' . SB_DS . 'update-1.0.1.php';