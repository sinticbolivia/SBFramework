<?php
$view = SB_Request::getString('view');
?>
<ul class="view-buttons">
	<li <?php print $view == 'user_access' ? 'class="current"' : ''; ?>>
		<a href="<?php print SB_Route::_('index.php?mod=statistics&view=user_access'); ?>">
			<?php print SB_Text::_('Accesos por usuario')?></a> |
	</li>
	<li <?php print $view == 'section_access' ? 'class="current"' : ''; ?>>
		<a href="<?php print SB_Route::_('index.php?mod=statistics&view=section_access'); ?>">
			<?php print SB_Text::_('Accesos por secciones')?></a> |
	</li>
	<li <?php print $view == 'login_errors' ? 'class="current"' : ''; ?>>
		<a href="<?php print SB_Route::_('index.php?mod=statistics&view=login_errors'); ?>"><?php print SB_Text::_('Errores de login')?></a></li>
</ul>