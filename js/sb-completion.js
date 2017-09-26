/**
 * Ajax completion
 * @author Sintic Bolivia
 * @developer Juan Marcelo Aviles Paco
 */

function SBCompletion(options)
{
	var timeout 			= null;
	var the_input			= jQuery(options.input);
	var url					= options.url;
	this.suggestions_list 	= null;
	var _options			= options;
	var $this 					= this;
	
	function OnInputKeyUp(e)
	{
		
		if( timeout )
			clearTimeout(timeout);
		//##check if ESC is pressed
		if( e.keyCode == 27 )
		{
			$this.suggestions_list.css('display', 'none');
			return false;
		}
		//##check if enter or backspace is pressed
		if( e.keyCode == 13 )
		{
			e.preventDefault();
			e.stopPropagation();
			return false;
		}
		if( e.keyCode == 8 && the_input.val().length <= 0 )
		{
			e.preventDefault();
			e.stopPropagation();
			return false;
		}
		if( the_input.val().length <= 0 )
		{
			return false;
		}
		//##check for cursor codes
		if(e.keyCode >= 37 && e.keyCode <= 40 )
		{
			if( e.keyCode == 40 )
			{
				$this.suggestions_list.find('li:first a').focus();
			}
			if( e.keyCode == 38 )
			{
				$this.suggestions_list.find('li:last a').focus();
			}
			return true;
		}
		
		$this.suggestions_list.css('display', 'none');
		timeout = setTimeout($this.GetSuggestions, 400);
	}
	this.GetSuggestions = function()
	{
		var params = null;
		var endpoint = url + '&completion=1&keyword=' + the_input.val();
		if( the_input.get(0).dataset.query_data )
		{
			endpoint += '&' + the_input.get(0).dataset.query_data;
		}
		var loading_gif = _options.loading_gif ? _options.loading_gif : '';
		the_input.css('background', 'url('+loading_gif+') no-repeat center right');
		jQuery.post(endpoint, params, function(res)
		{
			the_input.css('background', '');
			$this.suggestions_list.html('');
			if( typeof res != 'object' )
			{
				window.console && console.log('The results are not an object');
				return false;
			}
			if( res.status == 'ok' )
			{
				jQuery.each(res.results, function(i, obj)
				{
					var a = jQuery('<a class="the_suggestion" href="javascript:;" data-id="'+obj.id+'" data-full_name="'+obj.name+'" style="display:block;">'
								+obj.label+
							'</a>');
					a.get(0).data = obj;
					a.data('obj', obj);
					a.click($this.OnSuggestionSelected)
					var li = jQuery('<li style="display:block;width:100%;"></li>');
					li.append(a);
					$this.suggestions_list.append(li);
				});
				$this.suggestions_list.css('display', 'block');
			}
		});
	};
	this.OnSuggestionSelected = function(e)
	{
		if( options.callback )
		{
			options.callback(e.currentTarget);
		}
		the_input.val(options.setValue ? options.setValue(the_input.get(0), e.currentTarget) : e.target.innerText);
		//##create and dispatch the event
		var evt = new CustomEvent('sb_completion_on_selected', {detail:{item_selected: this}});
		the_input.get(0).dispatchEvent(evt);
		$this.suggestions_list.css('display', 'none');
		the_input.focus();
		return false;
	};
	this.Build = function()
	{
		the_input.attr('autocomplete', 'off');
		$this.suggestions_list = jQuery('<ul class="sb-suggestions"></ul>');
		$this.suggestions_list.css({position:'absolute', 
									width: '100%',
									'min-width': '50%',
									'max-height': '200px',
									'z-index': 100,
									background: '#fff',
									overflow: 'auto',
									top: '100%',
									left: 0,
									border: '1px solid #ececec'
		});
		jQuery(the_input).parent().css('position', 'relative');
		jQuery(the_input).parent().append($this.suggestions_list);
	};
	this.SetEvents = function()
	{
		//##set the main event
		jQuery(the_input).keyup(OnInputKeyUp);
		//##set event on suggestion clicked
		//jQuery(document).on('click', '.the_suggestion', $this.OnSuggestionSelected);
		//##set events to move cursor into suggestions list
		$this.suggestions_list.keyup(function(e)
		{
			e.preventDefault();
			e.stopImmediatePropagation();
			e.stopPropagation();
			//console.log(e.keyCode);
			if( e.keyCode == 38 )
			{
				jQuery(this).find("li a:focus").parent().prev().find('a:first').focus();
			}
			if( e.keyCode == 40 )
			{
				jQuery(this).find("li a:focus").parent().next().find('a:first').focus();
			}
			return false;
		});
		jQuery(the_input).keydown(function(e)
		{
			if( e.keyCode == 13 )
			{
				e.preventDefault();
				e.stopPropagation();
				return false;
			}
		});
	};
	this.Build();
	this.SetEvents();
}