<?php
/* 

	
	Customizing - The Stuff I (rusty) wrote 


*/

function favicon(){
	echo '<link rel="Shortcut Icon" type="image/png" href="'.get_bloginfo('stylesheet_directory').'/images/favicon.png" />';
}
add_action('admin_head', 'favicon');
add_action('wp_head', 'favicon');

define("JUMP_ELE", "webcomic");
function rel_url_jump($r){return "$r#".JUMP_ELE;}
add_filter('webcomic_get_relative_url', 'rel_url_jump');

function rel_link_jump($r){return preg_replace( '/href="(.*?)"/', 'href="$1#'.JUMP_ELE.'"', $r);}
add_filter('webcomic_get_relative_link', 'rel_link_jump');

function get_webcomic_permalink(){
	$wc = get_webcomic_post();
	return $wc->guid."#".JUMP_ELE;
}
function the_webcomic_link($format = "%link", $link='%label'){
	global $webcomic, $id;
	$label = '<span>'.__('&infin;', 'webcomic').'<span>';
	$link = '<a href="%s" rel="permalink" class="permalink-webcomic-link">'.$label.'</a>';
	echo sprintf($link, rel_url_jump(get_permalink($id)));
}
function pages_view(){
	global $webcomic, $id;
	?><div class='webcomics-pages'><?php
	print_r($webcomic->get_the_webcomic_archive("group=storyline&&image=small&group_image=medium"));
	?></div><?php
}
function the_webcomic_object_nospan( $size = 'full', $link = false, $taxonomy = false, $terms = false, $key = false, $id = false ) { 
	global $webcomic;
	echo preg_replace('/<span.*?>(.*)<\/span>/','$1',$webcomic->get_the_webcomic_object( $size,$link,$taxonomy,$terms,$key,$id )); 
}
function get_the_webcomic_image($id=false){
	if(!$id) $id = get_the_ID();
	$wc = get_webcomic_post($id);
	$ID = ($id) ? $id : $wc->ID;
	$wc = $wc->webcomic_files["full"];
	$r = false;
	if($count = count($wc)){
		$imgFmt = '<img src="%1$s" id="webcomic_image_%2$s" />';
		if($count>1){
			//If Multiple, Return Array
			$r = array();	
			foreach($wc as $k => $v)
				$r[$k] = sprintf($imgFmt, $wc[$k]['url'], $ID."_".$k);
		}else{
			//If Singular, Return String
			$r = sprintf($imgFmt, $wc[0]['url'], $ID);
		}
	}
	return $r;
}
function the_webcomic_image($id=false){
	$img = get_the_webcomic_image($id);
	if(count($img)>1) foreach($img as $i) echo $i;
	else echo $img;
}

function random_backgroundImages_fromDir(
	$count=50,
	$x0=0,$x1=100,$xM="%",
	$y0=0,$y1=100,$yM="%",
	$dir="images/bubbles/",	
	$echo=true
){
	$cache = array();
	if(!count($cache)){
		$pool = glob($dir."*.{png,gif,jpg,jpeg}", GLOB_BRACE);
		if(!count($pool)){
			$pool = glob(TEMPLATEPATH."/$dir"."*.{png,gif,jpg}", GLOB_BRACE);
			foreach($pool as $k => $p)
				$pool[$k] = str_replace(TEMPLATEPATH, get_bloginfo("template_url"), $p);
		}
		$areaX=array($x0,$x1,$xM);
		$areaY=array($y0,$y1,$yM);
		
		for(
			$i=0; 
			$i < (is_array($count) ? rand($count[0], $count[1]) : $count);
			$i++
		){
			$r["img"][] = sprintf("url(%s)", $pool[rand(0, count($pool)-1)]);
			$r["pos"][] = sprintf('%1$s %2$s',
				rand($areaX[0], $areaX[1]).$areaX[2],
				rand($areaY[0], $areaY[1]).$areaY[2]
			);
		}
		foreach($r as $k => $v)
			$r[$k] = implode($v,",");
		if(!$echo)
			return $r;
		else{
			echo 
			sprintf("background-image:%s;\n",$r["img"]).
			sprintf("background-position:%s;\n",$r["pos"]);
		}
	}else{
		echo $cache[rand(0,count($cache)-1)];
	}
}
function tooltip($arg, $pref=false){
	if($pref) $r = $pref;
	else $r = $arg;
	sort($r);
	$i = rand(0, count($r)-1);
	return $r[$i];
}

