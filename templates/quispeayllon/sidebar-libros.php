<?php
$libros = LT_HelperContent::GetArticles(array(
			'type'			=> 'libros',
			'rows_per_page' => 4,
			'order_by'		=> 'rand'
		));
?>
<div id="sidebar" class="col-sm-12 col-md-3">
	<div class="widget">
		<h2 class="title"><?php print _e('Informaci&oacute;n', 'qa'); ?></h2>
		<div class="body">
			PEDIDOS:<br/>
			Z. 12 de Octubre Av. 6 de Marzo Nro. 1000 of. B-14<br/>
			Frente de la Aduana<br/>
			El Alto<br/>
			Telf: 77707447 - 71290190 - 75884885
		</div>
	</div>
	<div class="widget">
		<h2 class="title"><?php print _e('Otros Libros', 'qa'); ?></h2>
		<div class="body">
			<ul class="libros-sidebar">
				<?php foreach($libros['articles'] as $l): ?>
				<li class="libro">
					<div class="image">
						<a href="<?php print $l->link; ?>">
							<?php print $l->TheThumbnail(); ?>
						</a>
					</div>
					<div class="title">
						<a href="<?php print $l->link; ?>"><?php print $l->title; ?></a>
					</div>
				</li>
				<?php endforeach; ?>
			</ul>
		</div>
	</div>
</div><!-- end id="sidebar" -->