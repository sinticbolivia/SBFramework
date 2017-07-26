<?php
$view = SB_Request::getString('view');
?>
<ul class="view-buttons">
	<li <?php print $view == 'section_access' ? 'class="current"' : ''; ?>>
		<a href="<?php print SB_Route::_('index.php?mod=statistics&view=section_access'); ?>">
			<?php print SB_Text::_('Accesos por secciones', 'statistics')?>
		</a> |
	</li>
	<li <?php print $view == 'content_access' ? 'class="current"' : ''; ?>>
		<a href="<?php print SB_Route::_('index.php?mod=statistics&view=content_access'); ?>">
			<?php print SB_Text::_('Accesos por Contenidos', 'statistics')?>
		</a> |
	</li>
	<li <?php print $view == 'history_content_access' ? 'class="current"' : ''; ?>>
		<a href="<?php print SB_Route::_('index.php?mod=statistics&view=history_content_access'); ?>">
			<?php print SB_Text::_('Historial Acceso por Contenidos', 'statistics')?>
		</a> |
	</li>
	<li <?php print $view == 'history_section_access' ? 'class="current"' : ''; ?>>
		<a href="<?php print SB_Route::_('index.php?mod=statistics&view=history_section_access'); ?>">
			<?php print SB_Text::_('Historial Acceso a Secciones', 'statistics')?>
		</a>
	</li>
	
</ul>