<?php /** Displays the full-size webcomic on the home and single-post pages. */ 
global $inkblot,$webcomic,$post,$q;
//if($q->have_posts())
//	$q->the_posts();
ajax_the_id(get_the_ID());
?>
<?php?>
	<section class="webcomic full">
		<div><?php ?></div>
		<div><?php print_r(get_webcomic_all_ids());?></div>
	</section>
