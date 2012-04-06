<?php /* Template Name: Coming Soon
Displays a Coming Soon Page */
get_header();
get_header("branding");
the_post();
?>
		<div id='main' class='body'>
			<div>
				<section class='content'>
					<div style='float:right; margin-left:1em'><?php dynamic_sidebar('inkblot-sidebar2');?></div>
					<article>
						<header>
							<hgroup>
								<h1><?php the_title(); ?></h1>
							</hgroup>
						</header>
							<?php the_content();?>
					</article>
					<hr>
				</section>
			</div>
		</div>
<?php 
get_footer();?>