wp_enqueue_script( 'my-scripts',  get_bloginfo("template_directory"). '/js/scripts.js', array( 'jquery' ) );

// embed the javascript file that makes the AJAX request
wp_enqueue_script( 'ajax-nav-request', get_bloginfo("template_directory")  . '/js/ajax.js', array( 'jquery' ) );
wp_localize_script( 'ajax-nav-request', 'MyAjax', array('url' => admin_url( 'admin-ajax.php' )));

// if both logged in and not logged in users can send this AJAX request,
// add both of these actions, otherwise add only the appropriate one
add_action( 'wp_ajax_nopriv_ajax-nav', 'ajax_nav' );
add_action( 'wp_ajax_ajax-nav', 'ajax_nav' );

function ajax_nav() { 
	// get the submitted parameters
	global $webcomic;
	$r = array();
	foreach(get_webcomic_nav_ids((int) $_POST['id']) as $k => $v){
		$r[$k] = $webcomic->get_webcomic_post($v);
	}
	make_json($r);
}
function make_json($data){
	// generate the response
	$response = json_encode($data);
	// response output 
	header( "Content-Type: application/json" );
	echo $response;
	// IMPORTANT: don't forget to "exit"
	exit;
}
function ajax_the_id($id=null,$collection=''){
	$id = $id ? $id : get_the_ID();
	?>
	<script>
	/*<![CDATA[ */
		MyAjax.id = <?php echo $id;?>;
	/* ]]>*/
	</script>
<?
}

