<!--webcomic-post-->
<article>
	<header>
		<hgroup>
			<h1 class='title'><?php the_title();?></h1>
		</hgroup>
	</header>
	<footer>
		<p>Posted by <span class='author'><?php the_author_link(); ?></span> on <span class='date'><?php the_date();?></span></p>
	</footer>
	<section class='content'>
		<?php the_content();?>
</section>
</article>
<!--webcomic-post end-->
