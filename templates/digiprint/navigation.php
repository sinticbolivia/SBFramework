<?php
$user = sb_get_current_user();
?>
<div id="user-session" class="text-center">
	<img src="<?php print sb_get_user_image_url(sb_get_current_user()->user_id);//print BASEURL; ?>" alt="" class="thumbnail" />
	<div id="user-session-info">
		<div id="hello-user"><b><?php printf(SB_Text::_('Hello %s', 'lb'), sb_get_current_user()->username); ?></b></div>
		<a href="<?php print SB_Route::_('index.php?mod=users'); ?>"><?php print SB_Text::_('My profile', 'lb'); ?></a><br/>
		<a href="<?php print SB_Route::_('index.php?mod=users&task=logout'); ?>"><?php _e('Close session', 'lb'); ?></a>
	</div>
</div>
<div class="clear"></div>
<nav>
	<?php //SB_Menu::rederMenu('backend'); ?>
	<ul class="sb-menu">
		<li>
			<a href="<?php print SB_Route::_('index.php'); ?>">
				<span class="glyphicon glyphicon-th-large"></span> 
				<span class="text"><?php _e('Home', 'digiprint'); ?></span>
			</a>
		</li>
		<li>
			<a href="<?php print SB_Route::_('index.php?mod=mb'); ?>">
				<span class="glyphicon glyphicon-shopping-cart"></span> 
				<span class="text"><?php _e('Tienda', 'digiprint'); ?></span>
			</a>
		</li>
		<li>
			<a href="<?php print SB_Route::_('index.php?mod=quotes'); ?>">
				<span class="glyphicon glyphicon-shopping-cart"></span> 
				<span class="text"><?php _e('Cotizacion', 'digiprint'); ?></span>
			</a>
		</li>
		<li>
			<a href="<?php print SB_Route::_('index.php?mod=mb&view=orders.default'); ?>">
				<span class="glyphicon glyphicon-shopping-cart"></span> 
				<span class="text"><?php _e('Orders', 'digiprint'); ?></span>
			</a>
		</li>
		<li>
			<a href="<?php print SB_Route::_('index.php'); ?>">
				<span class="glyphicon glyphicon-pencil"></span> 
				<span class="text"><?php _e('Payments', 'digiprint'); ?></span>
			</a>
		</li>
		<li>
			<a href="<?php print SB_Route::_('index.php'); ?>">
				<span class="glyphicon glyphicon-briefcase"></span> 
				<span class="text"><?php _e('Realizar Pagos', 'digiprint'); ?></span>
			</a>
		</li>
		<li>
			<a href="<?php print SB_Route::_('index.php?mod=storage'); ?>">
				<span class="glyphicon glyphicon-barcode"></span> 
				<span class="text"><?php _e('My Storage', 'digiprint'); ?></span>
			</a>
		</li>
		<li>
			<a href="<?php print SB_Route::_('index.php'); ?>">
				<span class="glyphicon glyphicon-stats"></span> 
				<span class="text"><?php _e('Promociones', 'digiprint'); ?></span></a>
		</li>
		<li>
			<a href="<?php print SB_Route::_('index.php?mod=support'); ?>">
				<span class="glyphicon glyphicon-hdd"></span> 
				<span class="text"><?php _e('Soporte', 'digiprint'); ?></span></a>
		</li>
	</ul>
	<div class="text-center">
		<a href="javascript:;" style="font-size:25px;" id="hide-menu">
			<span class="glyphicon glyphicon-circle-arrow-right"></span>
		</a>
	</div>
</nav>
<script>
function toggleMenu()
{
	var $this = jQuery('#hide-menu');
	if( !$this.hasClass('little') )
	{
		jQuery('#menu').removeClass('col-xs-2 col-md-2').addClass('little');
		jQuery('#content').removeClass('col-xs-offset-2 col-md-offset-2 col-xs-10 col-md-10')
							.addClass('wide-content');
		$this.addClass('little');
		createCookie('little_menu', 1, 365);
	}
	else
	{
		jQuery('#menu').addClass('col-xs-2 col-md-2').removeClass('little');
		jQuery('#content').removeClass('wide-content').addClass('col-xs-offset-2 col-md-offset-2 col-xs-10 col-md-10');
		$this.removeClass('little');
		eraseCookie('little_menu');
	}
	
}
jQuery(function()
{
	if( parseInt(readCookie('little_menu')) === 1 )
	{
		toggleMenu();
	}
	jQuery('#hide-menu').click(function()
	{
		toggleMenu();
	});
	jQuery('#menu').fadeIn();
});

</script>