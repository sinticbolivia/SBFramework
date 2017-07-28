<?php 
/**
* Template: Contenido Completo
*/
lt_get_header();
?>
	<div id="wrap-content" class="row">
		<div id="content" class="">
			<h1 id="content-title"><?php print $article->TheTitle(); ?> <span class="icon icon-calc"></span></h1>
			<div id="content-body">
				<div class="container">
					<?php SB_MessagesStack::ShowMessages(); ?>
					<?php print $article->TheContent(); ?>
				</div>
			</div><!-- end id="content-body" -->
		</div><!-- end id="content" -->
	</div>
<?php
lt_get_footer();
?>