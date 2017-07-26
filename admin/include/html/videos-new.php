<?php
$vid = isset($_GET['id']) ? (int)$_GET['id'] : null;
$_mod = 'videos';
$title = 'Nuevo Video';
$the_video = null;
if( $vid )
{
	$query = "SELECT * FROM contenido WHERE id = $vid LIMIT 1";
	$res = $dbh->query($query);
	$the_video = $res->fetch_object();
	$title = 'Editar Video';
}
$query = "SELECT * FROM categoria ORDER BY categoria ASC";
$res = $dbh->query($query);
?>
<h1 id="title"><?php print $title; ?></h1>
<?php print SB_MessagesStack::ShowMessages(); ?>
<form action="" method="post" enctype="multipart/form-data">
	<input type="hidden" name="task" value="save" />
	<input type="hidden" name="mod" value="<?php print $_mod; ?>" />
	<?php if( $vid ): ?>
	<input type="hidden" name="id" value="<?php print $vid; ?>" />
	<?php endif; ?>
	<div class="form-row">
		<input type="text" id="title" name="title" value="<?php print isset($_POST['title']) ? $_POST['title'] : ($the_video ? $the_video->title : ''); ?>" placeholder="Titulo" />
	</div>
	<div class="form-row">
		<label>Imagen URL:</label>
		<input type="text" name="image_url" value="<?php print isset($_POST['image_url']) ? $_POST['image_url'] : ''; ?>" style="width:50%;" />
	</div>
	<div class="form-row">
		<label>Subir Imagen:</label>
		<input type="file" name="image_file" value="" style="width:50%;" />
	</div>
	<div class="form-row">
		<label>Video Url:</label>
		<input type="text" name="yt_video_url" value="<?php print isset($_POST['yt_video_url']) ? $_POST['yt_video_url'] : ($the_video ? $the_video->url : ''); ?>" style="width:50%;" />
	</div>
	<div class="form-row">
		<label>Descripcion:</label><br/>
		<textarea rows="" cols="" style="width:100%;height:300px;" id="description" name="description"><?php print isset($_POST['description']) ? $_POST['description'] : ($the_video ? html_entity_decode($the_video->descripcion) : ''); ?></textarea>
	</div>
	<div class="form-row">
		<label>Categoria:</label>
		<select name="category">
			<option value="-1">-- categoria --</option>
			<?php while($cat = $res->fetch_object()): ?>
			<option value="<?php print $cat->id ?>" 
				<?php print (isset($_POST['category']) && $_POST['category'] == $cat->id) ? 'selected' : ($the_video && $the_video->id_categoria == $cat->id) ? 'selected' : ''; ?>>
					<?php print $cat->categoria ?>
				</option>
			<?php endwhile; ?>
		</select>
	</div>
	<div class="form-row">
		<button type="submit" class="button primary">Guardar</button>
	</div>
</form>
<script>
jQuery(function()
{
	window.neditor = new nicEditor({iconsPath : '/admin/js/nicEdit/nicEditorIcons.gif',fullPanel : true}).panelInstance('description'); 
}); 
</script>