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
		//##get the input height
		let height = the_input.get(0).offsetHeight;
		jQuery(the_input).parent().css({height: height + 'px'});
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
		if( !url )
			return false;
		
		var endpoint = url + '&completion=1&keyword=' + the_input.val();
		if( the_input.get(0).dataset.query_data )
		{
			endpoint += '&' + the_input.get(0).dataset.query_data;
		}
		if( options.getQueryData )
		{
			endpoint += options.getQueryData();
		}
		var loading_gif = _options.loading_gif ? _options.loading_gif : '/images/spin.gif';
		the_input.css('background', 'url('+loading_gif+') no-repeat center right');
		jQuery.get(endpoint, params, function(res)
		{
			the_input.css('background', '');
			$this.suggestions_list.html('');
			if( options.AppendItemsCallback )
			{
				options.AppendItemsCallback($this.suggestions_list, res, $this);
				$this.suggestions_list.css('display', 'block');
			}
			else
			{
				if( typeof res != 'object' )
				{
					window.console && console.log('The results are not an object');
					return false;
				}
				if( res.status == 'ok' )
				{
					jQuery.each((res.results || res.items), function(i, obj)
					{
						$this.AppendSuggestion(i, obj);
					});
					$this.suggestions_list.css('display', 'block');
				}
			}
			
		});
	};
	this.AppendSuggestion = function(i, obj)
	{
		var li 			= document.createElement('li');
		//##check for render callback
		if( options.renderSuggestionCallback )
		{
			var element = options.renderSuggestionCallback(i, obj, $this);
			li.appendChild(element);
		}
		else
		{
			var a 			= document.createElement('a');
			a.className 	= 'the_suggestion';
			a.href			= 'javascript:;';
			a.style.display = 'block';
			a.innerHTML		= obj.label || obj.name || '';
			a.data 			= obj;	
			//##assign dataset
			for(var key in obj)
				a.dataset[key] = obj[key];
			if( obj.data )
				for(var key in obj.data)
					a.dataset[key] = obj.data[key];
			
			a.addEventListener('click', $this.OnSuggestionSelected);
			li.appendChild(a);
		}
		
		$this.suggestions_list.append(li);
	}
	this.OnSuggestionSelected = function(e)
	{
		the_input.val(this.dataset.name || this.dataset.label || '');
		if( options.setValue )
			the_input.val( options.setValue(the_input.get(0), e.currentTarget) );
		//##create and dispatch the event
		var evt = new CustomEvent('sb_completion_on_selected', {detail:{item_selected: this}});
		the_input.get(0).dispatchEvent(evt);
		if( the_input.get(0).dataset.onselected )
		{
			var func = eval(the_input.get(0).dataset.onselected);
			if( typeof(func) == 'function' )
				func(the_input, e.currentTarget);
		}
		$this.suggestions_list.css('display', 'none');
		the_input.focus();
		if( options.callback )
		{
			options.callback(e.currentTarget);
		}
		return false;
	};
	this.Build = function()
	{
		the_input.wrap('<div class="sb-completion"></div>');
		the_input.attr('autocomplete', 'off');
		$this.suggestions_list = jQuery('<ul class="sb-suggestions suggestions" style="list-style:none;"></ul>');
		$this.suggestions_list.css({position:'absolute', 
									width: '100%',
									'min-width': '50%',
									'max-height': '200px',
									'z-index': 100,
									background: '#fff',
									overflow: 'auto',
									top: '100%',
									left: 0,
									border: '1px solid #ececec',
									display: 'none',
									margin: 0,
									padding: 0
		});
		jQuery(the_input).parent().css({'position': 'relative'});
		jQuery(the_input).parent().append($this.suggestions_list);
	};
	this.SetEvents = function()
	{
		//##set the main event
		jQuery(the_input).keyup(OnInputKeyUp);
		//##set event on suggestion clicked
		//jQuery(document).on('click', '.the_suggestion', $this.OnSuggestionSelected);
		//##set events to move cursor into suggestions list
		$this.suggestions_list.keydown(function(e)
		{
			
			//console.log(e.keyCode);
			if( e.keyCode == 38 )
			{
				e.preventDefault();
				e.stopImmediatePropagation();
				e.stopPropagation();
				jQuery(this).find("li a:focus").parent().prev().find('a:first').focus();
				return false;
			}
			if( e.keyCode == 40 )
			{
				e.preventDefault();
				e.stopImmediatePropagation();
				e.stopPropagation();
				jQuery(this).find("li a:focus").parent().next().find('a:first').focus();
				return false;
			}
			return true;
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
