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
global $webcomic, $inkblot, $q, $post;
?>
<!--Index.php-->
<?php
get_header();
get_header("topNav");
get_header("branding");
?>
<div id='main' class='body'>
	<div>
		<?php
if ( is_home() && !is_paged() && $inkblot->option( 'home_webcomic_toggle' ) ) 
{	
	print("<!--is home, home_webcomic_toggle on-->");
	$tax = array();
	$c = $inkblot->option( 'home_webcomic_collection' );
	if ( !empty( $c ) ) 
	{ 	
		$term = get_term( ( int ) $c, 'webcomic_collection' );
		$tax['webcomic_collection'] =$term->slug;
	}
	$s = $inkblot->option( 'home_webcomic_storyline' );
	if ( !empty( $s ) ) 
	{ 	
		$term = get_term( ( int ) $s, 'webcomic_storyline' );
		$tax['webcomic_storyline'] =$term->slug;
	}
	//	echo $inkblot->option( 'home_webcomic_order' );
	
	$q = new WP_Query( array_merge(
		array(
			'post_type'=>'webcomic_post',
			'posts_per_page'=>1,
			'orderby'=>'date',
			'order'=> $inkblot->option( 'home_webcomic_order' )
		),$tax)
	);
	//print_r($q);
	if($q->have_posts() ) while ( $q->have_posts() ) 
	{
		$q->the_post();
		get_template_part( 'webcomic', 'home' );
	}
}elseif(is_home() && !is_paged()){
	print("<!--is home, home_webcomic_toggled off-->");
	
	//	get_template_part( 'webcomic', 'home');

} elseif ( is_singular( 'webcomic_post' ) ) 
{
	if(have_posts()) while( have_posts() ){
		the_post();
		get_template_part( 'webcomic', 'single' ); 
	}
}elseif( is_archive() ){
	print("this is an archive");
	if(is_tax("webcomic_collection"))
		print(" of a collection");
	if(is_tax("webcomic_storyline"))
		print(" of a storyline");
}else{
	print("Dont know what this is. Lets output the Query<pre>");
	print_r($wp_query);
	print("</pre>");
}
?>
	<hr>
	</div>
</div>
<? get_footer(); ?>
<!--Index.php End-->
