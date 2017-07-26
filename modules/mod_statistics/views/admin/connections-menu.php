<?php
$view = SB_Request::getString('view');
?>
<ul class="view-buttons">
	<li <?php print $view == 'user_access' ? 'class="current"' : ''; ?>>
		<a href="<?php print SB_Route::_('index.php?mod=statistics&view=user_access'); ?>" class="has-popover" 
			data-message="<?php print SBText::_('STATS_CON_MENU_USER_ACCESS'); ?>">
			<?php print SB_Text::_('Accesos por usuario')?></a> |</li>
	<li <?php print $view == 'connections_history' ? 'class="current"' : ''; ?>>
		<a href="<?php print SB_Route::_('index.php?mod=statistics&view=connections_history'); ?>" class="has-popover"
			data-message="<?php print SBText::_('STATS_CON_MENU_HISTORY'); ?>">
			<?php print SB_Text::_('Historial Conexiones', 'statistics')?>
		</a> |
	</li>
	<li <?php print $view == 'login_errors' ? 'class="current"' : ''; ?>>
		<a href="<?php print SB_Route::_('index.php?mod=statistics&view=login_errors'); ?>" class="has-popover"
			data-message="<?php print SBText::_('STATS_CON_MENU_LOGIN_ERRORS'); ?>">
			<?php print SB_Text::_('Errores de login')?>
		</a>
	</li>
</ul>