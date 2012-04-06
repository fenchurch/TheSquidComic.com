	<!--.head nav-->
		<header class='head'>
			<div>
				<nav class='l'>
					<ul>
						<li><a href='<?php echo home_url("/");?>' rel='home'>Home</a></li><?php 
wp_nav_menu(
	array(
		"container"=>false,
		"theme_location"=>"navbar", 
		"items_wrap"=>'%3$s'
	)
);
?>
					</ul>
				</nav>
				<hr>
			</div>
		</header>
