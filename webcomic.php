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
$inline_link = '<li class="%1$s %2$s"><a href="%3$s"></a><div><div></div><span>%4$s</span></div></li>';
?>
		<section class="webcomic" id='webcomic'>
			<article>
				<div class='overlay'><?php foreach(array("l prev", "r next") as $v)
					printf("<nav class='%s'><div><div></div></div></nav>", $v);
				?></div>
				<div class='load'></div>
				<div class='cur'><?php the_webcomic_image();?></div>
				<hr>
			</article>
			<nav class='inline icon'>
				<ul class='l'><?php
					printf($inline_link, "first", tooltip($r,array("_a _0","_b _0")), get_permalink($navIDs['oldest']), "First Page");
					printf($inline_link, "prev", tooltip($r), get_permalink($navIDs['older']), "Previous Page");
				?></ul>
				<ul class='r'><?php 
					printf($inline_link, "next", tooltip($r), get_permalink($navIDs['newer']), "Next Page");
					printf($inline_link, "last", tooltip($r,array("_b _2", "_c _2", "_c _1")), get_permalink($navIDs['newest']), "Last Page");
				?></ul>
				<ul><?php printf($inline_link, "link", tooltip($r), get_permalink($navIDs['current']), "Permalink");?></ul>
			</nav>
			<?php get_template_part("webcomic", "post");?>
		</section>

	</div><div>
		<?php get_sidebar("body");?>
<!--.webcomic<?php the_ID();?> end-->
