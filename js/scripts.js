if(!console){
	console = {log:function(){return;}}
}
(function($){
var plugin = "pageLoader",
//Plugin Vars
$$,o,data,relData,loadingData,preloaded,
//Public Methods
methods = {
	init	:function(id, options){
		//Set Options
		o = _m.options(options);
		//Make methods public
		$.extend(this, methods);
		if(!data){
			data = {};
			_m.data(id);
		}
		return this;
	},
	go	:function(page){
		//Check if its being fed the event
		if(typeof page == "object"){
			page.preventDefault();
			page = page.currentTarget.className;
		}
		if(page != 'bookmark')
			if(relData['bookmark'] == relData[page]) return;
		//Check if we are loading Data
		_m.animate.scroll(page, function(){
			_m.animate.pageFlip(page, function(){
				relData = _m.relativeData( relData[page].ID , page);
				_m.updateControls();
			});
		});
		return;
	}
},
//Private Methods
_m = {
	options	:function(options){
		o = $.extend(true, {
			url:"",
			ajax:{
				action:"ajax-nav",
				id:0,		
				storyline:'',
				collection:''
			},
			data:null,
			duration:"slow",
			easing:"easeInOutQuad",
			cur:"cur",
			load:"load",
			controls:".overlay"
		}, options);
		return o;
	},
	preload:function(d, id){
		var r;
		if(!preloaded) r = {
			start:null,
			prev:null,
			bookmark:null,
			next:null,
			end:null
		}
		if(id) switch(id){
		case "start":
			r = {
				start:preloaded.start,
				prev:null,
				bookmark:preloaded.start,
				next:null,
				end:preloaded.end
			};
			break;
		case "prev":
			r = {
				start:preloaded.start,
				prev:null,
				bookmark:preloaded.prev,
				next:preloaded.bookmark,
				end:preloaded.end
			};				
			break;
		case "next":
			r = {
				start:preloaded.start,
				prev:preloaded.bookmark,
				bookmark:preloaded.next,
				next:null,
				end:preloaded.end
			};					
			break;
		case "end":
			r = {
				start:preloaded.start,
				prev:null,
				bookmark:preloaded.end,
				next:null,
				end:preloaded.end
			};						
			break;
		}
		$.each(r, function(k,v){
			if(v === null){
				img = $("<img />",{
					src	:d[k].webcomic_files.full[0].url,
					title	:d[k].post_title
				});
				r[k] = img;
			}
		});
		preloaded = r;
	},
	//
	animate:{
		loading	:function(show){
			//Loading Element
			//Toggled
			if(!o.loading && show){
				o.loading = $("<div />", {class:"loading", html:"Loading..."}).
					hide().
					animate({opacity:1}, o.duration);
				$$.append(o.loading);
			}else{
				if(o.loading)
					o.loading.
					animate({opacity:0},{
						duration:o.duration, 
						complete:function(){
							o.loading.remove(); 
							o.loading=null;
							}
						}
					);
			}
		},
		pageFlip:function(id, complete){
			//Page Flipping Animation
			var dir = (id == "next" || id == "end") ? true : false,
			start = dir ? 1: -1,
			end = 100,
			unit = "%",
			cur = $$.find("."+o.cur),
			loader =_m.loader(id).
				animate({left:0+unit},
					{
						duration:o.duration,
						easing:o.easing,
						step:function(now,fx){
							//Move to the Opposite Position
							cur.css({left:(fx.pos*(-start)*end)+unit})
						},
						complete:function(){
							cur.remove();
							loader.	removeAttr("style").
								removeClass(o.load).
								addClass(o.cur);
							if(complete) 
							if(typeof complete == "function")
								complete(id);
							clearSelection();
						}
				}).
				css({left:(start*end)+unit}).
				height(cur.find("img").height());

			cur.before(loader);
		},
		scroll:function(id, complete){
			var animate = {
				duration:o.duration,
				easing:o.easing
			},
			$cur = $$.find("."+o.cur),
			$w = $(window),
			dir = id == "next" || dir == "end" ? true:false,
			p0 = $cur.offset().top,
			p1 = p0 + $cur.height(),
			max = p1 - $w.height();
			//
			p = $w.scrollTop() + $w.height();
			load = false;
			
			if( ! (p+1 < p1 && dir)){
				//If back or already scrolled past
				//Load
				load = true;
				complete()
				
				animate.complete = function(){
				}
			}else{
				//Otherwise scroll to next height
				//if next scroll is past max, go to max
				if( p > max )
					p = max;
				animate.complete = function(){
				}
			}
			//
			if(p == p0){
				//If at top already, run the complete function
				if(load) animate.compete();
			}else{
				//Otherwise run the animate
				if(load) p = p0;
				//Works for both
				$('html').animate({scrollTop:p}, animate);
				animate.complete = function(){};
				$("body").animate({scrollTop:p}, animate);
			}
			return load;
		}
	},
	updateControls:function(){
		$.each( relData, function(i,v){
			$a = $$.find("[rel="+i+"]");
			label = v.post_title;
			$a.attr("href", v.guid).attr("title", $a.attr("rel")+": "+label).html(label);
			//$("link[rel="+i+"]").attr("href",v.guid);
			if(i == "bookmark"){
				info = $$.find(".info");
				if(info.length){
					info.find(".title").html(v.post_title);
					info.find(".content").html(v.post_content);
				}
			}
		});
	},
	loader:function(id, complete){
		return $("<div />", {
			class:"load",
			html: preloaded[id]
		}).css({
			position:"absolute",
			width:"100%",
			left:(id == "next" || id == "end"? "100%" : "-100%")
		}).append($("<hr>"));
	},
	relativeData:function(id,page){
		var index = 0,
		count = 0;
		$.each(data, function(i,v){count++; if(id == v.ID) index = i});
		count--;
		r = {
			start	:data[0],
			prev	:data[(index==0 ? index : index-1)],
			bookmark:data[index],			
			next	:data[(index < count ? index+1 : count)],
			end	:data[count]
		};
		_m.preload(r,page);
		return r;
	},
	data:function(id){
		//For reasigning data. 
		//This should be done on init
		//If data is loaded (o.data)
		//set the initial relative Data to either 
		//id or the first in data
		if(o.data){
			data = o.data;
			relData = _m.relativeData(id || o.data[0].id);
			return;
		}
		loadingData = true;
		$.ajax(	{
			url:o.url,
			type:"POST",
			data:o.ajax,
			success:function(r){
				//Put the new data into the old data
				//Deep recursion needed.
				$.extend(true, data, r);
				relData = _m.relativeData(id || o.ajax.id);
				loadingData = false;				
				if(complete)
					if(typeof complete == "function")
						complete(data);
			},
			error:function(){
				console.log(arguments);
				loadingData = false;
			}
		});
	}
}
//Jquery Constructor
$.fn[plugin] = function(method){
	$$ = this;
	if(methods[method]){
		return methods[method].apply( this, Array.prototype.slice.call(arguments, 1));
	}else if(typeof method === 'object' || !method || !(methods[method])){
		return methods.init.apply(this, arguments);
	}else{
		$.error("Method." + method + ' does not exist on $.fn.'+plugin);
	}
}
$[plugin] = function(options){
	return undefined;
}
})(jQuery);
(function($){
	$.toggleSize = function(e){
		console.log(e);
		if(! $(e.target).is($(e.currentTarget))) return;
		e.preventDefault();
		
		var $wc = $("#webcomic");
		var rent = $wc[0].parentNode;
		$rent = $(rent);
		zoom = $wc.find(".zoom").toggleClass("in").toggleClass("out");
		zoom.attr("title", zoom.hasClass("in") ? "Zoom:Fit" : "Zoom:Full");
		if(rent.style.maxWidth) {
			$(window).scrollTop($rent.offset().top);
			//Doubleswitch to get bookmark box-size
			w0 = rent.style.maxWidth;			
			rent.style.maxWidth = "";
			w1 = $rent.width();
			rent.style.maxWidth = w0
			$rent.animate({maxWidth:w1},{complete:function(){rent.style.maxWidth = ""}});
		}else{
			$(window).scrollTop($rent.offset().top);
			$dom = $wc.find(".cur");
			w = Math.floor($(window).height() * $dom.width() / $dom.height());
			$rent.animate({maxWidth:w});
		}
	}
})(jQuery);

var clearSelection = function() {
	var sel ;
	if(document.selection && document.selection.empty){
		document.selection.empty() ;
	} else if(window.getSelection) {
		sel=window.getSelection();
	if(sel && sel.removeAllRanges)
		sel.removeAllRanges() ;
	}
}
