<?php
class LT_Template3DConstructora
{
	public function __construct()
	{
		$this->AddActions();
		$this->AddShortcodes();
	}
	protected function AddActions()
	{
	}
	protected function AddShortcodes()
	{
		SB_Shortcode::AddShortcode('3dc_gallery', array($this, 'shortcode_3dc_gallery'));
	}
	public function shortcode_3dc_gallery($args)
	{
		$galeria_url = TEMPLATE_URL . '/images/galeria';
		ob_start();
		$images = scandir(TEMPLATE_DIR . SB_DS . 'images' . SB_DS . 'galeria');
		?>
		<div class="isotope-gallery">
			<?php foreach($images as $img): if( $img == '.' || $img == '..' ) continue; ?>
			<div class="gallery-img">
				<a href=""><img src="<?php print $galeria_url . '/' . $img ?>" alt="" /></a>
			</div>
			<?php endforeach; ?>
		</div>
		<?php
		return ob_get_clean();
	}
}
new LT_Template3DConstructora();