<?php
/**
 * Template: Reps
 */
//sb_add_script('http://maps.googleapis.com/maps/api/js?key&v=3&language=en&region=US&ver=4.7.2', 'google-maps');
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
$res = LT_HelperContent::GetArticles(array(
	'type' 			=> 'reps',
	'rows_per_page'	=> -1
));
lt_get_header();
?>
<div id="content-wrap" class="row">
	<?php lt_get_sidebar(); ?>
	<div id="content" class="col-xs-12 col-md-9">
		<?php SB_MessagesStack::ShowMessages(); ?>
		<?php sb_show_module(); ?>
		<div id="reps-container">
			<div class="row">
				<div class="col-md-3">
					<select id="state" name="state" class="form-control">
						<option value="-1"><?php _e('-- select --', 'ps'); ?></option>
						<?php foreach(ps_get_us_states() as $index => $s): ?>
						<option value="<?php print $index; ?>"><?php print $s; ?></option>
						<?php endforeach; ?>
					</select>
				</div>
				<div class="col-md-1">
					<button type="button" id="btn-search" class="btn btn-default">
						<?php _e('Search', 'ps'); ?>
					</button>
				</div>
			</div>
			<div id="reps-data" style="">
			</div>
		</div>
	</div>
</div>
<script>
var reps = <?php print json_encode($res['articles']); ?>;
function ShowReps(state)
{
	jQuery('#reps-data').html('');
	var _reps = '';
	jQuery.each(reps, function(i, r)
	{
		if( jQuery.inArray(state.toString(), eval(r._states)) != -1 )
		{
			_reps += '<div class="rep-row">\
							<div class="col-sx-12 col-sm-4 col-md-4">' +
									r.title + '<br/>' +
									r._address +
							'</div>\
							<div class="col-sx-12 col-sm-4 col-md-4">'+
								r._city + '<br/>' +
								//r._state + '<br/>' +
								r._zip +
							'</div>\
							<div class="col-sx-12 col-sm-4 col-md-4">'+
								'<b>Phone:</b> '+r._phone + '<br/>' +
								'<b>Email:</b> ' + r._email +
							'</div>\
						</div>';
			
		}
	});
	jQuery('#reps-data').html(_reps);
}
jQuery(function()
{
	jQuery('#btn-search').click(function()
	{
		var state = parseInt(jQuery('#state').val());
		if( state == -1 )
			return false;
		ShowReps(state);
		return false;
	});
});
</script>
<?php lt_get_footer(); ?>