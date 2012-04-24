<!--.webcomic<?php the_ID();?> begin-->
<?php /** Displays the full-size webcomic on the home and single-post pages. */ 
global $post;

$storyline = current(get_the_terms($post->ID, "webcomic_storyline"));
$collection = current(get_the_terms($post->ID, "webcomic_collection"));
$nav = get_webcomics_data($post->ID,$storyline->slug,$collection->slug);
extract(nav($nav, $post->ID));

?>
		<section class="webcomic" id='webcomic'>
			<article>
				<nav><ul><?php echo $prev; echo $next; ?></ul></nav>
				<div class='cur'><?php the_webcomic_image();?><hr></div>
			</article>
			<hr>
			<nav class='inline icon'>
				<ul class='l'><?php echo $start; echo $prev;?></ul>
				<ul class='r'><?php echo $next; echo $end;?></ul>
				<ul>
					<li class='info hide'>
						<div><?php get_template_part("webcomic", "post");?></div>
					</li>
					<li class='zoom in' title='Zoom:Fit'></li>
					<?php echo $bookmark;?>
				</ul>
				<hr>
			</nav>
			<hr>
		</section>
		<script>
(function($){
	var pl =$("#webcomic").
		pageLoader(<?php the_ID();?>,{data:<?php echo json_encode($nav);?>});
	$("#webcomic nav a").not(".bookmark").click(pl.go);
	$("#webcomic .cur").dblclick($.toggleSize);
	$("#webcomic .zoom").click($.toggleSize);
	var $info = $("#webcomic .info");
	$info.click(function(e){toggleInfo()});
	var toggleInfo = function(){
		if($info.hasClass("show")){
			$info.removeClass("show").addClass("hide");
			$(document).unbind("click", hideInfo);		
		}else if($info.hasClass("hide")){
			$info.removeClass("hide").addClass("show");
			$(document).bind("click", hideInfo);			
		}
	}
	var hideInfo = function(e){
		if($info.is($(e.target))) return;
		else if($info.hasClass("show")) toggleInfo();
	}
	
})(jQuery);
		</script>
<!--.webcomic<?php the_ID();?> end-->
