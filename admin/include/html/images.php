<?php
$query = "SELECT COUNT(*) as total_rows FROM contenido WHERE tipo = 'imagen'";
$res = $dbh->query($query);
$limit = 20;
$total_rows = $res->fetch_object()->total_rows;
$total_pages = ceil($total_rows / $limit);
$page 	= isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = $page == 1 ? 0 : ($page - 1) * $limit; 
$query = "SELECT * FROM contenido WHERE tipo = 'imagen' ORDER BY fecha DESC LIMIT $offset,$limit";
$res = $dbh->query($query);
?>
<h1 id="title">Imagenes</h1>
<?php print SB_MessagesStack::ShowMessages(); ?>
<a href="index.php?mod=images&view=new" class="button primary">Nuevo</a>
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
<?php $i = 1; while( $img = $res->fetch_object() ): ?>
<tr>
	<td><?php print $i; ?></td>
	<td><div  class="image"><img src="<?php print BASEURL . '/imagen/archivos/' . $img->imagen ?>" alt="" /></div></td>
	<td><?php print $img->title ?></td>
	<td>
		<a href="<?php print BASEURL . '/imagen/' . $img->seo_title; ?>" target="_blank">Ver</a> |
		<a href="index.php?mod=images&view=new&id=<?php print $img->id; ?>">Editar</a> | 
		<a href="index.php?mod=images&task=delete&id=<?php print $img->id; ?>">Borrar</a>
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