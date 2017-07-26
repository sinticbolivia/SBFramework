<?php
$template_dir = TEMPLATES_DIR . SB_DS . 'default';
$template_url = TEMPLATES_URL . '/default';
$tab = SB_Request::getString('tab', 'mosaico');
$images = array();
$images_dir = 'backgrounds' . SB_DS . (($tab == 'mosaico') ? 'mosaico' : 'extrude');
$extensions = array('jpg', 'jpeg', 'gif', 'png');
$dh = opendir($template_dir . SB_DS . $images_dir);
while( ($file = readdir($dh)) !== false )
{
	if( $file{0} == '.' ) continue;
	$ext = pathinfo($file, PATHINFO_EXTENSION);
	if( !in_array($ext, $extensions) ) continue;
	$images[] = array('path' => $template_dir . SB_DS . $images_dir . SB_DS . $file, 
						'url' => $template_url . '/' . $images_dir . '/' . $file,
						'type' => $tab
	);
}
closedir($dh);
?>
<!doctype html>
<html>
<head>
<script type="text/javascript">
    if (parent) 
	{
        var oHead = document.getElementsByTagName("head")[0];
        var arrStyleSheets = parent.document.getElementsByTagName("link");
        var scripts = parent.document.getElementsByTagName("script");
        //console.log(arrStyleSheets);
        for (var i = 0; i < arrStyleSheets.length; i++)
        {
            oHead.appendChild(arrStyleSheets[i].cloneNode(true));
        }
        for (var i = 0; i < scripts.length; i++)
        {
            oHead.appendChild(scripts[i].cloneNode(true));
        }
}
var bg_design 	= parent.document.getElementById('bg-design');
var bg_image 	= parent.document.getElementById('bg-image');
var dialog 		= parent.document.getElementById('uploader-dialog');
top.jQuery(function()
{
	
});
function setBackgroundImage()
{
	if( this.dataset.type == 'mosaico' )
	{
		bg_design.value = 'repeat';
	}
	if( this.dataset.type == 'extrusion' )
	{
		bg_design.value = 'expand';
	}
	bg_image.src = this.dataset.url;
	bg_image.style.display = 'inline';
	var params = 'task=set_bg_image&file='+this.dataset.file+'&url='+this.dataset.url+'&path='+this.dataset.path+'&type='+this.dataset.type;
	top.jQuery.post('<?php print SB_Route::_('index.php')?>', params, function(res)
	{
		top.jQuery(dialog).modal('hide');
	});
}
</script>
<style>
.select-image{display:block;width:50px;height:50px;border:2px solid #ececec;}
.select-image:FOCUS{border:2px solid #2098FF;}
</style>
</head>
<body>
<div id="uploader-tabs">
	<!-- Nav tabs -->
  	<ul class="nav nav-tabs" role="tablist">
    	<li <?php print $tab == 'mosaico' ? 'class="active"' : ''; ?>>
    		<a href="<?php print SB_Route::_('index.php?task=get_bg_images&tab=mosaico'); ?>" aria-controls="home" role="tab" data-toggle="tab"><?php print SB_Text::_('Mosaico'); ?></a></li>
    	<li <?php print $tab == 'extrusion' ? 'class="active"' : ''; ?>>
    		<a href="<?php print SB_Route::_('index.php?task=get_bg_images&tab=extrusion'); ?>" aria-controls="profile" role="tab" data-toggle="tab"><?php print SB_Text::_('Extrusion'); ?></a></li>
  	</ul>
  	<!-- Tab panes -->
  	<div class="tab-content">
    	<div role="tabpanel" class="tab-pane active">
    		<?php foreach($images as $img): ?>
    		<div class="pull-left" style="margin:0 5px 5px 0;">
    			<a href="javascript:;" class="select-image" data-type="<?php print $img['type']; ?>" data-url="<?php print $img['url']; ?>"
    				data-file="<?php print basename($img['path']); ?>" data-path="<?php print $img['path']; ?>"
    				onclick="setBackgroundImage.call(this);">
    				<img style="width:100%;height:100%;" src="<?php print $img['url']; ?>" alt="" />
    			</a>
    		</div>
    		<?php endforeach; ?>
    		<div class="clearfix"></div>
    	</div><!-- end  -->
  	</div>
</div><!-- end id="uploader-tabs" -->
</body></html>