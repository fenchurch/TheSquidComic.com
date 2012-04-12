<?php /* Template Name: Coming Soon
Displays a Coming Soon Page */
get_header();
get_header("branding");
the_post();
?>
		<div id='main' class='body'>
			<div>
				<?php get_sidebar("body");?>
				<section class='content'>
					<article>
						<header>
							<hgroup>
								<h1><?php the_title(); ?></h1>
							</hgroup>
						</header>
						<div>	
							<?php the_content();?>
						</div>
					</article>
				</section>
				<hr>
			</div>
		</div>
<?php 
get_footer();?>
