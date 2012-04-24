<?php global $post, $webcomic; ?>
		<!--.head nav-->
		<header class='head'>
			<div>
				<nav class='l'>
					<ul><li><a href="/">Home</a></li><?php 
wp_nav_menu(
	array(
		"container"=>false,
		"theme_location"=>"navbar", 
		"items_wrap"=>'%3$s'
	)
);
?></ul>
				</nav><?php ?>
				<nav class='r'>
					<ul><? if($comic_nav = comic_nav()) echo $comic_nav;?><li class='search'><?php get_search_form();?></li></ul>
				</nav>
				<hr>
			</div>
		</header>
		<!--.head nav end-->
