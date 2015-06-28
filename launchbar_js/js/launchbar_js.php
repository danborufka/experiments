<?php header("Content-type: text/css; charset: UTF-8"); ?>
<?php include_once 'funcs.js'; ?>

jQuery(document).ready(function($) 
{
	var origin 	= location.origin.replace(/^[a-z]+\:\/\//, '').replace(/\:[0-9]+$/, ''),
		srv 	= { local:'http://localhost:8888/experiments/launchbar_js/', online: '//tracking-isobar.com/dev/launchbar/' };

	$.webshims.setOptions('forms', { replaceUI: true });
	webshim.polyfill('es5 forms');

	if(!$('#launchbar').length)
	{
		$('<?php echo str_replace("\n", '', file_get_contents('../markup.html')); ?>').prependTo( document.body );
	}

	$("<style>").prependTo(document.head).html( "<?php echo file_get_contents('../css/launchbar.min.css'); ?>" );

	var KEYS 		= { SPACE: 32, TAB: 9, RETURN: 13, ESC: 27 },

		$launchbar 	= $('#launchbar'),
		$tabtab 	= $('#lb_tabtab'),
		$suggestions = $('#lb_suggestions'),

		opts 		= window.LAUNCHBAR ? LAUNCHBAR.options : null,
		last_loaded_cmd,

		last_cmd 	= $tabtab.val();

	window.LAUNCHBAR = { 

		load: 		function(commands)
		{
			last_loaded_cmd = commands;

			$.getScript(srv[LAUNCHBAR.options.mode] + 'commands/' + commands + '.js', function()
			{
				console.info('Loaded %cLAUNCHBAR/' + last_loaded_cmd, 'color: #ddd; background-color:#333; padding:3px;', 'from', origin);
			});
			return LAUNCHBAR;
		},

		install: 	function(setup)
		{
			$.extend(true, LAUNCHBAR, setup);

			if( setup.commands )
			{
				LAUNCHBAR.addSuggestions( Object.keys( setup.commands ) )
			}
			if( setup.shortcuts )
			{
				LAUNCHBAR.addSuggestions( Object.keys( setup.shortcuts ) );
			}

			if(setup.commands || setup.shortcuts)
			{
				console.info('Installed %cLAUNCHBAR/'+last_loaded_cmd, 'color: #ddd; background-color:#333; padding:3px;', 'from', origin);
			}
			return LAUNCHBAR;
		},

		addSuggestions: function(cmd_list)
		{
			cmd_list = cmd_list
				.filter(function(i){ return i.length > 1; }) 
				.map(function(val) 
				{
					if(val.length > 1)
					{
						var lbl = '';

						if(LAUNCHBAR.labels[val])
						{
							lbl = 'label="' + LAUNCHBAR.labels[val] + '" ';
						}
						return "<option " + lbl + "value=\"" + val + "\" />";
					}
				});

			$suggestions.html( cmd_list.join('') );
			return LAUNCHBAR;
		},

		utils:
		{
			prefill: 	function( fields )
						{
							Object.keys(fields).forEach(function(key)
							{
							 	var $el = $('#' + key);

							 	if( $el.is(':checkbox,:radio') )
							 	{
							 		$el.prop( 'checked', fields[key] ).parent().addClass('checked');
							 	}
							 	else
							 	{
							 		var val = fields[key];
							 		if(typeof val === 'object')
							 		{
							 			val = parseInt(val[0] + Math.random() * (val[1] - val[0]));
							 		}
							 		
							 		if( $el.is('select') )
							 		{
							 			$el.children().eq( val ).prop('selected', true);
							 		}
							 		else
							 		{
							 			$el.val( val );
							 		}
							 	}

							});
						}

		},
		options: 		{}
	};

	window.LAUNCHBAR.options = opts;

	$tabtab.on('blur', function()
	{
		$launchbar.fadeOut(100);
		$tabtab.val( last_cmd );
	});

	$(document).on('keyup', function(e)
	{
		var cmd, params, input;

		if($launchbar.is(':visible'))
		{
			input 	= $tabtab.val().split(/\s+/);

			cmd 	= input.shift().toLowerCase();
			params 	= input.slice(0);

			switch(e.which)
			{
				case KEYS.RETURN:
						
					if( LAUNCHBAR.commands[cmd] )
					{
						// shortcuts to other commands are allowed:
						LAUNCHBAR.commands[cmd] = apply_shortcut(LAUNCHBAR.commands[cmd], LAUNCHBAR.commands);
						// launch command
						LAUNCHBAR.commands[cmd].apply( arguments.caller, params );

						last_cmd = cmd + '';
					}
					else
					{
						alert.delay(101, 'Sorry, command not found!');
					}

				case KEYS.ESC:
					
					$tabtab.trigger('blur');

			}
			return false;
		}
		else
		{
			if(e.which === KEYS.SPACE && e.ctrlKey)
			{
				$launchbar.fadeIn(100);
				$tabtab.focus()[0].setSelectionRange(0, $tabtab[0].value.length);
				return false;
			}
		}

	})
	.on('change', function()
	{
		last_cmd = $tabtab.val();
	});

	LAUNCHBAR
		.load('default')
		.load(origin);
});