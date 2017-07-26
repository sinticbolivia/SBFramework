<?php
class SB_MBWidgetLatestContent extends SB_Widget
{
	public function __construct()
	{
		parent::__construct(__('Widget Last Contents', 'content'));
	}
	public function Render($args = array())
	{
		$def_args = array(
			'title'			=> __('Latest contents', 'content'),
			'show_image'	=> true,
			'type'			=> 'page',
			'orderby'		=> 'creation_date',
			'order'			=> 'desc',
			'page'			=> 1,
			'limit'			=> 6,
		);
		$args = array_merge($def_args, $args);
		extract($args);
		$page = $page <= 0 ? 1 : $page;
		$offset = $page == 1 ? 0 : ($page - 1) * $limit;
		$table = SB_DbTable::GetTable('content', true);
		$conds = array(
			'type'		=> $type,
			'status'	=> 'publish'
		);
		$contents = $table->GetRows($limit, $offset, $conds, 'LT_Article');
		//print_r($contents);
		//ob_start();
		?>
		<div class="widget widget-latest-contents widget-latest-<?php print $type; ?>">
			<h2 class="title"><?php print $title; ?></h2>
			<div class="body">
				<?php if( count($contents) ): ?>
				<ul class="pages-list menu">
					<?php foreach($contents as $c): ?>
					<li class="content">
						<?php if( $show_image ): ?>
						<div class="content-image">
							<a href="<?php print $c->link; ?>">
								<?php print $c->TheThumbnail(); ?>
							</a>
						</div>
						<?php endif; ?>
						<div class="content-title"><a href="<?php print $c->link; ?>"><?php print $c->title; ?></a></div>
					</li>
					<?php endforeach; ?>
				</ul>
				<?php else: ?>
				<span><?php _e('There were not contents found', 'content'); ?></span>
				<?php endif; ?>
			</div>
		</div>
		<?php
		//return ob_get_clean();
	}
}
sb_register_widget('SB_MBWidgetLatestContent');