<?php
$size = "large";
$init =  true;
$thumbs = false;
if ( have_posts() ) :
	extract($q = (array) get_queried_object());?>
<section class='<?php echo $taxonomy; ?>'>
	<article class='hentry'>
		<header>
			<h1><a href='<?php echo get_term_link($slug, $taxonomy);?>'><?php echo $name;?></a></h1>
			<p class='description'><?php echo $description; ?></p>
		</header>
<?php

	if($taxonomy == "webcomic_storyline"
	|| $taxonomy == "webcomic_collection")
	$args = array(
		"post_type"=>"webcomic_post",
		"posts_per_page"=>-1,
		"order" => "ASC"
	);
	$args[$taxonomy] = $slug;
	query_posts($args);
	while( have_posts()) : the_post();
	  $useFirstImage = false;
	  $img = array();
	  $files = false;
	  if($init):
	    if(count($webcomic_files[$size])){
	      $files	= $webcomic_files[$size];
	    }elseif(!$files && $taxonomy == "webcomic_storyline"){
	      $useFirstImage = true;
	      $files = $post->webcomic_files[$size];
	    }
	    if($files)
	      foreach($files as $v)
	    	$img[] = $v;
?>
<?php 	    if(count($img) && $init): 
?>
		<div class='thumbnail'>
<?php 	      foreach($img as $i){?>
			<div><a href="<?php the_permalink();?>"><img src='<?php echo $i["url"]?>'/></a></div>
<?php 	      }?>
		</div>
<?php       endif;
	  endif;?>
<?php 	  if("webcomic_collection" == $taxonomy): if($init):?>
		<div class='content'>
<?php
	    $init = false;
	    $storylines = webcomic_tax("storyline", $term_id);
	    foreach($storylines as $storyline){
	      unset($args[$taxonomy]);
	      $args["webcomic_storyline"] = $storyline->slug;
	      query_posts($args);
	      get_template_part("tax");
	    }
	  endif;else:?>
<?php 	    if($init): $thumbs = true;?>
		<div class='content'>
			<ul class='thumbnails'>
<?php 	    endif;?>
<?php 	    if(!$useFirstImage && !$init): ?>
				<li>
					<div>
<?php 	      foreach($post->webcomic_files["small"] as $v):?>
						<a href='<?php the_permalink();?>' title='<?php the_title();?>'>
							<img src="<?php echo $v["url"];?>" />
						</a>
<?php	      endforeach;?>
					</div>
				</li>
<?php	    endif;?>
<?php 	  endif;?>
<?php 	  $init = false; 
	endwhile;
	if($thumbs):?>
			</ul>
<?php 	endif;__log($wp_query);?>
		</div>
	</article>
</section>
<?php endif;?>
