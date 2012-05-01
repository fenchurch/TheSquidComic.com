<?php
/*
 *
 *
 */
$fullsize = "large";
$thumbsize = "medium";
$used_post = false;
extract((array) get_queried_object());
__log(get_queried_object(),$wp_query);
$query = $wp_query->query;
$query = array_merge(array(
	"post_type"=>"webcomic_post",
	"posts_per_page"=>-1,
	"order" => "ASC"
), $query);
query_posts($query);
__log($wp_query);

?>
<?php if(have_posts()): the_post();
	if(get_post_type() == "webcomic_post"){	
		if(count($webcomic_files[$fullsize])){
			$files = $webcomic_files[$fullsize];
		}elseif($taxonomy == "webcomic_storyline"){
			$used_post = true;
			$files = $post->webcomic_files[$fullsize];
		}else{
			$files = false;
		}
	}
?>
<section class='<?php echo $taxonomy;?>'>
	<article class='hentry'>
		<header>
			<h1>
				<?php printf( "<a href='%s'>%s</a>",
				get_term_link($slug, $taxonomy),
				$name);
				?>
			</h1>
			<?php if($description) 
				printf( "<p class='%s'>%s</p>",
				"description",
				$description);
			?>
		</header>
<?php if($files && count($files)):
?>		<div class='thumbnail'>
			<div>
				<?php 
		foreach($files as $f){
			printf( "<a href='%s' title='%s'><img src='%s'/></a>", 
				get_permalink(), 
				get_the_title(),
				$f["url"]
			);
		}
?>
			</div>
		</div>
<?php endif;?>
		<div class='content'>
<?php 
if("webcomic_collection" == $taxonomy):
	$storylines = webcomic_tax("storyline", $term_id);
	foreach($storylines as $storyline){
		$query= array("webcomic_storyline" => $storyline->slug);
		query_posts($query);
		get_template_part("tax2", $taxonomy);
	}
else: 
	if(!$used_post)
		rewind_posts();
?>
			<div class='thumbnails'>
				<ul>
<?php 	while( have_posts()):
		the_post();
		if(has_post_thumbnail() || count($files = $post->webcomic_files[$thumbsize])):
			$imgs = array();
			if(has_post_thumbnail())
				$imgs[] = get_the_post_thumbnail($post->ID, $thumbsize);
			else
				foreach($files as $v)
					$imgs[] = sprintf("<img src='%s' />", $v["url"]);
?>
				<?php 
			printf(	"<li><div><a href='%s' title='%s' rel='%s'>%s</a></div></li>",
				get_permalink(),
				$name.", ".get_the_title(),
				"bookmark",
				implode("", $imgs)
			);
		endif;
	endwhile;?>
				</ul>
			</div>
<?php endif;?>
		</div>
	<article>
</section>
<?php endif;?>
