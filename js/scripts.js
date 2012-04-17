(function($){
var plugin = "pageLoader",
//Plugin Vars
$$,o,data,loadingData,
//Public Methods
methods = {
	init	:function(options){
		console.log("init", options);
		//Set Options
		o = _m.options(options);
		//Make methods public
		$.extend(this, methods);
		if(!data){
			data = {};
			_m.data();
		}
		
		return this.each(function(){
			var $this = $(this);
		});
	},
	go	:function(page){
		console.log(page);
		//Check if we are loading Data
		if(!loadingData)
		_m.animate.scroll(page, function(){
			_m.animate.pageFlip(page, function(){});
			o.id = data[page].ID;
			_m.data();

		})
	//	if(!_m.animate.scroll() || page== "older" || page == "oldest")
	//		o.id = data[page].ID;
		return;
	}
},
//Private Methods
_m = {
	options	:function(options){
		return o = $.extend(true, {
			url:"",
			id:0,
			action:"ajax-nav",
			duration:500,
			easing:"easeInOutQuad",
			cur:"cur",
			load:"load",		
			controls:"overlay"
		}, options);
	},
	//
	animate:{
		loading	:function(){
			//Loading Element
			//Toggled
			if(!o.loading)
				o.loading = $$.
				append("<div />", {class:"loading", html:"Loading..."}).
				hide().
				animate({opacity:1}, slow);
			else
				o.loading.
				animate({opacity:0},{
					duration:slow, 
					complete:function(){
						$(this).remove(); 
						o.loading=null
						}
					}
				);
		},
		pageFlip:function(id, complete){
			//Page Flipping Animation
			var dir = (id == "newer" || id == "newest") ? true : false,
			start = dir ? 1: -1,
			end = 100,
			unit = "%",
			cur = $$.find("."+o.cur),
			loader = _m.loader(
				data[id].webcomic_files.full[0].url,
				function(){
					loader.animate(
					{left:0+unit},
					{
						duration:o.duration,
						easing:o.easing,
						step:function(now,fx){
							//Move to the Opposite Position
							cur.css({left:(fx.pos*(-start)*end)+unit})
						},
						complete:function(){
							console.log("PageFlip Completed, running function");
							cur.remove();
							loader.	removeAttr("style").
								removeClass(o.load).
								addClass(o.cur);
							if(complete) 
							if(typeof complete == "function")
								complete(id);
						}
					});
				}
			).css({left:(start*end)+unit});
			
			console.log("file:", data[id].webcomic_files.full[0]);

			$$.append(loader);
		},
		scroll:function(id, complete){
			console.log($$.height());
			_m.toggleControls(false);
			var animate = {
				duration:o.duration,
				easing:o.easing
			},
			$w = $(window),
			dir = id == "newer" || dir == "newest" ? true:false,
			p0 = $$.offset().top,
			p1 = p0 + $$.height(),
			max = p1 - $w.height();
			//
			p = $w.scrollTop() + $w.height();
			load = false;
			if( ! (p+1 < p1 && dir)){
				//If back or already scrolled past
				//Load
				load = true;
				animate.complete = function(){
					console.log("Scroll completed, running complete function");
					complete()
				}
			}else{
				//Otherwise scroll to next height
				//if next scroll is past max, go to max
				if( p > max )
					p = max;
				animate.complete = function(){
					console.log("Scroll completed, no complete script running");
					_m.toggleControls(true);
				}
			}
			//
			if(p == p0){
				//If at top already, run the complete function
				if(load) animate.compete();
			}else{
				//Otherwise run the animate
				if(load) p = p0;
				$('body').animate({scrollTop:p}, animate);
			}
			return load;
		}
	},
	toggleControls:function(show){
		c = $$.find("."+o.controls);
		if(show === true)
			c.show();
		else
			if(show === false)
				c.hide();
			else
				c.toggle();
	},
	loader:function(file, complete){
		return $("<div />", {
			class:"load",
			html: $("<img />", {src:file}).load(complete)
		}).css({
			position:"absolute",
			width:"100%",
			height:"100%"
		});
	},
	data:function(complete){
		//For reasigning data. 
		//This should be done on init
		//Driven by o.id, o.action
		//And also after any page load
		loadingData = true;
		$.ajax({
			url:o.url,
			type:"POST",
			data:{
				id:o.id,
				action:o.action	
			},
			success:function(r){
				//Put the new data into the old data
				//Deep recursion needed.
				$.extend(true, data, r);
				loadingData = false;				
				_m.toggleControls(true);
				console.log("Data driven from ajax", data);
			//	if(complete)
			//		if(typeof complete == "function")
			//			complete(data);
			}
		});
	}
}
//Jquery Constructor
$.fn[plugin] = function(method){
	$$ = this;
	argCount = 0;
	args = $(arguments).toArray().slice(argCount);
	if(methods[method]){
		return methods[method].apply( this, Array.prototype.slice.call(args, 1));
	}else if(typeof method === 'object' || !method ){
		return methods.init.apply(this, args);
	}else{
		$.error("Method." + method + ' does not exist on $.fn.'+plugin);
	}
}
$[plugin] = function(options){
	return undefined;
}
})(jQuery);
