		<section class='webcomic_post'>
			<header>
					<hgroup>
						<h1><?php the_title();?></h1>
						<p>Posted by <?php the_author_link(); ?> on <?php the_date();?></p>
					</hgroup>
			</header>
			<article>
				<?php the_content();?>
			</article>
			<footer>
				<p><?php ?></p>
			</footer>
		</section>
