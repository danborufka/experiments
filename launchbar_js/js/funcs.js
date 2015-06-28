function apply_shortcut(shortcut, list)
{
	if(list[shortcut])
	{
		shortcut = list[shortcut];
	}
	return shortcut;
}

Function.prototype.delay = function(dly)
{
	var self 	= this,
		args 	= arguments;

	setTimeout(function()
	{
		console.log(self, args);
		self.apply(window, Array.prototype.slice.call(args, 1));	
	}, dly);	
}