<?php
$user_links = array(
		array('link'	=> SB_Route::_('index.php?mod=users'), 'text'	=> SBText::_('Inicio')),
		
);
$user_links = SB_Module::do_action('user_menu', $user_links);
$user_links[] = array('link'	=> SB_Route::_('index.php?tpl=tpl-contact'), 'text'	=> SBText::_('Contacto'));
?>
<div class="text-right">
	<div class="btn-group">
		<?php foreach($user_links as $menu_item): ?>
		<a href="<?php print $menu_item['link']; ?>" class="btn btn-default"><?php print $menu_item['text']; ?></a>
		<?php endforeach; ?>
		<a href="<?php print SB_Route::_('index.php?mod=users&task=logout'); ?>" class="btn btn-default"><?php print SB_Text::_('Salir'); ?></a>
	</div>
</div>