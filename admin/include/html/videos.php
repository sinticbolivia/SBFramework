<?php
$query = "SELECT COUNT(*) as total_rows FROM contenido WHERE tipo = 'video'";
$res = $dbh->query($query);
$limit = 20;
$total_rows = $res->fetch_object()->total_rows;
$total_pages = ceil($total_rows / $limit);
$page 	= isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = $page == 1 ? 0 : ($page - 1) * $limit; 
$query = "SELECT * FROM contenido WHERE tipo = 'video' ORDER BY fecha DESC LIMIT $offset,$limit";
$res = $dbh->query($query);
?>
<h1 id="title">Videos</h1>
<?php print SB_MessagesStack::ShowMessages(); ?>
<a href="index.php?mod=videos&view=new" class="button primary">Nuevo</a>
<table class="listing">
<thead>
<tr>
	<th>#</th>
	<th>Imagen</th>
	<th>Titulo</th>
	<th>Accion</th>
</tr>
</thead>
<tbody>
<?php $i = 1; while( $v = $res->fetch_object() ): ?>
<tr>
	<td><?php print $i; ?></td>
	<td>
		<div class="image" data-url="<?php print $v->url; ?>">
			<?php if( stristr($v->url, 'youtube.com') ): ?>
			<img src="<?php print sb_get_youtube_img($v->url); ?>" />
			<?php else: ?>
			<img src="<?php print BASEURL . '/imagen/archivos/' . $v->imagen ?>" alt="" />
			<?php endif; ?>
		</div>
	</td>
	<td><?php print $v->title ?></td>
	<td>
		<a href="<?php print BASEURL . '/' . $v->seo_title; ?>" target="_blank">Ver</a> |
		<a href="index.php?mod=videos&view=new&id=<?php print $v->id; ?>">Editar</a> | 
		<a href="index.php?mod=videos&task=delete&id=<?php print $v->id; ?>">Borrar</a>
	</td>
</tr>
<?php $i++; endwhile; ?>
</tbody>
</table>
<p>
	<span>Paginas: </span>
	<?php for($i = 1; $i <= $total_pages; $i++): ?>
	<a href="index.php?mod=videos&page=<?php print $i; ?>" <?php print ($i == $page) ? 'class="current"' : ''; ?>><?php print $i; ?></a>
	<?php endfor;?>
</p>