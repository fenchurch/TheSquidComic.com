<?php global $post, $webcomic; ?>
		<!--.head nav-->
		<header class='head'>
			<div>
				<nav class='l'>
					<ul>
<?php 
wp_nav_menu(
	array(
		"container"=>false,
		"theme_location"=>"navbar", 
		"items_wrap"=>'%3$s'
	)
);
?>
					</ul>
				</nav><?php if($comic_nav = comic_nav()):?>
				<nav class='r'>
					<ul>
<?echo $comic_nav;?>
					</ul>
				</nav><?php endif;?>
				<hr>
			</div>
		</header>
		<!--.head nav end-->
