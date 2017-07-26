<?php
define('LT_ADMIN', 1);

require_once dirname(dirname(__FILE__)) . '/init.php';
require_once INCLUDE_DIR . SB_DS . 'template-functions.php';
require_once ADM_INCLUDE_DIR . SB_DS . 'functions.php';
if( !sb_is_user_logged_in() )
{
	sb_redirect(SB_Route::_('login.php'));
}
$type = SB_Request::getString('type', 'frontend');
//##get current template
$ctemplate = sb_get_template_info(sb_get_template_dir($type) . SB_DS . 'style.css');
$templates = sb_get_templates($type);
$templates_url = ($type == 'frontend') ? TEMPLATES_URL : ADMIN_URL . '/templates';

if( $activate = SB_Request::getString('activate') )
{
	$param = ($type == 'frontend') ? 'template_frontend' : 'template_admin';
	sb_update_parameter($param, $activate);
	SB_MessagesStack::AddMessage(SBText::_('The template has been activated'), 'success');
	sb_redirect(SB_Route::_('templates.php?type='.$type));
}
//lt_get_header();
ob_start();
?>
<style>
.template{}
.template .panel-body{height:150px;position:relative;overflow:hidden;}
.template .panel-body img{position:absolute;top:0;right:0;bottom:0;left:0;max-height:none !important;}
.template .panel-body span{position: absolute;padding:10px;color:#fff;overflow:auto;
z-index: 10;
background: rgba(0,0,0,0.8);
display: block;
top: 0;
right: 0;
left: 0;
bottom: 0;opacity:0;transition:opacity 0.4s ease-in-out;}
.template .panel-body:hover span{opacity:1;}
</style>
<div id="content" class="">
	<div class="wrap">
		<?php print SB_MessagesStack::ShowMessages(); ?>
		<h2><?php print SBText::_('Templates'); ?></h2>
		<ul class="nav nav-tabs">
			<li class="<?php print $type == 'frontend' ? 'active' : ''; ?>"><a role="tab" href="<?php print SB_Route::_('templates.php?type=frontend'); ?>"><?php print SBText::_('Frontend'); ?></a></li>
			<li class="<?php print $type == 'backend' ? 'active' : ''; ?>"><a role="tab" href="<?php print SB_Route::_('templates.php?type=backend'); ?>"><?php print SBText::_('Backend'); ?></a></li>
		</ul>
		<div class="tab-content">
			<div class="tab-pane active">
				<div class="row">
					<div class="col-md-4">
						<?php 
						$img = file_exists($ctemplate['template_dir'] . SB_DS . 'screenshot.png') ? 
								$templates_url . '/' . basename($ctemplate['template_dir']) . '/screenshot.png'
								:
								BASEURL . '/images/no-template.png';
						?>
						<div class="template panel panel-default">
							<div class="panel-heading"><?php print $ctemplate['Template name']; ?></div>
							<div class="panel-body">
								<img src="<?php print $img; ?>" />
							</div>
						</div>
					</div>
					<div class="col-md-8">
						<div class="row">
							<div class="col-md-2"><b><?php _e('Author:', 'lt'); ?></b></div>
							<div class="col-md-10"><?php print $ctemplate['Template author']; ?></div>
						</div>
						<div class="row">
							<div class="col-md-2"><b><?php _e('URL:', 'lt'); ?></b></div>
							<div class="col-md-10">
								<a href="<?php print $ctemplate['Template Url']; ?>" target="_blank">
									<?php print $ctemplate['Template Url']; ?>
								</a>
							</div>
						</div>
						<div class="row">
							<div class="col-md-2"><b><?php _e('Version:', 'lt'); ?></b></div>
							<div class="col-md-10">
								<?php print isset($ctemplate['Template Version']) ? $ctemplate['Template Version'] : __('Unknow', 'lt'); ?>
							</div>
						</div>
						<div class="row">
							<div class="col-md-2"><b><?php _e('Description:', 'lt'); ?></b></div>
							<div class="col-md-10">
								<?php print isset($ctemplate['Template Description']) ? $ctemplate['Template Description'] : __('Unknow', 'lt'); ?>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
				<?php foreach($templates as $id => $tpl): if( $tpl['Template name'] == $ctemplate['Template name']) continue;?>
				<?php
				$img = BASEURL . '/images/no-template.png';
				if( file_exists($tpl['template_dir'] . SB_DS . 'screenshot.png') )
				{
					$img = $templates_url . '/' . $id . '/screenshot.png';
				} 
				?>
				<div class="col-md-4">
					<div class="template panel panel-default">
						<div class="panel-heading"><?php print $tpl['Template name']; ?></div>
						<div class="panel-body">
					   	 	<img src="<?php print $img; ?>" alt="" />
					   	 	<span>
								<b><?php print SBText::_('Author:'); ?></b><?php print $tpl['Template author']; ?><br/>
								<b><?php _e('Version:'); ?></b><?php print $tpl['Template Version']; ?><br/>
								<b><?php _e('Description:'); ?></b> <?php print $tpl['Template Description']; ?><br/>
							</span>
					  	</div>
					  	<div class="panel-footer">
					  		<a href="<?php print SB_Route::_('templates.php?type='.$type . '&activate=' . $id); ?>" 
								class="btn btn-default btn-xs">
								<?php print SBText::_('Activate'); ?>
							</a>
					  		<a href="<?php print $tpl['Template Url']; ?>" class="btn btn-default btn-xs" target="_blank">
								<?php print SBText::_('Website'); ?>
							</a>
					  	</div>
					</div>
				</div>
				<?php endforeach; ?>
				</div>
				<div class="clearfix"></div>
			</div>
		</div><!-- end class="tab-content" -->
	</div>
</div>
<?php 
$_html_content = ob_get_clean();
$template_file = 'index.php';
$mod = $view = 'templates';
SB_Request::setVar('view', $view);
$app->ProcessModule(null);
sb_set_view_var('_html_content', $_html_content, 'templates');
sb_process_template($template_file);
sb_show_template(); 
//$dbh->Close();