<?php
namespace SinticBolivia\BeetleCMS\Templates\Admin\LittleBoys;
use SinticBolivia\SBFramework\Classes\SB_Language as SB_Language;
use SinticBolivia\SBFramework\Classes\SB_Module;
require_once dirname(__FILE__) . SB_DS . 'include' . SB_DS . 'hooks.customers.php';

class LT_ThemeLittleBoys
{
	public function __construct()
	{
		SB_Language::loadLanguage(LANGUAGE, 'lb', dirname(__FILE__) . SB_DS . 'locale');
		$this->AddActions();
	}
	protected function AddActions()
	{
		SB_Module::add_action('lt_tinymce_args', array($this, 'tinymce_args'));
		SB_Module::add_action('template_loaded', array($this, 'action_template_loaded'));
	}
	public function tinymce_args($args)
	{
		//$args['menubar'] = 'insert';
		//$args['toolbar'] = 'image';
		
		$args['image_list'] = array(
				array('title' => 'separador1', 'value' => 'http://500sitios.com/imag/separador1.png'),
				array('title' => 'separador2', 'value' => 'http://500sitios.com/imag/separador2.png'),
				array('title' => 'separador3', 'value' => 'http://500sitios.com/imag/separador3.png'),
				array('title' => 'separador4', 'value' => 'http://500sitios.com/imag/separador4.png'),
				array('title' => 'separador5', 'value' => 'http://500sitios.com/imag/separador5.png'),
				array('title' => 'separador6', 'value' => 'http://500sitios.com/imag/separador6.png'),
				array('title' => 'separador7', 'value' => 'http://500sitios.com/imag/separador7.png'),
				array('title' => 'separador8', 'value' => 'http://500sitios.com/imag/separador8.png'),
				array('title' => 'separador9', 'value' => 'http://500sitios.com/imag/separador9.png'),
				array('title' => 'separador10', 'value' => 'http://500sitios.com/imag/separador10.png')
				
		);
		
		return $args;
	}
	public function action_template_loaded()
	{
		SB_Module::add_action('content_types', array($this, 'action_content_types'));
		SB_Module::add_action('content_data_project', array($this, 'action_content_data_project'));
	}
	public function action_content_types($types)
	{
		/*
		$types['project']	= array(
				'labels'	=> array(
						'new_label' => __('New Project'),
						'menu_label'	=> __('Projects'),
						'edit_label'	=> __('Edit Project'),
						'listing_label'	=> __('Projects Management')
				),
				'features'	=> array(
						'featured_image'	=> true,
						'use_dates'			=> false,
						'calculated_dates'	=> false,
				)
		);
		*/
		return $types;
	}
	public function action_content_data_project($content)
	{
		return true;
		?>
		<br/>
		<div class="panel panel-default">
			<div class="panel-heading"><span><?php _e('Project Information'); ?></span></div>
			<div class="panel-body">
				<div class="form-group">
					<label><?php _e('Manager'); ?></label>
					<input type="text" name="meta[_manager]" value="" class="form-control" />
				</div>
				<div class="form-group">
					<label><?php _e('Budget'); ?></label>
					<input type="text" name="meta[_manager]" value="" class="form-control" />
				</div>
				<div class="form-group">
					<label><?php _e('Start Date'); ?></label>
					<input type="text" name="meta[_start_date]" value="" class="form-control datepicker" />
				</div>
				<div class="form-group">
					<label><?php _e('End Date'); ?></label>
					<input type="text" name="meta[_end_date]" value="" class="form-control datepicker" />
				</div>
			</div>
		</div>
		<div class="panel panel-default">
			<div class="panel-heading"><span><?php _e('Geo Data'); ?></span></div>
			<div class="panel-body">
				<div class="form-group">
					<label><?php _e('Address'); ?></label>
					<input type="text" id="paddress" name="meta[_address]" value="" class="form-control" />
				</div>
				<div class="form-group">
					<label><?php _e('Latitude'); ?></label>
					<input type="text" id="lat" name="meta[_lat]" value="" class="form-control" />
				</div>
				<div class="form-group">
					<label><?php _e('Length'); ?></label>
					<input type="text" id="lng" name="meta[_lng]" value="" class="form-control" />
				</div>
				<div id="map" style="width:100%;height:350px;"></div>
				<div>
					<script src="https://maps.googleapis.com/maps/api/js?region=es_ES&key=AIzaSyAL5RRwu9kXrgE6M5K7pLswjUIBGfqhxR0"></script>
	              	<script>
					var map;
					var geocoder 	= null;
					var marker 		= null;
					var lat = lng 	= null;
					function initMap() 
					{
						geocoder = new google.maps.Geocoder();
				        map = new google.maps.Map(document.getElementById('map'), 
						{
				        	center: {lat: -16.4956817, lng: -68.1335464},
				          	zoom: 16
				        });
				        map.setZoom(16);
						lat = parseFloat(jQuery('#lat').val());
				        lng = parseFloat(jQuery('#lng').val());
				        if( !isNaN(lat) && !isNaN(lng) )
				        {
				        	map.setCenter({lat: lat, lng: lng});
				        	marker = new google.maps.Marker({
				            	map: map,
				            	draggable: true,
				                animation: google.maps.Animation.DROP,
				            	position: {lat: lat, lng: lng}
				        	});
				        	map.setCenter({lat: lat, lng: lng});
					    }
					}
					jQuery(function()
					{	initMap();
						jQuery('#map').on('shown',function(){ google.maps.event.trigger(map, 'resize'); });
					});
				    </script>
				</div>
			</div>
		</div>
		<?php 
	}
}
new LT_ThemeLittleBoys();