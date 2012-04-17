var nav_ajax;
(function($){
	nav_ajax = function(id, comp){
		$.post(
			MyAjax.url,
			{
				action	:'ajax-nav',
				id	:id
			},
			function(r){
				comp(r);
			}
		)
	}
})(jQuery);
