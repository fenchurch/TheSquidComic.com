var nav_ajax;
(function($){
	console.log("TEST");
	nav_ajax = function($whereTo){
		$.post(
			MyAjax.url,
			{
				action	:'ajax-nav',
				id	: MyAjax.id
			},
			function(r){
				console.log(r);
			}
		)
	}
})(jQuery);
