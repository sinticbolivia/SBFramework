<?php
class SB_MBWidgetPages extends SB_Widget
{
	public $whole_items;
	
	public function __construct()
	{
		parent::__construct(__('Pages', 'content'));
		
	}
	public function Render($args = array())
	{
		$def_args = array(
			'title'		=> $this->title,
			'in'		=> null,
			'exclude'	=> null,
			'orderby'	=> 'name',
			'order'		=> 'asc'
		);
		$args = array_merge($def_args, $args);
		$pages = LT_HelperContent::GetArticles(array(
			'rows_per_page' => -1
		));
		
		?>
		<div class="widget">
			<h2 class="title"><?php print $args['title']; ?></h2>
			<div class="body">
				<ul class="pages-list menu">
					<?php foreach($pages as $p): ?>
					<li><a href="<?php print $p->link; ?>"><?php print $p->title; ?></a></li>
					<?php endforeach; ?>
				</ul>
			</div>
		</div>
		<?php
	}
}
sb_register_widget('SB_MBWidgetPages');