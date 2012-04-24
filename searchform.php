<form action="<?php echo home_url( '/' ); ?>" method="get" id="searchform">
	<label for='s'>Search</label>
	<div>
		<div><p><input type="text" name="s" id="s" /></p>
		<input type='submit' value='Search'/></div>
	</div>
</form>
<script>
(function($){
	var toggleSearch = function(e){
		var toggler = search.siblings("div").find(">div");
		var toggleOn = {
			attr:{width:"toggle", opacity:1},
			complete:function(){
				search.addClass("show");
				$("#"+search.attr("for")).focusout(toggleSearch);
			}
		};
		var toggleOff = {
			attr:{width:"1px", opacity:0},
			complete:function(){
				search.removeClass("show");
				toggler.attr("style","");
				$("#"+search.attr("for")).unbind("focusout", toggleSearch);
			}
		};
		if(search.hasClass("show"))
			toggle = toggleOff;
		else
			toggle = toggleOn;
		toggler.animate(toggle.attr,toggle.complete);
	//	$("#"+search.attr("for")).unbind("focusout", toggleSearch).focusout(toggleSearch);
	}
	var search = $("#searchform label").click(toggleSearch);
})(jQuery);
</script>