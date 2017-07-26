<?php
?>
<div class="wrap">
	<h1 id="page-title"><?php print $page_title; ?></h1>
    <p><?php printf(__('Total modules: %d', 'modules'), count($available_modules)); ?></p>
	<table class="table">
	<thead>
	<tr>
		<th width="40">&nbsp;</th>
		<th><?php print SB_Text::_('Name', 'modules'); ?></th>
		<th><?php print SB_Text::_('Actions', 'modules'); ?></th>
	</tr>
	</thead>
	<tbody>
	<?php if( count($available_modules) ): foreach($available_modules as $mod): ?>
	<tr>
		<td width="80">
			<img src="<?php printf("%s/images/modules_icon.gif", BASEURL);?>" alt="<?php print $mod->Name; ?>" width="40" /></td>
		<td>
            <div><b><?php print isset($mod->Names->{LANGUAGE}) ? $mod->Names->{LANGUAGE} : $mod->Name; ?></b></div>
            <p><?php print isset($mod->Descriptions->{LANGUAGE}) ? $mod->Descriptions->{LANGUAGE} : $mod->Description; ?></p>
            <div class="row" style="font-size:12px;">
				<div class="col-md-3">
                    <?php _e('Author:', 'modules') ?> <a href="<?php print !empty($mod->Website) ? $mod->Website : '#'; ?>" target="_blank">
                    <?php print $mod->Author; ?></a>
                </div>
                <div class="col-md-2">
                    <?php _e('Version:', 'modules'); print isset($mod->Version) ? $mod->Version : ''; ?> 
                </div>
                <div class="col-md-3">
                    <?php print isset($mod->Email) ? sprintf("%s <a href=\"mailto:%s?subject=%s\">%s</a>", 
                                                                __('Email:', 'modules'), 
                                                                $mod->Email, 
                                                                __('About your plugin', 'modules'),
                                                                $mod->Email) : ''; ?> 
                </div>
            </div>
		</td>
		<td>
			<?php if( in_array($mod->Id, $enabled_modules) ): ?>
			<a href="<?php print SB_Route::_('index.php?mod=modules&task=disable_module&the_mod='.$mod->Id) ?>" class="btn btn-danger btn-xs">
				<?php print SB_Text::_('Disable', 'modules'); ?>
			</a>
			<?php else: ?>
			<a href="<?php print SB_Route::_('index.php?mod=modules&task=enable_module&the_mod='.$mod->Id) ?>" class="btn btn-primary btn-xs">
				<?php print SB_Text::_('Enable', 'modules'); ?>
			</a>
			<?php endif; ?>
		</td>
	</tr>
	<?php endforeach; else: ?>
	<tr><td colspan="2"><?php print SB_Text::_('There are no modules installed', 'modules'); ?></td></tr>
	<?php endif; ?>
	</tbody>
	</table>
</div><!-- end class="container" -->