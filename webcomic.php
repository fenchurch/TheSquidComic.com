<?php /** Displays the full-size webcomic on the home and single-post pages. */ 
//global $inkblot,$webcomic,$post,$q;
//if($q->have_posts())
//	$q->the_posts();
ajax_the_id(get_the_ID());

$r = array(
	"_a _0","_a _1",
	"_b _0","_b _1","_b _2",
		"_c _1","_c _2"	
);

function tooltip($arg, $pref=false){
	if($pref) $r = $pref;
	else $r = $arg;
	sort($r);
	$i = rand(0, count($r)-1);
	return $r[$i];
}
$inline_link = '<li class="%1$s %2$s"><a href="%3$s"></a><p><span>%4$s</span></p></li>';
?>
<?php?>
	<section class="webcomic">
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
				printf($inline_link, "first", tooltip($r,array("_a _0","_b _0")), "#FIRST", "First Page");
				printf($inline_link, "prev", tooltip($r), "#PREV", "Previous Page");
			?></ul>
			<ul class='r'><?php 
				printf($inline_link, "next", tooltip($r), "#NEXT", "Next Page");
				printf($inline_link, "last", tooltip($r,array("_b _2", "_c _2", "_c _1")), "#LAST", "Last Page");
			?></ul>
			<ul><?php printf($inline_link, "link", tooltip($r), "#LINK", "Permalink");?></ul>

		</nav>
		<div><?php ?></div>
	</section>
