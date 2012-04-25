<?php
get_header("all");
?>
<div class='body' id='main'>
	<div>
		<section>
<?php if( have_posts()) : the_post();
	$author = get_the_author();
	$author_id = get_the_author_meta("ID");
	$author_url = get_author_posts_url($author_id);
	$author_email = get_the_author_meta('user_email');
	$author_meta = array();
	foreach(array("user_email", "user_url", "aim", "yim", "jabber", "twitter", "facebook", "tumblr") as $v)
		if($meta = get_the_author_meta($v))
			$author_meta[$v] = $meta;
?>
			<article class='hentry author'>
				<header>
					<h1>
						<?php printf(
							"<a href='%s' title='%s' rel='me'>%s</a>",
							esc_url($author_url),
							esc_attr($author),
							$author
						);
						?>
					</h1>
<?php 					if(count($author_meta)):?>					
					<p>
<?php 						$meta = array();
						foreach($author_meta as $k => $v){
							if($k == "twitter") $v = "http://twitter.com/#!/$v";
							if($k == "user_email") $v = "mailto:$v";
							$meta[] = sprintf(
								"<a href='%s' class='%s'>%s</a>",
								$v, $k,
								ucwords(str_replace("user_", "", $k))
							);
						}
						echo implode(" | ",$meta);
?>
					</p>
<?php 					endif;?>

				</header>
<?php				if ( get_the_author_meta( 'description' ) ) : ?>
				<div class='thumbnail author-avatar'>
					<div>
						<?php
						printf(	"<a href='%s' title='%s' rel='me'>%s</a>",
							"mailto:$author_email",
							esc_attr($author),
							get_avatar($author_id)
						);
						?>
					</div>
				</div>
				<div class='content'>
					<p><?php the_author_meta( 'description' ); ?></p>
				</div>
<?php 				endif;?>
				<footer>
					<h2>Posts:</h2>
<?php		rewind_posts();

		get_template_part("loop");
?>
				</footer>
			</article>
<?php 	endif;?>
		</section>
	</div>
</div>
