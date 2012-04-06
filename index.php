<?php
/**
 * Template Name: Index
 * This file contains everything for the following templates:
 * 
 * home.php
 * single-webcomic_post.php
 * single.php
 * page.php
 * search.php
 * archive.php
 * 404.php
 * 
 * You can change any of these by modifying this file directly or
 * creating the missing template file. For more information, see
 * http://codex.wordpress.org/Template_Hierarchy
 */
global $webcomic, $inkblot, $q;
?>
<!--Index.php-->
<?php
get_header();
get_header("topNav");
get_header("branding");
?>
<div id='main' class='body'>
	<div>
		<section id='webcomic'>
			<div>
		<?php
if ( is_home() && !is_paged() && $inkblot->option( 'home_webcomic_toggle' ) ) 
{	
	$i = $inkblot->option( 'home_webcomic_collection' ); 
	if ( !empty( $i ) ) 
	{ 	
		$wc = get_term( ( int ) $i, 'webcomic_collection' );
		$wcl = '&webcomic_collection=' . $wc->slug;
	} else 
		$wcl = '';
	$q = new WP_Query( 
		'post_type=webcomic_post&posts_per_page=1&order=' . $inkblot->option( 'home_webcomic_order' ) . $wcl
	); 
	while ( $q->have_posts() ) 
	{
		$q->the_post();
		get_template_part( 'webcomic', 'home' ); 
	} 
} elseif ( is_singular( 'webcomic_post' ) ) 
{	print("this a single");
	get_template_part( 'webcomic', 'single' ); 
} 
?>
			<div>
		</section>
	</div>
</div>
<? get_footer(); ?>
<!--Index.php End-->
