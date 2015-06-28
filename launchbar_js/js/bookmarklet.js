var srv  = {local:'http://localhost:8888/experiments/launchbar_js/', online: 'http://pjure-isobar.com/experiments/launchbar/'};

window.LAUNCHBAR = { options: { mode: 'online' } };

function init_bookmarklet() 
{
	getScript('//cdnjs.cloudflare.com/ajax/libs/webshim/1.15.7/dev/polyfiller.js', function() 
	{
		getScript(srv[mode] + 'js/launchbar_js.php', function(){});
	});
};

function getScript(url, success) 
{
	var script = document.createElement('script');
	script.src = url;

	var head = document.getElementsByTagName('head')[0],
		done = false;

	// Attach handlers for all browsers
	script.onload = script.onreadystatechange = function() 
	{
		if (!done && (!this.readyState || this.readyState == 'loaded' || this.readyState == 'complete')) 
		{
			done = true;
			
			// callback function provided as param
			success();
			
			script.onload = script.onreadystatechange = null;
			head.removeChild(script);
			
		}
		return false;
	};
	
	head.appendChild(script);
};

// Only do anything if jQuery isn't defined
if(typeof jQuery == 'undefined') 
{
	if(typeof $ == 'function') 
	{
		// warning, global var
		thisPageUsingOtherJSLibrary = true;
	}
	getScript('//code.jquery.com/jquery-latest.js', function() 
	{
		if (typeof jQuery=='undefined') 
		{
			// Super failsafe - still somehow failed...
			console.error('jQuery could\'nt be loaded');
		} 
		else 
		{
			// jQuery loaded! Make sure to use .noConflict just in case
			jQuery.noConflict();
			init_bookmarklet();
		}
	});

} 
else 
{ // jQuery was already loaded
	init_bookmarklet();
};