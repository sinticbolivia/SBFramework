<?php
$user = sb_get_current_user();
?>
<div id="user-session" class="text-center">
	<img src="<?php print sb_get_user_image_url(sb_get_current_user()->user_id);//print BASEURL; ?>" alt="" class="thumbnail" />
	<div id="hello-user"><b><?php printf(SB_Text::_('Hello %s', 'lb'), sb_get_current_user()->username); ?></b></div>
	<a href="<?php print SB_Route::_('profile.php'); ?>"><?php _e('My profile', 'lb'); ?></a><br/>
	<a href="<?php print SB_Route::_('index.php?mod=users&task=logout'); ?>"><?php _e('Close session', 'lb'); ?></a>
</div>
	<div class="clear"></div>
<nav>
	<?php SB_Menu::rederMenu('backend'); ?>
	<?php /* ?>
	<ul>
		<li><a href="index.php">Inicio</a></li>
		<li><a href="index.php?mod=content&view=sections">Secciones</a></li>
		<li><a href="index.php?mod=content">Contenido</a></li>
		<li>
			<a href="javascript:;">Administracion</a>
			<ul>
				<li><a href="index.php?mod=users">Usuarios</a></li>
				<li><a href="index.php?mod=users&view=roles">Roles de Usuario</a></li>
			</ul>
		</li>
		<li>
			<a href="index.php?mod=settings">Configuracion</a>
			<ul>
				<li><a href="index.php?mod=settings">General</a></li>
				<li><a href="index.php?mod=settings&view=templates">Templates</a></li>
				<li><a href="index.php?mod=modules">Modulos</a></li>
			</ul>
		</li>
	</ul>
	*/?>
	<!-- 
	<a href="javascript:;"><?php print sb_format_datetime(date('Y-m-d H:i:s')); ?></a>
	 -->
</nav>