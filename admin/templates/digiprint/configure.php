<?php
$id 		= SB_Request::getInt('id');
$item_id	= SB_Request::getInt('item_id');
$service    = new SB_MBProduct($id);
$setting	= SB_Request::getString('setting');
if( $setting )
    $service->$setting    = json_decode($service->$setting);

//print_r($service->$setting);
$_variations = SB_Session::getVar('variations');
//print_r($_variations);
$variations = isset($_variations['product_'.$id]) ? $_variations['product_'.$id] : array();
if( $item_id && (!count($variations) || !$_variations) )
{
	$variations = (array)mb_get_order_item_meta($item_id, '_variations');
	//print_r($variations);
}
//print_r($variations);
$cfg_file = dirname(__FILE__) . SB_DS . 'configure-'.$setting.'.php';
lt_get_header();

?>
<?php if( SB_Request::getTask() == 'save_order_item_variation' ): ?>
<script>parent.jQuery('#modal-configure').modal('hide');</script>
<?php die();endif; ?>
<style>#top-bar,#menu{display:none !important;}#content{background:#fff;}</style>
<div id="content" class="col-md-12">
    <div class="wrap">
        <h2>Configuracion</h2>
        <?php if( file_exists($cfg_file) ): include 'configure-'.$setting.'.php'; ?>
        <?php else: ?>
        <a href="<?php print SB_Route::_('index.php?tpl_file=configure&id='.$id.'&setting=color'. ($item_id ? '&item_id='.$item_id : '')); ?>" class="btn btn-primary">Configurar Color</a>
        <a href="<?php print SB_Route::_('index.php?tpl_file=configure&id='.$id.'&setting=talla'. ($item_id ? '&item_id='.$item_id : '')); ?>" class="btn btn-primary">Configurar Tallas</a>
        <a href="<?php print SB_Route::_('index.php?tpl_file=configure&id='.$id.'&setting=genero'. ($item_id ? '&item_id='.$item_id : '')); ?>" class="btn btn-primary">Configurar Genero</a> 
        <a href="<?php print SB_Route::_('index.php?tpl_file=configure&id='.$id.'&setting=estilo'. ($item_id ? '&item_id='.$item_id : '')); ?>" class="btn btn-primary">Configurar Estilos</a>
        <?php endif; ?>
    </div>
    <div class="clearfix"></div>
</div>
<?php lt_get_footer(); ?>
