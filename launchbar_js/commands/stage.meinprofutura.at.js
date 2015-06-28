LAUNCHBAR.install({ 
	commands: 	{ 	prefill: function()
					{
						var fields = { 	
						 		'acf-field-vorname': 				'Werner', 
						 		'acf-field-nachname': 				'Testfried', 
						 		'acf-field-anrede-Herr': 			true, 
						 		'acf-field-agbs-1': 				true,
						 		'geburtsdatum_tag': 				[1,30],
						 		'geburtsdatum_monat': 				[1,12],
						 		'geburtsdatum_jahr': 				[1999,2015],
						 		'acf-field-e-mail': 				'd.borufka' + parseInt(Math.random() * 9999) + '@pjureisobar.com',
						 		'acf-field-passwort': 				'asdasdasd',
						 		'acf-field-passwort_wiederholen': 	'asdasdasd'
						};

						LAUNCHBAR.utils.prefill( fields );
					},
					p: 'prefill'
				},
	labels: 	{	'prefill': 	'Prefill form',
					'p': 		'prefill'
				}			
});