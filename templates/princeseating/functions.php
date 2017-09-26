<?php
define('THEME_PS_DIR', dirname(__FILE__));
define('THEME_PS_URL', TEMPLATES_URL . '/' . basename(THEME_PS_DIR));
define('LITERATURE_UPLOADS_DIR', UPLOADS_DIR . SB_DS . 'literature');
define('LITERATURE_UPLOADS_URL', UPLOADS_URL . '/literature');
if( !is_dir(LITERATURE_UPLOADS_DIR) )
	mkdir(LITERATURE_UPLOADS_DIR);
	
class LT_ThemePrinceseating
{
	protected function __construct()
	{
		$this->AddActions();
	}
	protected function AddActions()
	{
		SB_Module::add_action('content_types', array($this, 'action_content_types'));
		SB_Module::add_action('emono_query_products_args', array($this, 'emono_query_products_args'));
		SB_Module::add_action('init', array($this, 'action_init'));
		if( lt_is_admin() )
		{
			SB_Module::add_action('content_event_sidebar', array($this, 'action_content_event_sidebar'));
			SB_Module::add_action('mb_product_after_description', array($this, 'action_mb_product_after_description'));
			SB_Module::add_action('product_tabs', array($this, 'action_product_tabs'));
			SB_Module::add_action('product_tabs_content', array($this, 'action_product_tabs_content'));
			SB_Module::add_action('content_literature_sidebar', array($this, 'action_content_literature_sidebar'));
			SB_Module::add_action('content_data_reps', array($this, 'content_data_reps'));
		}
	}
	public function action_content_types($types)
	{
		$types['page']['features']['calculated_dates'] = false;
		$types['news'] = array(
					'labels'	=> array(
							'menu_label'	=> __('News', 'ps'),
							'new_label'		=> __('New News', 'ps'),
							'edit_label'	=> __('Edit News', 'ps'),
							'listing_label'	=> __('News', 'ps')
					),
					'features'	=> array(
							'featured_image'	=> true,
							'use_dates'			=> false,
							'calculated_dates'	=> false
					)
		);
		$types['event'] = array(
					'labels'	=> array(
							'menu_label'	=> __('Events', 'ps'),
							'new_label'		=> __('New Event', 'ps'),
							'edit_label'	=> __('Edit Event', 'ps'),
							'listing_label'	=> __('Events', 'ps')
					),
					'features'	=> array(
							'featured_image'	=> true,
							'use_dates'			=> true,
							'calculated_dates'	=> false
					)
		);
		$types['literature'] = array(
					'labels'	=> array(
							'menu_label'	=> __('Literature', 'ps'),
							'new_label'		=> __('New Literature', 'ps'),
							'edit_label'	=> __('Edit Literature', 'ps'),
							'listing_label'	=> __('Literature', 'ps')
					),
					'features'	=> array(
							'featured_image'	=> true,
							'use_dates'			=> false,
							'calculated_dates'	=> false
					)
		);
		$types['fabrics'] = array(
					'labels'	=> array(
							'menu_label'	=> __('Fabrics', 'ps'),
							'new_label'		=> __('New Fabrics', 'ps'),
							'edit_label'	=> __('Edit Fabrics', 'ps'),
							'listing_label'	=> __('Fabrics', 'ps')
					),
					'features'	=> array(
							'featured_image'	=> true,
							'use_dates'			=> false,
							'calculated_dates'	=> false
					),
					'section'	=> array(
						'for_object'	=> 'fabrics',
						'labels'		=> array(
								'menu_label' 	=> '<span class="glyphicon glyphicon-folder-open"></span> Groups', 
								'new_label' 	=> 'New Group',
								'edit_label'	=> 'Edit Group',
								'listing_label'	=> 'Fabrics Groups'
						)
					)
		);
		$types['finishes'] = array(
					'labels'	=> array(
							'menu_label'	=> __('Finishes', 'ps'),
							'new_label'		=> __('New Finish', 'ps'),
							'edit_label'	=> __('Edit Finish', 'ps'),
							'listing_label'	=> __('Finishes', 'ps')
					),
					'features'	=> array(
							'featured_image'	=> true,
							'use_dates'			=> false,
							'calculated_dates'	=> false
					),
					'section'	=> array(
						'for_object'	=> 'finishes',
						'labels'		=> array(
								'menu_label' 	=> '<span class="glyphicon glyphicon-folder-open"></span> Groups', 
								'new_label' 	=> 'New Group',
								'edit_label'	=> 'Edit Group',
								'listing_label'	=> 'Finishes Groups'
						)
					)
		);
		$types['colors'] = array(
					'labels'	=> array(
							'menu_label'	=> __('Colors', 'ps'),
							'new_label'		=> __('New Color', 'ps'),
							'edit_label'	=> __('Edit Color', 'ps'),
							'listing_label'	=> __('Colors', 'ps')
					),
					'features'	=> array(
							'featured_image'	=> true,
							'use_dates'			=> false,
							'calculated_dates'	=> false
					),
					/*
					'section'	=> array(
						'for_object'	=> 'colors',
						'labels'		=> array(
								'menu_label' 	=> '<span class="glyphicon glyphicon-folder-open"></span> Groups', 
								'new_label' 	=> 'New Group',
								'edit_label'	=> 'Edit Group',
								'listing_label'	=> 'Colors Groups'
						)
					)
					*/
		);
		$types['reps'] = array(
					'labels'	=> array(
							'menu_label'	=> __('Representatives', 'ps'),
							'new_label'		=> __('New Rep', 'ps'),
							'edit_label'	=> __('Edit Rep', 'ps'),
							'listing_label'	=> __('Respresentatives', 'ps')
					),
					'features'	=> array(
							'featured_image'	=> true,
							'use_dates'			=> false,
							'calculated_dates'	=> false
					)
		);
		return $types;
	}
	public function emono_query_products_args(&$args)
	{
		$args['search_cols'][] = 'p.product_code';
		//print_r($args);
	}
	public function action_init()
	{
		$task = SB_Request::getTask();
		if( $task )
		{
			$method = 'task_' . str_replace('ps_', '', $task);
			if( method_exists($this, $method) )
			{
				call_user_func(array($this, $method));
			}
		}
	}
	public function action_content_event_sidebar($content)
	{
		?>
		<div class="widget">
			<h2 class="title"><?php print SB_Text::_('Event Options', 'content'); ?></h2>
			<div class="body">
				<div class="form-group">
					<label><?php _e('Address:', 'ps'); ?></label>
					<input type="text" name="meta[_address]" value="<?php print isset($content) ? $content->_address : ''; ?>" class="form-control" />
				</div>
				<div class="form-group">
					<label><?php _e('Date:', 'ps'); ?></label>
					<input type="text" name="meta[_date]" value="<?php print isset($content) ? $content->_date : ''; ?>" class="form-control datepicker" />
				</div>
			</div>
		</div>
		<?php
	}
	public function action_mb_product_after_description($product)
	{
		?>
		<div class="form-group">
			<label><?php _e('Features', 'ps'); ?></label>
			<textarea name="meta[_features]" class="form-control"><?php print $product->_features; ?></textarea>
		</div>
		<?php
	}
	public function action_product_tabs()
	{
		?>
		<li><a href="#dimensions" data-toggle="tab"><?php _e('Dimensions', 'ps'); ?></a></li>
		<li><a href="#fabrics" data-toggle="tab"><?php _e('Fabrics', 'ps'); ?></a></li>
		<li><a href="#finishes" data-toggle="tab"><?php _e('Finishes', 'ps'); ?></a></li>
		<li><a href="#colors" data-toggle="tab"><?php _e('Colors', 'ps'); ?></a></li>
		<?php
	}
	public function action_product_tabs_content($product)
	{
		//var_dump($product->_attributes);
		$attr 			= $product ? json_decode($product->_attributes) : array();
		$height 		= '';
		$seat_height 	= '';
		$width 			= '';
		$depth 			= '';
		$weight 		= '';
		//##check if attributes are in correct format
		if( !isset($attr->height) )
		{
			$new_attr = (object)array(
				'height'		=> '',
				'seat_height'	=> '',
				'width'			=> '',
				'depth'			=> '',
				'weight'		=> ''
			);
			if( $attr )
			{
				foreach($attr as $a)
				{
					if( $a->taxonomy == 'pa_height' )
						$new_attr->height = $a->value;
					elseif( $a->taxonomy == 'pa_seat-height' )
						$new_attr->seat_height = $a->value;
					elseif( $a->taxonomy == 'pa_width' )
						$new_attr->width = $a->value;
					elseif( $a->taxonomy == 'pa_depth' )
						$new_attr->depth = $a->value;
					elseif( $a->taxonomy == 'pa_weight' )
						$new_attr->weight = $a->value;
				}
			}
			$attr = $new_attr;
		}
		//print_r($attr);
		
		//##fabrics
		sb_include_module_helper('content');
		$fabrics	= array();
		$finishes 	= array();
		$c_fabrics 	= array();
		$c_finishes	= array();
		$colors		= array();
		$c_colors	= array();
		if( $product )
		{
			$c_fabrics	= (array)json_decode($product->_fabrics);
			$c_finishes = (array)json_decode($product->_finishes);
			$c_colors	= (array)json_decode($product->_colors);
			/*
			$fabrics = LT_HelperContent::GetArticles(array(
				'type' 			=> 'fabrics',
				'rows_per_page'	=> -1,
				'order_by'		=> 'title',
				'order'			=> 'asc'
			));
			$finishes = LT_HelperContent::GetArticles(array(
				'type' 			=> 'finishes',
				'rows_per_page'	=> -1,
				'order_by'		=> 'title',
				'order'			=> 'asc'
			));
			*/
			$fabrics	= LT_HelperContent::GetSections(0, 'fabrics');
			$finishes	= LT_HelperContent::GetSections(0, 'finishes');
			$colors 	= LT_HelperContent::GetArticles(array(
				'type' 			=> 'colors',
				'rows_per_page'	=> -1,
				'order_by'		=> 'title',
				'order'			=> 'asc'
			));
		}
		?>
		<div id="dimensions" class="tab-pane">
			<div class="row">
				<div class="col-md-6">
					<?php //if( is_object($attr) || is_array($attr) ): foreach($attr as $i => $a): ?>
						<?php //if( $a->taxonomy == 'pa_height' ): ?>
						<div class="form-group">
							<label><?php _e('Height', 'ps'); ?></label>
							<input type="text" name="meta[_attributes][height]" value="<?php print $attr->height ?>" class="form-control" />
						</div>
						<?php //elseif( $a->taxonomy == 'pa_seat-height' ): ?>
						<div class="form-group">
							<label><?php _e('Seat Height', 'ps'); ?></label>
							<input type="text" name="meta[_attributes][seat_height]" value="<?php print $attr->seat_height ?>" class="form-control" />
						</div>
						<?php //elseif( $a->taxonomy == 'pa_width' ): ?>
						<div class="form-group">
							<label><?php _e('Width', 'ps'); ?></label>
							<input type="text" name="meta[_attributes][width]" value="<?php print $attr->width ?>" class="form-control" />
						</div>
						<?php //elseif( $a->taxonomy == 'pa_depth' ): ?>
						<div class="form-group">
							<label><?php _e('Depth', 'ps'); ?></label>
							<input type="text" name="meta[_attributes][depth]" value="<?php print $attr->depth ?>" class="form-control" />
						</div>
						<?php //elseif( $a->taxonomy == 'pa_weight' ): ?>
						<div class="form-group">
							<label><?php _e('Weight', 'ps'); ?></label>
							<input type="text" name="meta[_attributes][weight]" value="<?php print $attr->weight ?>" class="form-control" />
						</div>
						<?php //endif; ?>
					<?php //endforeach; endif; ?>
				</div>
				<div class="col-md-6">
				</div>
			</div>
		</div><!-- end id="dimensions" -->
		<div id="fabrics" class="tab-pane">
			<div class="form-group">
				<label><?php _e('Select Fabrics'); ?></label>
				<div style="width:100%;height:200px;overflow:auto;" class="form-control">
					<?php foreach($fabrics as $g): ?>
					<div>
						<label>
							<input type="checkbox" name="meta[_fabrics][]" value="<?php print $g->section_id; ?>"
								<?php print in_array($f->section_id, $c_fabrics) ? 'checked' : ''; ?>/>
							<?php print $f->name; ?>
						</label>
					</div>
					<?php endforeach; ?>
				</div>
			</div>
		</div><!-- end id="fabrics" -->
		<div id="finishes" class="tab-pane">
			<div class="form-group">
				<label><?php _e('Select Finishes'); ?></label>
				<div style="width:100%;height:200px;overflow:auto;" class="form-control">
					<?php foreach($finishes as $g): ?>
					<div>
						<label>
							<input type="checkbox" name="meta[_finishes][]" value="<?php print $g->section_id; ?>"
								<?php print in_array($g->section_id, $c_finishes) ? 'checked' : ''; ?>/>
							<?php print $g->name; ?>
						</label>
					</div>
					<?php endforeach; ?>
				</div>
			</div>
		</div><!-- end id="finishes" -->
		<div id="colors" class="tab-pane">
			<div class="form-group">
				<label><?php _e('Select Colors'); ?></label>
				<div style="width:100%;height:200px;overflow:auto;" class="form-control">
					<?php foreach($colors['articles'] as $c): ?>
					<div>
						<label>
							<input type="checkbox" name="meta[_colors][]" value="<?php print $c->content_id; ?>"
								<?php print in_array($c->content_id, $c_colors) ? 'checked' : ''; ?>/>
							<?php print $c->title; ?>
						</label>
					</div>
					<?php endforeach; ?>
				</div>
			</div>
		</div><!-- end id="finishes" -->
		<?php
	}
	public function task_specsheet()
	{
		$id 		= SB_Request::getInt('id');
		if( !$id )
		{
			lt_die(__('Invalid product identifier', 'ps'));
		}
		$product 	= new SB_MBProduct($id);
		if( !$product->product_id )
		{
			lt_die(__('The product does not exists', 'ps'));
		}
		$ops = sb_get_parameter('mb_settings');
		ob_start();
		include THEME_PS_DIR . SB_DS . 'pdf-specs.php';
		$html = ob_get_clean();
		if( SB_Request::getInt('html') )
			die($html);
		define('DOMPDF_ENABLE_CSS_FLOAT', true);
		$pdf = mb_get_pdf_instance('', '', 'dompdf');
		$pdf->loadHtml($html);
		$pdf->render();
		$pdf->stream(sprintf(__('product-specs-%s-%d', 'mb'), $product->product_code, $product->product_id),
				array('Attachment' => SB_Request::getInt('download', 1), 
						'Accept-Ranges' => 1));
		die();
	}
	public function action_content_literature_sidebar($content)
	{
		if( !$content )
			return false;
		?>
		<div class="widget">
			<h2 class="title"><?php _e('PDF File', 'ps'); ?></h2>
			<div class="body">
				<div id="pdf-container">
					<?php if( $content->_pdf_file ): ?>
					<a href="<?php print UPLOADS_DIR . '/literature/' . $content->_pdf_file; ?>" target="_blank">
						<?php print $content->_pdf_file; ?>
					</a>
					<?php endif; ?>
				</div>
				<div>&nbsp;</div>
				<div id="select-pdf" class="btn btn-default btn-xs">
					<?php _e('Upload PDF', 'ps'); ?>
				</div>
				<a href="javascript:;" id="btn-remove-pdf" class="btn btn-danger btn-xs"
					style="<?php print $content->_pdf_file ? '' : 'display:none;'; ?>">
					<?php _e('Delete', 'content'); ?>
				</a>
				<div id="uploading-pdf" style="display:none;"><?php _e('Uploading file...', 'ps'); ?></div>
			</div>
		</div>
		<script>
		jQuery(function()
		{
			window.pdf_uploader = new qq.FineUploaderBasic({
				button: document.getElementById('select-pdf'),
				request: {
					endpoint: '<?php print SB_Route::_('index.php?task=ps_upload_pdf&id='.$content->content_id); ?>'
				},
				validation: {allowedExtensions: ['pdf']},
				callbacks: 
				{
					onUpload: function(id, fileName) {jQuery('#uploading-pdf').css('display', 'block');},
					onComplete: function(id, fileName, res) 
					{
						jQuery('#uploading-pdf').css('display', 'none');
						if (res.success) 
						{
							jQuery('#pdf-container').append('<a href="'+res.pdf_url+'" target="_blank">'+res.pdf_file+'</a>');
							jQuery('#btn-remove-pdf').css('display', 'inline');
						} 
						else 
						{
							alert(res.error);
						}
					}
				}
			});
			jQuery(document).on('click', '#btn-remove-pdf', function()
			{
				jQuery.get('<?php print SB_Route::_('index.php?task=ps_delete_pdf&id='.$content->content_id)?>', function(res)
				{
					if( res.status == 'ok' )
					{
						jQuery('#pdf-container').html('');
						jQuery('#btn-remove-pdf').css('display', 'none');
					}
					else
					{
						alert(res.error);
					}
				});
			});
		});
		</script>
		<?php
	}
	public function task_upload_pdf()
	{
		$id = SB_Request::getInt('id');
		if( !$id )
		{
			sb_response_json(array('success' => false, 'error' => __('Invalid content identifier', 'content')));
		}
		
		sb_include('qqFileUploader.php', 'file');
		$uh = new qqFileUploader();
		$uh->allowedExtensions = array('pdf');
		// Specify the input name set in the javascript.
		$uh->inputName = 'qqfile';
		// If you want to use resume feature for uploader, specify the folder to save parts.
		$uh->chunksFolder = 'chunks';
		$res = $uh->handleUpload(LITERATURE_UPLOADS_DIR);
		if( isset($res['error']) )
		{
			sb_response_json($res);
		}
		$file_path 			= LITERATURE_UPLOADS_DIR . SB_DS . $uh->getUploadName();
		$res['pdf_url']		= LITERATURE_UPLOADS_URL . '/' . $uh->getUploadName();
		$res['uploadName'] 	= $uh->getUploadName();
		$res['pdf_file']	= $uh->getUploadName();
		
		$pdf = sb_get_content_meta($id, '_pdf_file');
		if( $pdf && file_exists(LITERATURE_UPLOADS_DIR . SB_DS . $pdf) )
		{
			@unlink(LITERATURE_UPLOADS_DIR . SB_DS . $pdf);
		}
		sb_update_content_meta($id, '_pdf_file', $uh->getUploadName());
		sb_response_json($res);
	}
	public function content_data_reps($content)
	{
		$terrs = array(
			'Washington, oregon',
			'Southern Nevada, Arizona',
			'New York, Eastern Pennsylvania',
			'Utah, Colorado, New Mexico',
			'Nebraska, Iowa, Kansas, Missouri, Arkansas',
			'NC & SC',
			'Tennessee, Mississippi',
			'New England States',
			'Michigan, Ohio, Indiana, Kentucky',
			'Southern California',
			'N & S Dakota, missesota, Wisconsin',
			'Central Florida'
		);
		$c_states = $content && $content->_states ? (array)json_decode($content->_states) : array();
		//print_r($c_states);
		?>
		<div class="panel panel-primary">
			<div class="panel-heading"><h2><?php _e('Representative Information', 'ps'); ?></h2></div>
			<div class="panel-body">
				<div class="form-group">
					<label><?php _e('Address', 'ps'); ?></label>
					<input type="text" name="meta[_address]" value="<?php print $content ? $content->_address : ''; ?>" class="form-control" />
				</div>
				<div class="form-group">
					<label><?php _e('City', 'ps'); ?></label>
					<input type="text" name="meta[_city]" value="<?php print $content ? $content->_city : ''; ?>" class="form-control" />
				</div>
				<div class="form-group">
					<label><?php _e('State', 'ps'); ?></label>
					<div class="form-control" style="width:100%;height:150px;overflow:auto;">
						<?php foreach(ps_get_us_states() as $index => $state): ?>
						<label>
							<input type="checkbox" name="meta[_states][]" value="<?php print $index ?>"
								<?php print $content && in_array($index, $c_states) ? 'checked' : ''; ?> />
							<?php print $state ?>
						</label><br/>
						<?php endforeach; ?>
					</div>
				</div>
				<div class="form-group">
					<label><?php _e('Zip', 'ps'); ?></label>
					<input type="text" name="meta[_zip]" value="<?php print $content ? $content->_zip : ''; ?>" class="form-control" />
				</div>
				<div class="form-group">
					<label><?php _e('Phone', 'ps'); ?></label>
					<input type="text" name="meta[_phone]" value="<?php print $content ? $content->_phone : ''; ?>" class="form-control" />
				</div>
				<div class="form-group">
					<label><?php _e('Email', 'ps'); ?></label>
					<input type="text" name="meta[_email]" value="<?php print $content ? $content->_email : ''; ?>" class="form-control" />
				</div>
			</div>
		</div>
		<?php
	}
	
	public static function GetInstance()
	{
		static $instance;
		if( !$instance )
		{
			$instance = new LT_ThemePrinceseating();
		}
		return $instance;
	}
}
function ps_get_us_states()
{
	return array(
			"Alabama",
			"Alaska",
			"Arizona",
			"Arkansas",
			"California",
			"Colorado",
			"Connecticut",
			"Delaware",
			"District of Columbia",
			"Florida","Georgia","Hawaii","Idaho","Illinois","Indiana","Iowa","Kansas","Kentucky","Louisiana",
			"Maine","Maryland","Massachusetts","Michigan","Minnesota","Mississippi","Missouri","Montana",
			"Nebraska","Nevada","New Hampshire","New Jersey","New Mexico","New York","North Carolina",
			"North Dakota","Ohio","Oklahoma","Oregon","Pennsylvania","Rhode Island","South Carolina",
			"South Dakota","Tennessee","Texas","Utah","Vermont","Virginia","Washington","West Virginia",
			"Wisconsin","Wyoming","Canada","Caribbean","Europe"
	);
}
LT_ThemePrinceseating::GetInstance();