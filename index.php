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
if( is_home() ){
	if( !is_paged() && $inkblot->option( 'home_webcomic_toggle' )
	){	
		//Home
		//+webcomic turned on
		//-paged blog
		print("<!--is home, home_webcomic_toggle on-->");
		//Home Options (tax)
		$tax = array();
		$c = $inkblot->option( 'home_webcomic_collection' );
		if ( !empty( $c ) ) { 	
			$term = get_term( ( int ) $c, 'webcomic_collection' );
			$tax['webcomic_collection'] =$term->slug;
		}
		$s = $inkblot->option( 'home_webcomic_storyline' );
		if ( !empty( $s ) ) { 	
			$term = get_term( ( int ) $s, 'webcomic_storyline' );
			$tax['webcomic_storyline'] =$term->slug;
		}
		//Query	
		$q = new WP_Query( array_merge(
			array(
				'post_type'=>'webcomic_post',
				'posts_per_page'=>1,
				'orderby'=>'date',
				'order'=> $inkblot->option( 'home_webcomic_order' )
			),$tax)
		);
		//Loop
		if($q->have_posts() ) while ( $q->have_posts() ) 
		{
			$q->the_post();
			get_template_part( 'webcomic', 'home' );
		}

	}elseif(is_paged()){
		print("<!--is home, home_webcomic_toggled off-->");
	}?>
	<hr>
	</div>
	<div>
	<?php print("<!--is home, home_webcomic_toggled off-->");?>
	<?php 
		//Show body Sidebars
		get_sidebar("body");
		//Loop
		get_template_part('loop');
	?>
<?php
}elseif(is_singular( 'webcomic_post' )){
	//Webcomic template needs already be in the loop
	if(have_posts()) while( have_posts() ){
		the_post();
		get_template_part( 'webcomic', 'single' ); 
	}
}elseif(is_singular() )
{	//Singular post
	print("<!--is singular-->");
	get_template_part('loop');
}elseif( is_archive() )
{	
	print("<!--is archive-->");	
	global $wp_query, $post, $posts;
	$q = get_queried_object();
	get_template_part('tax', $q->taxonomy);
}else{
	get_template_part('loop');
	__log($wp_query);
}
?>
	<hr>
	</div>
</div>
<? get_footer(); ?>
<!--Index.php End-->
