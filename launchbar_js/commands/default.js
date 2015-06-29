var cmds_default = { 
	commands: 	{ 	open: function(file, _blank)
					{
						 if(!file.match(/^[on] .+/))
						 {
						 	file = (_blank ? 'n' : 'o') + ' ' + file;
						 }

						file = apply_shortcut( file.toLowerCase(), cmds_default.shortcuts );

						if(file.match(/^[on] .+/))
						{
							file = file.slice(2);
						}

						if(!file.match(/^[a-z]+\:\/\//))
						{
							file = 'http://' + file;
						}

						if(file)
						{
							if(_blank)
							{
								window.open(file);
							}
							else
							{
								location.href = file;
							}
						}
						else alert('Please supply a URL!');
					},
					new: function(file)
					{
						LAUNCHBAR.commands.open(file, true);
					},

					o: 'open',
					n: 'new'
				},
	labels: 	{	'open': 	'Open link in same window',
					'new': 		'Open link in new tab',
					'ftp': 		'Open FTP in new tab',

					/* shortcuts: */
					'o': 'open', 'n': 'new',
					'o mi': 	'Goto milupa [PRODUCTION]',
					'o lmi': 	'Goto milupa [LOCAL]',
					'n mi': 	'New Tab: milupa [PRODUCTION]',
					'n lmi': 	'New Tab: milupa [LOCAL]'
				},
	shortcuts:  { 	'o mi': 	'http://www.milupa.at',
					'o lmi': 	'http://localhost:8888/mylupa.at/',
					'n mi': 	'http://www.milupa.at',
					'n lmi': 	'http://localhost:8888/mylupa.at/'
				}			
}
LAUNCHBAR.install( cmds_default );