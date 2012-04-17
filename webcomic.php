<!--.webcomic<?php the_ID();?> begin-->
<?php /** Displays the full-size webcomic on the home and single-post pages. */ 
//global $inkblot,$webcomic,$post,$q;
//$id = get_the_ID();
$navIDs = get_webcomic_nav_ids();
ajax_the_id();

$r = array(
	"_a _0","_a _1",
	"_b _0","_b _1","_b _2",
		"_c _1","_c _2"	
);
$inline_link = '<li class="%s">%s<div><div></div><span>%s</span></div></li>';
$p = "<p></p>";
?>
		<section class="webcomic" id='webcomic'>
			<article>
				<div class='overlay'><?php foreach(array("l prev", "r next") as $v)
					printf("<nav class='%s'><div><div></div></div></nav>", $v);
				?></div>
				<div class='cur'><?php the_webcomic_image();?></div>
			</article>
			<hr>
			<nav class='inline icon'>
				<ul class='l'><?php
					printf($inline_link, "oldest ".tooltip($r,array("_a _0","_b _0")), $p, "First Page");
					printf($inline_link, "older ".tooltip($r), $p, "Previous Page");
				?></ul>
				<ul class='r'><?php 
					printf($inline_link, "newer ".tooltip($r), $p, "Next Page");
					printf($inline_link, "newest ".tooltip($r,array("_b _2", "_c _2", "_c _1")), $p, "Last Page");
				?></ul>
				<ul><?php printf(
					$inline_link,
					"current ".tooltip($r), 
					"<a href='".get_permalink($navIDs['current'])."'></a>",
					"Permalink"
				);?></ul>
			</nav>
			<hr>
		</section>

		<?php get_template_part("webcomic", "post");?>
<?php 
/*
	this stuff will need to be put into the scripts.js file 
*/
?>
<script>
(function($){
	var toggle = function(){
		var rent = this.parentNode.parentNode;
		$rent = $(rent);

		if(rent.style.maxWidth) {
			document.location.hash = "";						
			document.location.hash = "#webcomic";
			//Doubleswitch to get current box-size
			w0 = rent.style.maxWidth;			
			rent.style.maxWidth = "";
			w1 = $rent.width();
			rent.style.maxWidth = w0
			$rent.animate({maxWidth:w1},{complete:function(){rent.style.maxWidth = ""}});
		}else{
			document.location.hash = "";						
			document.location.hash = "#webcomic";			
			$dom = $(this);			
			w = Math.floor($(window).height() * $dom.width() / $dom.height());
			$rent.animate({maxWidth:w});
		}
	}
	$("#webcomic .inline .oldest").click(function(e){ e.preventDefault(); pl.go("oldest");});
	$("#webcomic .inline .older").click(function(e){ e.preventDefault(); pl.go("older");});
	$("#webcomic .inline .newer").click(function(e){ e.preventDefault(); pl.go("newer");});
	$("#webcomic .inline .newest").click(function(e){ e.preventDefault(); pl.go("newest");});	
	$("#webcomic nav.r").click(function(e){e.preventDefault(); pl.go("newer")});
	$("#webcomic nav.l").click(function(e){e.preventDefault(); pl.go("older")});
	$("#webcomic article").dblclick(toggle);

	var pl = $("#webcomic article").pageLoader({
		url:MyAjax.url, 
		id:<?php the_ID();?>, 
		action:"ajax-nav"
	});


})(jQuery);
</script>
<!--.webcomic<?php the_ID();?> end-->
