<?php /** Displays the full-size webcomic on the home and single-post pages. */ global $inkblot;

global $webcomic;
?>
<div class="webcomic full">
	<div id="webcomic-above" class="widgetized"><?php 
		dynamic_sidebar( 'inkblot-webcomic-above' ); 
	?></div>
	<?php the_webcomic_object( 'full', $inkblot->option( 'single_webcomic_link' ) ); ?>
	<nav class="below"><?php
		first_webcomic_link("%link", __("&laquo;", "webcomic")); 
		previous_webcomic_link("%link", __("&lsaquo;", "webcomic"));
		the_webcomic_link();
		next_webcomic_link("%link", __("&rsaquo;", "webcomic")); 
		last_webcomic_link("%link", __("&raquo;", "webcomic")); 
	?></nav>
	<?php pages_view();?>
	<div id="webcomic-below" class="widgetized"><?php dynamic_sidebar( 'inkblot-webcomic-below' ); ?></div>
</div>