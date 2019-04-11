<?php
use SinticBolivia\SBFramework\Classes\SB_Menu;

?>
<nav>
	<div class="container">
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
		<ul class="right">
			<li>
				<a href="javascript:;"><?php printf(__('Hola %s'), sb_get_current_user()->username); ?></a>
				<ul>
					<li><a href="<?php print b_route('profile.php'); ?>"><?php _e('Mi Perfil'); ?></a></li>
					<li><a href="<?php print b_route('index.php?mod=users&task=logout'); ?>">Cerrar Sesion</a></li>
				</ul>
			</li>
			<li ></li>
		</ul>
		<div class="clear"></div>
		<!-- 
		<a href="javascript:;"><?php print sb_format_datetime(date('Y-m-d H:i:s')); ?></a>
		 -->
	</div>
</nav>