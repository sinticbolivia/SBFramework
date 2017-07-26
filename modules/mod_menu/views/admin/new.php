<?php
?>
<div class="wrap">
	<h2>
		<?php _e('New Menu', 'menu'); ?>
		<span class="pull-right">
			<a href="<?php print SB_Route::_('index.php?mod=menu')?>" class="btn btn-danger"><?php _e('Cancel', 'menu'); ?></a>
			<a href="javascript:;" class="btn btn-success" onclick="jQuery('#form-menu').submit();"><?php _e('Save', 'menu'); ?></a>
		</span>
	</h2>
	<form id="form-menu" action="" method="post">
		<input type="hidden" name="mod" value="menu" />
		<input type="hidden" name="task" value="save" />
		<?php if( isset($menu) ): ?>
		<input type="hidden" name="key" value="<?php print $menu->key; ?>" />
		<?php endif; ?>
		<div class="form-group">
			<label><?php _e('Name', 'menu'); ?></label>
			<input type="text" name="name" value="<?php print isset($menu) ? $menu->name : ''; ?>" class="form-control" />
		</div>
		<div class="form-group">
			<label><?php _e('Language', 'menu'); ?></label>
			<select name="lang" class="form-control">
				<?php foreach(SB_Factory::getApplication()->GetLanguages() as $code => $lang): ?>
				<option value="<?php print $code; ?>" <?php print isset($menu) && $menu->lang == $code ? 'selected' : ''; ?>>
					<?php print $lang; ?></option>
				<?php endforeach; ?>
			</select>
		</div>
	</form>
	<?php if( isset($menu) ): ?>
	<h3><?php _e('Menu Items', 'menu'); ?></h3>
	<div class="row">
		<div class="col-md-6">
			<div class="panel panel-default">
				<div class="panel-heading"><?php _e('Pages', 'menu'); ?></div>
				<div class="panel-body" style="max-height:350px;overflow-x:none;overflow-y:auto;">
					<ul class="list-group">
					<?php foreach($pages as $p): ?>
					<li class="row list-group-item" 
						data-id="<?php print $p->content_id; ?>"
						data-title="<?php print $p->title; ?>"
						data-slug="<?php print $p->slug; ?>"
						data-link="<?php print 'index.php?mod=content&view=article&id='.$p->content_id.'&slug='.$p->slug; ?>"
						data-type="page">
						<div class="col-md-10"><?php print $p->title; ?></div>
						<div class="col-md-2">
							<a href="javascript:;" class="btn btn-default btn-add-page btn-xs">
								<?php _e('Add', 'menu'); ?>
							</a>
						</div>
					</li>
					<?php endforeach; ?>
					</ul>
				</div>
			</div>
			<div class="panel panel-default">
				<div class="panel-heading"><?php _e('Sections', 'menu'); ?></div>
				<div class="panel-body" style="max-height:350px;overflow-x:none;overflow-y:auto;">
					<ul class="list-group">
					<?php foreach($this->sections as $s): ?>
					<li class="row list-group-item" 
						data-id="<?php print $s->section_id; ?>"
						data-title="<?php print $s->name; ?>"
						data-slug="<?php print $s->slug; ?>"
						data-link="<?php print 'index.php?mod=content&view=section&id='.$s->section_id.'&slug='.$s->slug; ?>"
						data-type="section">
						<div class="col-md-10"><?php print $s->name; ?></div>
						<div class="col-md-2">
							<a href="javascript:;" class="btn btn-default btn-add-page btn-xs">
								<?php _e('Add', 'menu'); ?></a></div>
					</li>
					<?php endforeach; ?>
					</ul>
				</div>
			</div>
			<div class="panel panel-default">
				<div class="panel-heading"><?php _e('Posts', 'menu'); ?></div>
				<div class="panel-body" style="max-height:350px;overflow-x:none;overflow-y:auto;">
					<ul class="list-group">
					<?php foreach($this->posts as $p): ?>
					<li class="row list-group-item" 
						data-id="<?php print $p->content_id; ?>"
						data-title="<?php print $p->title; ?>"
						data-slug="<?php print $p->slug; ?>"
						data-link="<?php print 'index.php?mod=content&view=post&id='.$p->content_id . '&slug=' . $p->slug; ?>"
						data-type="post">
						<div class="col-md-10"><?php print $p->title; ?></div>
						<div class="col-md-2">
							<a href="javascript:;" class="btn btn-default btn-add-page btn-xs">
								<?php _e('Add', 'menu'); ?></a></div>
					</li>
					<?php endforeach; ?>
					</ul>
				</div>
			</div>
			<div class="panel panel-default">
				<div class="panel-heading"><?php _e('Categories', 'menu'); ?></div>
				<div class="panel-body" style="max-height:350px;overflow-x:none;overflow-y:auto;">
					<ul class="list-group">
					<?php foreach($this->categories as $c): ?>
					<li class="row list-group-item" 
						data-id="<?php print $c->category_id; ?>"
						data-title="<?php print $c->name; ?>"
						data-slug="<?php print $c->slug; ?>"
						data-link="<?php print 'index.php?mod=content&view=category&id='.$c->category_id . '&slug='.$c->slug; ?>"
						data-type="category">
						<div class="col-md-10"><?php print $c->name; ?></div>
						<div class="col-md-2">
							<a href="javascript:;" class="btn btn-default btn-add-page btn-xs">
								<?php _e('Add', 'menu'); ?></a></div>
					</li>
					<?php endforeach; ?>
					</ul>
				</div>
			</div>
			<div class="panel panel-default">
				<div class="panel-heading"><?php _e('Custom Link', 'menu'); ?></div>
				<div class="panel-body">
					<ul class="list-group">
						<li class="row list-group-item" 
							data-id="0"
							data-title="<?php _e('Custom Link', 'menu'); ?>"
							data-slug=""
							data-link=""
							data-type="custom">
							<div class="col-md-10"><?php _e('Add Custom Menu Item', 'menu'); ?></div>
							<div class="col-md-2">
								<a href="javascript:;" class="btn btn-default btn-add-page btn-xs">
									<?php _e('Add', 'menu'); ?></a></div>
						</li>
					</ul>
				</div>
			</div>
			<?php SB_Module::do_action('menu_panel_items', isset($menu) ? $menu : null); ?>
		</div>
		<div class="col-md-6">
			<div class="panel panel-default">
				<div class="panel-heading"><?php _e('Menu Items', 'menu'); ?></div>
				<div class="panel-body form-group-sm">
					<ul id="menu-items">
						<?php if( isset($menu->items) ): foreach((array)$menu->items as $item): ?>
						<li class="menu-item" data-id="<?php print $item->id; ?>" 
							data-title="<?php print $item->title; ?>" 
							data-link="<?php print @$item->link; ?>" 
							data-slug="<?php print $item->slug; ?>"
							data-type="<?php print @$item->type; ?>"
							data-css_id="<?php print @$item->css_id; ?>"
							data-css_class="<?php print @$item->css_class; ?>">
							<a href="javascript:;" class="btn btn-default"><?php print $item->title; ?></a>
							<div class="data">
								<h4><?php _e('Menu Data', 'menu'); ?></h4>
								<div class="row">
									<div class="col-sm-12 col-md-6">
										<div class="form-group">
											<input type="text" name="title" placeholder="<?php _e('Title', 'menu'); ?>" value="<?php print $item->title; ?>" class="form-control menu-item-title" draggable="false" />
										</div>
										<div class="form-group">
											<input type="text" name="link" placeholder="<?php _e('Link', 'menu'); ?>" value="<?php print @$item->link; ?>" class="form-control menu-item-link" draggable="false" />
										</div>
									</div>
									<div class="col-sm-12 col-md-6">
										<div class="form-group">
											<input type="text" name="css_id" placeholder="<?php _e('Css Id', 'menu'); ?>" value="<?php print @$item->css_id; ?>" class="form-control menu-item-css-id" draggable="false" />
										</div>
										<div class="form-group">
											<input type="text" name="css_class" placeholder="<?php _e('Css Class', 'menu'); ?>" value="<?php print @$item->css_class; ?>" class="form-control menu-item-css-class" draggable="false" />
										</div>
									</div>
								</div>
								<div class="form-group">
									<a href="javascript:;" class="btn btn-default btn-sm btn-remove-item"><?php _e('Remove', 'menu'); ?></a>
								</div>
							</div>
							<ul class="sub-menu">
								<?php if( isset($item->items) ): foreach($item->items as $ii): ?>
								<li class="menu-item" data-id="<?php print $ii->id; ?>" 
									data-title="<?php print $ii->title; ?>" 
									data-link="<?php print @$ii->link; ?>" 
									data-slug="<?php print $ii->slug; ?>"
									data-type="<?php print @$ii->type; ?>"
									data-css_id="<?php print $ii->css_id; ?>"
									data-css_class="<?php print @$ii->css_class; ?>">
									<a href="javascript:;" class="btn btn-default"><?php print $ii->title; ?></a>
									<div class="data">
										<h4><?php _e('Menu Data', 'menu'); ?></h4>
										<div class="row">
											<div class="col-sm-12 col-md-6">
												<div class="form-group">
													<input type="text" name="title" placeholder="<?php _e('Title', 'menu'); ?>" value="<?php print $ii->title; ?>" class="form-control menu-item-title" draggable="false" />
												</div>
												<div class="form-group">
													<input type="text" name="link" placeholder="<?php _e('Link', 'menu'); ?>" value="<?php print @$ii->link; ?>" class="form-control menu-item-link" draggable="false" />
												</div>
											</div>
											<div class="col-sm-12 col-md-6">
												<div class="form-group">
													<input type="text" name="css_id" placeholder="<?php _e('Css Id', 'menu'); ?>" value="<?php print @$ii->css_id; ?>" class="form-control menu-item-css-id" draggable="false" />
												</div>
												<div class="form-group">
													<input type="text" name="css_class" placeholder="<?php _e('Css Class', 'menu'); ?>" value="<?php print @$ii->css_class; ?>" class="form-control menu-item-css-class" draggable="false" />
												</div>
											</div>
										</div>
										<div class="form-group">
											<a href="javascript:;" class="btn btn-default btn-sm btn-remove-item"><?php _e('Remove', 'menu'); ?></a>
										</div>
									</div>
									<ul class="sub-menu">
										<?php if( isset($ii->items) ): foreach($ii->items as $iii): ?>
										<li class="menu-item" data-id="<?php print $iii->id; ?>" 
											data-title="<?php print $iii->title; ?>" 
											data-link="<?php print @$iii->link; ?>" 
											data-slug="<?php print $iii->slug; ?>"
											data-type="<?php print @$iii->type; ?>"
											data-css_id="<?php print $iii->css_id; ?>"
											data-css_class="<?php print @$iii->css_class; ?>">
											<a href="javascript:;" class="btn btn-default"><?php print $iii->title; ?></a>
											<div class="data">
												<h4><?php _e('Menu Data', 'menu'); ?></h4>
												<div class="row">
													<div class="col-sm-12 col-md-6">
														<div class="form-group">
															<input type="text" name="title" placeholder="<?php _e('Title', 'menu'); ?>" value="<?php print $iii->title; ?>" class="form-control menu-item-title" draggable="false" />
														</div>
														<div class="form-group">
															<input type="text" name="link" placeholder="<?php _e('Link', 'menu'); ?>" value="<?php print @$iii->link; ?>" class="form-control menu-item-link" draggable="false" />
														</div>
													</div>
													<div class="col-sm-12 col-md-6">
														<div class="form-group">
															<input type="text" name="css_id" placeholder="<?php _e('Css Id', 'menu'); ?>" value="<?php print @$iii->css_id; ?>" class="form-control menu-item-css-id" draggable="false" />
														</div>
														<div class="form-group">
															<input type="text" name="css_class" placeholder="<?php _e('Css Class', 'menu'); ?>" value="<?php print @$iii->css_class; ?>" class="form-control menu-item-css-class" draggable="false" />
														</div>
													</div>
												</div>
												<div class="form-group">
													<a href="javascript:;" class="btn btn-default btn-sm btn-remove-item"><?php _e('Remove', 'menu'); ?></a>
												</div>
											</div>
											<ul class="sub-menu">
											</ul>
										</li>
										<?php endforeach; endif; ?>
									</ul>
								</li>
								<?php endforeach; endif; ?>
							</ul>
						</li>
						<?php endforeach; endif;?>
					</ul>
				</div>
			</div>
		</div>
	</div>
	<?php endif; ?>
	<style>
	#menu-items .menu-item{margin:0 0 10px 0;}
	#menu-items .menu-item > a{display:block;text-align:left;}
	#menu-items .menu-item .data{display:none;}
	#menu-items .menu-item .sub-menu{margin:10px 0 10px 20px;padding:5px;}
	</style>
	<script src="<?php print BASEURL; ?>/js/Sortable.js"></script>
	<script>
	function menu_add_page(data)
	{
		var order = jQuery('#menu-items > li').length;
		var li = '<li class="menu-item">'+
					'<a href="javascript:;" class="btn btn-default">'+data.title +'</a>'+
					'<div class="data">'+
						'<h4>Menu Data</h4>'+
						'<div class="row">'+
							'<div class="col-sm-12 col-md-6">'+
								'<div class="form-group">'+
									'<input type="text" name="title" value="'+data.title+'" placeholder="<?php _e('Title', 'menu'); ?>" class="form-control menu-item-title" draggable="false" />'+
								'</div>'+
							'</div>'+
							'<div class="col-sm-12 col-md-6">'+
								'<div class="form-group">'+
									'<input type="text" name="css_id" value="" placeholder="<?php _e('Css Id', 'menu'); ?>" class="form-control menu-item-css-id" draggable="false" />'+
								'</div>'+
								'<div class="form-group">'+
									'<input type="text" name="css_class" value="" placeholder="<?php _e('Css Class', 'menu'); ?>" class="form-control menu-item-css-id" draggable="false" />'+
								'</div>'+
							'</div>'+
						'</div>'+
						'<div class="form-group">' +
							'<a href="javascript:;" class="btn btn-default btn-sm btn-remove-item"><?php _e('Remove', 'menu'); ?></a>'+
						'</div>' +
					'</div>'+
					'<ul class="sub-menu"></ul>'+
				'</li>';
		li = jQuery(li).get(0);
		li.dataset.title 	= data.title;
		li.dataset.id		= data.id;
		li.dataset.link		= data.link;
		li.dataset.slug		= data.slug;
		li.dataset.type		= data.type;
		li.dataset.order	= order;
		var submenu = jQuery(li).find('.sub-menu').get(0);
		Sortable.create(submenu, {group:'menu'});
		jQuery('#menu-items').append(li);
	}
	jQuery(function()
	{
		window.sortable = Sortable.create(document.getElementById('menu-items'), 
		{
			group: 'menu', 
			draggable: ".menu-item",
			dataIdAttr: 'order',
			store: {
		        /**
		         * Get the order of elements. Called once during initialization.
		         * @param   {Sortable}  sortable
		         * @returns {Array}
		         */
		        get: function (sortable) {
		            var order = localStorage.getItem(sortable.options.group);
		            console.log(order);
		            return order ? order.split('|') : [];
		        },
		        set: function (sortable) 
		        {
		            var order = sortable.toArray();
		            console.log(order);
		            localStorage.setItem(sortable.options.group, order.join('|'));
		        }
			}
		});
		var sub_menus = document.querySelectorAll('#menu-items .sub-menu');
		for(var i in sub_menus)
		{
			try
			{
				Sortable.create(sub_menus[i], {group:'menu'});
			}
			catch(e){}
		}
		
		jQuery('.btn-add-page').click(function()
		{
			var li = jQuery(this).parents('.row:first').get(0);
			menu_add_page(li.dataset);
		});
		jQuery(document).on('click', '.menu-item > a', function(e)
		{
			jQuery(this).parent().find('> .data').toggle();
			
			return false;
		});
		jQuery(document).on('keyup', '.menu-item-title', function()
		{
			var menu_item = jQuery(this).parents('.menu-item:first');
			menu_item.get(0).dataset.title = this.value;
			menu_item.find('a:first').html(this.value);
		});
		jQuery(document).on('keyup', '.menu-item-link', function()
		{
			var menu_item = jQuery(this).parents('.menu-item:first');
			menu_item.get(0).dataset.link = this.value;
		});
		jQuery(document).on('keyup', '.menu-item-css-id', function()
		{
			var menu_item = jQuery(this).parents('.menu-item:first');
			menu_item.get(0).dataset.css_id = this.value;
		});
		jQuery(document).on('keyup', '.menu-item-css-class', function()
		{
			var menu_item = jQuery(this).parents('.menu-item:first');
			menu_item.get(0).dataset.css_class = this.value;
		});
		jQuery(document).on('click', '.btn-remove-item', function()
		{
			jQuery(this).parents('.menu-item:first').remove();
		});
		jQuery('#form-menu').submit(function()
		{
			var form = jQuery(this);
			var menu = jQuery('#menu-items');
			var items = menu.find('> .menu-item');
			
			jQuery(items).each(function(i, item)
			{
				var input = '<input type="hidden" name="menu_items['+i+'][id]" value="'+item.dataset.id+'" />'+
							'<input type="hidden" name="menu_items['+i+'][title]" value="'+item.dataset.title+'" />'+
							'<input type="hidden" name="menu_items['+i+'][slug]" value="'+item.dataset.slug+'" />' +
							'<input type="hidden" name="menu_items['+i+'][link]" value="'+item.dataset.link+'" />' +
							'<input type="hidden" name="menu_items['+i+'][type]" value="'+item.dataset.type+'" />'+
							'<input type="hidden" name="menu_items['+i+'][css_class]" value="'+item.dataset.css_class+'" />' +
							'<input type="hidden" name="menu_items['+i+'][css_id]" value="'+item.dataset.css_id+'" />';
				jQuery(item).find('> ul > li').each(function(ii, iitem)
				{
					input += '<input type="hidden" name="menu_items['+i+'][items]['+ii+'][id]" value="'+iitem.dataset.id+'" />';
					input += '<input type="hidden" name="menu_items['+i+'][items]['+ii+'][title]" value="'+iitem.dataset.title+'" />';
					input += '<input type="hidden" name="menu_items['+i+'][items]['+ii+'][slug]" value="'+iitem.dataset.slug+'" />';
					input += '<input type="hidden" name="menu_items['+i+'][items]['+ii+'][link]" value="'+iitem.dataset.link+'" />';
					input += '<input type="hidden" name="menu_items['+i+'][items]['+ii+'][type]" value="'+iitem.dataset.type+'" />';
					input += '<input type="hidden" name="menu_items['+i+'][items]['+ii+'][css_class]" value="'+iitem.dataset.css_class+'" />';
					input += '<input type="hidden" name="menu_items['+i+'][items]['+ii+'][css_id]" value="'+iitem.dataset.css_id+'" />';
					jQuery(iitem).find('> ul > li').each(function(iii, iiitem)
					{
						input += '<input type="hidden" name="menu_items['+i+'][items]['+ii+'][items]['+iii+'][id]" value="'+iiitem.dataset.id+'" />';
						input += '<input type="hidden" name="menu_items['+i+'][items]['+ii+'][items]['+iii+'][title]" value="'+iiitem.dataset.title+'" />';
						input += '<input type="hidden" name="menu_items['+i+'][items]['+ii+'][items]['+iii+'][slug]" value="'+iiitem.dataset.slug+'" />';
						input += '<input type="hidden" name="menu_items['+i+'][items]['+ii+'][items]['+iii+'][link]" value="'+iiitem.dataset.link+'" />';
						input += '<input type="hidden" name="menu_items['+i+'][items]['+ii+'][items]['+iii+'][type]" value="'+iiitem.dataset.type+'" />';
						input += '<input type="hidden" name="menu_items['+i+'][items]['+ii+'][items]['+iii+'][css_class]" value="'+iiitem.dataset.css_class+'" />';
						input += '<input type="hidden" name="menu_items['+i+'][items]['+ii+'][items]['+iii+'][css_id]" value="'+iiitem.dataset.css_id+'" />';
					});
				});
				form.append(input);	
			});
			return true;
		});
	});
	</script>
</div>