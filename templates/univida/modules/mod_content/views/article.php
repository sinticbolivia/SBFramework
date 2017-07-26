<?php
?>
<div class="container">
	<div class="row">
		<div class="col-md-12 centered">
			<h3><span><?php print $article->title; ?></span></h3>
		</div>
	</div>
</div>
<div class="container content">
	<?php print $article->TheContent(); ?>
</div>