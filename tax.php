<?php
$size = "large";
extract($q = (array) get_queried_object());
if ( have_posts() ) :
	//the_post();
?>
<section class='<?php echo $taxonomy; ?>'>
	<article>
		<header><h1><?php echo $name;?></h1></header>
		<section><p><?php echo $description; ?></p></section>
<?php		if($webcomic_files){?>
		<section class='webcomic_files'>
<?php			foreach($webcomic_files[$size] as $i){?>
			<img src='<?php echo "$i->url"?>'/>
<?php 			}?>
		</section>
<?php		}?>
		<footer></footer>
<?php
	if("webcomic_collection" == $taxonomy) :
		$storyline = webcomic_tax("storyline", $term_id);

//		__log("WC tax:$taxonomy", webcomic_tax("storyline", $term_id));
?>	
<section class='webcomic_storyline'>
<?php
		foreach($storyline as $v) :
			extract((array)$v);
			__log("Storyline: ",$v);
		
?>
		<article>
			<header><h1><?php echo $name;?></h1></header>
			<section><p><?php echo $description; ?></p></section>
			<section class='webcomic_file'>
<?php			if($webcomic_files) {
				foreach($webcomic_files[$size] as $f){?>
				<img src='<?php echo $f["url"]?>' />
<?php 				}}?>
			</section>
			<footer></footer>

		</article>
<?php
		endforeach;
?>
	</section>
<?php 	endif;?>
	</article>
</section>
<?php endif;?>