function get_webcomic_nav_ids($id=null){
	$id = $id ? $id : get_the_ID();
	$p = get_webcomic_ids($id);
	foreach($p as $k => $v)
		if($v == $id)
			$i = $k;
	if($i !== null){
		return array(
			"oldest"=>$p[0],
			"older"=>$p[ !$i ? $i : $i-1],		
			"current"=>$id,
			"newer"=>$p[ $i < ($last = count($p)-1)? $i+1 : $last],
			"newest"=>$p[$last]
		);
	}else
		return false;
}
function get_webcomic_ids($id=null){
	$r = array();
	foreach(get_webcomics_data($id) as $k => $v) $r[] = $v->ID;
	return $r;
}
function get_webcomics_data($id=null, $storyline = "", $collection = "", $order = "ASC"){
	$id = $id ? $id : get_the_ID();
	if(!$id) return false;
	if(!$storyline){
		$storyline = array_shift(get_the_terms($id, "webcomic_storyline"));
		$storyline = $storyline->slug;
	}
	if(!$collection){
		$collection = array_shift(get_the_terms($id, "webcomic_collection"));
		$collection = $collection->slug;
	}
	$q = new WP_Query(
		Array(
			'post_type'=>"webcomic_post",
			'post_status' => "publish",
			'posts_per_page' => -1,
			'order' => $order,
			'webcomic_storyline'=> $storyline,
			'webcomic_collection'=> $collection
		)
	);
	return $q->posts;
}
function get_webcomic_tax($term, $id=null, $default=null){
	global $webcomic;
	$tax = false;
	if($id)
		$tax = array_shift(get_the_terms($id, "webcomic_$term"));
	elseif($post->post_type == "webcomic_post" )
		$tax = array_shift(get_the_terms($post->ID, "webcomic_$term"));
	//elseif(
		$tax = array_shift(get_terms( "webcomic_$term" ));
	return($tax);
}
function webcomic_taxs($tax, $group=false, $id=false){
	global $webcomic, $wp_query;
	$data = $webcomic->options['term_meta'][$tax];
	$t = "webcomic_$tax";
	$r = array();
	if($id)
		$r[] = get_term($id,$tax);
	elseif($tax == "collection")
		foreach($data as $i=>$v)
			$r[] = get_term($i,$t);	
	elseif($tax == "storyline")
		foreach($data as $i=>$v)
			if(!$group || ($group == $v['group']))
				$r[] = get_term($i,$t);
	
	return $r;
}
function comic_nav(){
	if( is_singular("webcomic_post") || is_archive() || is_home() ){
		global $post, $webcomic, $inkblot;
		$c_label = "Comics";
		$s_label = "Issues";
		$p_label = "Pages";
		$c = "collection";
		$s = "storyline";
		$r = array();
		$wrap = "<li>%s</li>";
		if(is_archive()){
			if($term = get_query_var("webcomic_$c")){
				//If its a collection, make the query the selected term
				//And list all the Storylines as Issues
				$collection = get_term_by('slug', $term, "webcomic_$c");
				$r[] = my_list($c_label, $collection, webcomic_taxs($c));
				$r[] = my_list($s_label, null, webcomic_taxs($s, $collection->term_id));
			}elseif($term = get_query_var("webcomic_$s")){
				//If
				$storyline = get_term_by('slug', $term, "webcomic_$s");
				$collection = get_term($storyline->term_group, "webcomic_$c");
				$r[] = my_list($c_label, $collection, webcomic_taxs($c));
				$r[] = my_list($s_label, $storyline, webcomic_taxs($s, $storyline->term_group));
			}else{
				$r[] = my_list($c_label, null, webcomic_taxs($c));
			}
		}elseif(is_singular("webcomic_post")){
			$collection = current(get_the_terms($post->ID, "webcomic_$c"));
			$storyline = current(get_the_terms($post->ID, "webcomic_$s"));
			$r[] = my_list($c_label, $collection, webcomic_taxs($c));
			$r[] = my_list($s_label, $storyline, webcomic_taxs($s, $storyline->term_group));
			$r[] = my_list($p_label, $post, get_webcomics_data(), false);
		}else{
			print_r($inkblot);
			if($inkblot->home_webcomic_toggle){
				print("blah");
				$collection = get_term($inkblog->home_webcomic_collection, "webcomic_$c");
				if(!empty($collection))
					$r[] = my_list($c_label, $collection, webcomic_taxs($c));
				$storyline = get_term($inkblog->home_webcomic_storyline, "webcomic_$s");
				if(!empty($storyline))
					$r[] = my_list($s_label, $storyline, webcomic_taxs($s, $storyline->term_group));
			}
		}
		foreach($r as $k => $v){
			$r[$k] = sprintf($wrap, $v);
		}
		return implode("\n",$r);
	}else{
		return false;
	}
}
function my_list($label, $root, $data, $exclude_self=true){
	
	if(!count($data)){ 
		return false;
	}else{
		$beforeGroup = "<div>";
		$afterGroup = "</div>";
		$groupTag = "ul";
		$groupClass = "";
		$itemTag = "li";
		$itemClass = "";
		$curClass = "cur";
		$first = current($data);
		$using_label = true;
		$term = (isset($first->term_id)) ? true : false;

		$a = '<a href="%2$s">%1$s</a>';
		if($root){
			$label = ($term) ? $root->name : $root->post_title;
			$using_label = false;
		}
		foreach($data as $i){
			if(!$root){
				$match = false;
			}else{
				$match = $term 
				? ($root->term_id == $i->term_id ? true : false) 
				: ($root->ID == $i->ID ? true : false);
			}
			$class='';
			if($match || $itemClass){
				$class = array();
				if($itemClass) $class[] = $itemClass;
				if($match && !$using_label) $class[] = $curClass;
				$class = sprintf(' class="%s"', implode(" ",$class));
			}
			if(!$exclude_self || !($match && $exclude_self)){
				$link = $term 
					? get_term_link($i) 
					: get_permalink($i->ID);
				$text = $term 
					? $i->name 
					: $i->post_title;
				$r.= sprintf(
					'<'.$itemTag.'%s>%s</'.$itemTag.'>',
					$class,
					sprintf($a, $text, $link)
				);
			}
		}
		$groupClass = $groupClass ? " class='$groupClass'" : "";
		$r = $beforeGroup.sprintf("<$groupTag$groupClass>".'%s'."</$groupTag>", $r).$afterGroup;

		return sprintf($a, $label, (!$using_label? get_term_link($root) : "")).$r;	
	}
}
?>
