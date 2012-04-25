<?php /** Displays posts on the home, archive, and search pages. */ global $inkblot, $wp_query; ?>
<?php if ( 1 < $wp_query->max_num_pages ) { ?><nav class="paginated above"><?php echo $inkblot->get_paginated_posts_links(); ?></nav><?php } ?>
<?php 
if( have_posts() ):
?>
	<section>
<?php
	while ( have_posts() ) : 
		the_post();
?>		
<?php 		if ( 'webcomic_post' == get_post_type() ) :?>
		<article class='hentry'>
			<header><h1><a href='<?the_permalink();?>'><?php the_title(); ?></a></h1></header>
		
<?php 			if ( $inkblot->option( 'archive_webcomic_toggle' ) ) : ?>
			<div class="thumbnail">
				<div>
					<?php 
					if ( has_post_thumbnail() ) the_post_thumbnail(); 
					else the_webcomic_object( $inkblot->option( 'archive_webcomic_size' ), 'self' ); 
					?>
				</div>
			</div>
<?php 			endif; ?>
			<div class='content'>
				<?php 	
				if ( is_archive() || is_search() ) the_excerpt(); 
				else the_content(); 
				?>
			</div>
			<hr>
			<footer>
				<div><?php 
					$link = "";
					printf(	"by <a href='%s'>%s</a>",  
						get_author_posts_url( get_the_author_meta( 'ID' ) ), 
						get_the_author()
					);
					printf( " | <a href='%s' rel='bookmark'>%s @ %s</a> | ",
						get_permalink(),
						get_the_time(get_option('date_format')),
						get_the_time()
					);
					comments_popup_link(); 
					edit_post_link( NULL, ' | ' );
				?></div>
			</footer>

		</article><!-- #post-<?php the_ID(); ?> -->
<?php 	else: 
?>
		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<header><h1><a href='<?the_permalink();?>'><?php the_title(); ?></a></h1></header>
			<?php if ( has_post_thumbnail() ) :?>
			<div class="thumbnail"><div><?php the_post_thumbnail(); ?></div></div>
			<?php endif; ?>
			<div class="content"><?php 
				if ( is_archive() || is_search() ){ 
					the_excerpt(); 
				}else the_content(); 
			?></div>
			<hr>
			<footer>
					<div>
						by <a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>"><?php the_author(); ?></a> | <a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_time( get_option( 'date_format' ) ); ?> @ <?php the_time(); ?></a> | <?php comments_popup_link(); edit_post_link( NULL, ' | ' ); ?>
					</div>
				</footer>
			</article><!-- #post-<?php the_ID(); ?> -->
<?php endif;//post_type
endwhile;//The Loop ?>
	</section>
<?php endif; //have_posts?>
<?php if ( 1 < $wp_query->max_num_pages ) { ?><nav class="paginated below"><?php echo $inkblot->get_paginated_posts_links(); ?></nav><?php } ?>