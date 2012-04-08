<?php /* Template Name: Coming Soon
Displays a Coming Soon Page */
get_header();
get_header("branding");
the_post();
?>
		<div id='main' class='body'>
			<div>
				<section class='webcomic'></section>
				<section class='widgets'>
					<div class='widget l'><?php
					dynamic_sidebar('inkblot-sidebar1');
					?></div>
					<div class='widget r'><?php
					dynamic_sidebar('inkblot-sidebar2');
					?></div>
				</section>
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
					<hr>
				</section>
			</div>
		</div>
<?php 
get_footer();?>
