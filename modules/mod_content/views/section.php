<?php
?>
<div class="lt-section">
	<h1><?php print $section->name; ?></h1>
	<div><?php print $section->description; ?></div>
	<div class="lt-section-articles">
		<?php foreach($articles as $a): ?>
		<div class="lt-article">
			<h2 class="title"><?php print $a->title; ?></h2>
			<div class="content"><?php print $a->excerpt; ?></div>
		</div>
		<?php endforeach; ?>
	</div>
</div>