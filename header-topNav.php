	<!--.head nav-->
		<header class='head'>
			<div>
				<nav class='l'>
					<ul>
<?php wp_nav_menu(
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
