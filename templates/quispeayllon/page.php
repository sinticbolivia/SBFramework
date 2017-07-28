<?php
$sidebar = '';
if( $article->type == 'post' )
{
	$sidebar = 'blog';
}
elseif( $article->type = 'libros' )
{
	$sidebar = 'libros';
}
lt_get_header();
?>
	<div ><?php SB_MessagesStack::ShowMessages(); ?></div>
	<div id="wrap-content">
		<div class="container">
			<div id="content" class="col-sm-12 col-md-9">
				<?php sb_show_module(); ?>
				<div class="social-buttons">
					<!-- Go to www.addthis.com/dashboard to customize your tools -->
					<div class="addthis_inline_share_toolbox"></div>
				</div>
			</div>
			<?php lt_get_sidebar($sidebar); ?>
		</div>
	</div>
<?php lt_get_footer(); ?>