<?php
/* 

	
	Customizing - The Stuff I (rusty) wrote 


 */
function my_init(){
	//Add favicon
	add_action('admin_head', 'favicon');
	add_action('wp_head', 'favicon');

	//Scripts to enqueue
	wp_enqueue_script( 'my-scripts',  get_bloginfo("template_directory"). '/js/scripts.js', array( 'jquery' ) );
	wp_enqueue_script( 'jquery-easing',  get_bloginfo("template_directory"). '/js/easing.js', array( 'jquery' ) );
	wp_enqueue_script( 'jquery-touch-punch',  get_bloginfo("template_directory"). '/js/touch-punch.js', array( 'jquery' ) );

	// embed the javascript file that makes the AJAX request
	wp_enqueue_script( 'ajax-nav-request', get_bloginfo("template_directory")  . '/js/ajax.js', array( 'jquery' ) );
	wp_localize_script( 'ajax-nav-request', 'MyAjax', array('url' => admin_url( 'admin-ajax.php' )));

	// if both logged in and not logged in users can send this AJAX request,
	// add both of these actions, otherwise add only the appropriate one
	add_action( 'wp_ajax_nopriv_ajax-nav', 'ajax_nav' );
	add_action( 'wp_ajax_ajax-nav', 'ajax_nav' );
	
	//Add social fields to the User Profile
	add_action( 'show_user_profile', 'social_profile_fields' );
	add_action( 'edit_user_profile', 'social_profile_fields' );

	add_action( 'personal_options_update', 'save_social_profile_fields' );
	add_action( 'edit_user_profile_update', 'save_social_profile_fields' );

}

function favicon(){
	echo '<link rel="Shortcut Icon" type="image/png" href="'.get_bloginfo('stylesheet_directory').'/images/favicon.png" />';
}
/*Tags*/
function get_webcomic_permalink(){
	$wc = get_webcomic_post();
	return $wc->guid;
}
function the_webcomic_link($format = "%link", $link='%label'){
	global $webcomic, $id;
	$link = '<a href="%s" rel="permalink" class="permalink-webcomic-link">'.$label.'</a>';
	echo sprintf($link, get_webcomic_permalink());
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
function pages_view(){
	global $webcomic, $id;
	?><div class='webcomics-pages'><?php
	print_r($webcomic->get_the_webcomic_archive("group=storyline&&image=small&group_image=medium"));
	?></div><?php
}

function random_backgroundImages_fromDir(
	$count=50,
	$x0=0,
	$x1=100,
	$xUnit="%",
	$y0=0,
	$y1=100,
	$yUnit="%",
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
		$areaX=array($x0,$x1,$xUnit);
		$areaY=array($y0,$y1,$yUnit);
		
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
function nav(
	$data, 
	$id, 
	$itemWrap = "<li class='%s'>%s</li>",
	$linkWrap = "<a href='%s' rel='%s' title='%s' class='%s'>%s</a>",
	$labels = array("start","prev","bookmark","next","end")
){
	$i = 0;
	foreach($data as $k => $v) if($v->ID == $id) $i = $k;
	$last = $k;
	$r = array();
	foreach($labels as $k => $v){
		$d = $data[0];
		if($v == "start")	$d = $data[0];
		if($v == "prev") 	$d = $data[(!$i ? $i : $i-1)];
		if($v == "bookmark") 	$d = $data[$i];			
		if($v == "next")	$d = $data[($i<$last ? $i+1 : $i)];
		if($v == "end")	$d = $data[$last];
			
		$item = sprintf($linkWrap, 
			$d->guid,
			strtolower($v),
			ucwords($v.": ".$d->post_title),
			$v,
			ucwords($d->post_title)
		);
		$r[$v] = sprintf($itemWrap, $v, $item);
	}
	return $r;
}


function ajax_nav($id = null, $storyline = "", $collection = "") { 
	// get the submitted parameters
	global $webcomic;
	$r = array();
	if(isset($_POST['id'])) $id =  $_POST['id'];
	if(isset($_POST['storyline'])) $storyline =  $_POST['storyline'];
	if(isset($_POST['collection'])) $id =  $_POST['collection'];

	$data = get_webcomics_data($id,$storyline,$collection);
	foreach($data as $k => $v)
		$r[] = $v;
	make_json($r);
}
function make_json($data){
	header( "Content-Type: application/json" );
	echo json_encode($data);
	// IMPORTANT: don't forget to "exit"
	exit;
}
function ajax_the_id($id=null){
	$id = $id ? $id : get_the_ID();
	?>
	<script>
	/*<![CDATA[ */
		MyAjax.id = <?php echo $id;?>;
	/* ]]>*/
	</script>
<?
}
function get_webcomics_data($id=null, $storyline = "", $collection = "", $order = "ASC"){
	$id = $id ? $id : get_the_ID();
	if(!$id) return false;
	
	if(!$storyline){
		$storyline = current(get_the_terms($id, "webcomic_storyline"));
		$storyline = $storyline->slug;
	}
	if(!$collection){
		$collection = current(get_the_terms($id, "webcomic_collection"));
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
function webcomic_tax($tax, $group=false, $id=false){
	global $webcomic, $wp_query;
	$data = $webcomic->options['term_meta'][$tax];
	$t = "webcomic_$tax";
	$r = array();
	if(is_object($group)) $group = $group->term_group;
	if($id){
		$r[] = get_term($id,$tax);
	}else{
		$terms = array();
		foreach(get_terms($t) as $v) $terms[$v->term_id] = $v;
		foreach($data as $k => $v){
			if(!$group){
				$r[] = $terms[$k];
			}elseif($group == $v['group']){
				$r[] = $terms[$k];
			}
		}
	}
	return $r;
}
function webcomic_tax_index($tax_array, $tax){
	if($tax === null 
	|| $tax === false 
	|| $tax === "") 
	return false;
	if(is_object($tax)) $id = $tax->term_id;
	else $id = $tax;
	foreach($tax_array as $k => $v)
		if($id == $v->term_id)
			return $k;
}
function comic_nav(
	$labels = array(
		"collection" => "Comics",
		"storyline"=> "Issues",
		"post" => "Pages"
	),
	$wrap = "<li>%s</li>"
){
	if( is_singular("webcomic_post") || is_archive() || is_home() ){
		global $post, $webcomic, $inkblot;
		$c = "collection";
		$s = "storyline";
		$r = array();
		if(is_archive()){
			if($tax = get_query_var($t = "webcomic_$c")){
				$collections = webcomic_tax($c);
				$collection = get_term_by('slug', $tax, $t);
				
				$storylines = webcomic_tax($s, $collection->term_id);
				$storyline = null;
				$storyline_label = $labels[s];
				
				//	__list($data,$id = null,$label = "")
				$r[] = __list($collections);
				$r[] = __list($storylines, $storyline, $storyline_label);
			}elseif($tax = get_query_var($t = "webcomic_$s")){
				$storyline = get_term_by('slug', $tax, $t);
				$collection = get_term($storyline->term_group, "webcomic_$s");
				$r[] = __list(webcomic_tax($c), $collection);
				$r[] = __list(webcomic_tax($s, $storyline->term_group), $storyline);
			}else{
				$r[] = __list(webcomic_tax($c), null, $labels[$c]);
			}
		}elseif(is_singular("webcomic_post")){
			$collection = current(get_the_terms($post->ID, "webcomic_$c"));
			$r[] = __list(webcomic_tax($c), $collection);

			$storyline = current(get_the_terms($post->ID, "webcomic_$s"));
			$r[] = __list(webcomic_tax($s, $storyline), $storyline);

			$data = get_webcomics_data($post->ID, $storyline->slug, $collection->slug);
			$r[] = __list($data, $post);
		}elseif(is_home()){
			if($inkblot->options["home_webcomic_toggle"]){
				if($inkblot->options["home_webcomic_$c"])
					$collection = get_term($inkblot->options["home_webcomic_$c"], "webcomic_$c");
				else
					$collection = false;
				$collection_tax = webcomic_tax($c);	
				
				if($inkblot->options["home_webcomic_$s"])
					$storyline = get_term($inkblot->options["home_webcomic_$s"], "webcomic_$s");
				else
					$storyline = false;
				$storyline_tax = webcomic_tax($s, $storyline);
				
				$r[] = __list($collection_tax, $collection, (!$collection ? $labels[$c]: false));
				$r[] = __list($storyline_tax, $storyline, (!$storyline ? $labels[$s]: false));
			}
		}
		foreach($r as $k => $v)
			$r[$k] = sprintf($wrap, $v);
		return implode("", $r);
	}else{
		return false;
	}
}
function __link($data = null){
	if(!$data)
		return get_permalink();
	elseif(isset( $data->term_id )) 
		return get_term_link($data);
	elseif(isset($data->ID)) 
		return get_permalink($data->ID);
	else 
		return get_permalink($data);
}
function __title($data = null){
	if(	!$data)
		return get_the_title();
	elseif(	isset($data->name)) 
		return $data->name;
	elseif(	isset($data->post_title)) 
		return $data->post_title;
	else 
		return get_the_title($data);
}

function __list(
	$data,
	$id = null,
	$label = "",
	$beforeLabel = "",
	$afterLabel = "",
	$beforeGroup = "<div class='submenu'>",
	$afterGroup = "</div>",
	$groupWrap = "<ul>%s</ul>",
	$curItemClass = "cur",
	$beforeItem = "<li%s>",
	$afterItem = "</li>",
	$itemWrap = "<a href='%s'>%s</a>"
){
	if(is_object($id)) $root = $id;
	else $root = $id !== null && $id !== false
		? $data[$id]
		: current($data);
	$tax = isset($root->term_id) 
		? true
		: false;
	$rootLabel =($label 
		? $label 
		: ($tax ? $root->name : $root->post_title)
	);
	$items = array();
	foreach($data as $i){
		$match = ($label) 
			? false 
			: $tax 
				? ($root->term_id == $i->term_id ? true : false)
				: ($root->ID == $i->ID ? true : false);
		
		if(!($match) || ($match && !$tax)){
			$items[] = 
				sprintf( $beforeItem, ($match && !$tax ? " class='$curItemClass'" : "")).
				sprintf( $itemWrap, __link($i), __title($i)).
				$afterItem;
		}
	}
	return	$beforeLabel.
		sprintf($itemWrap, __link($root), $rootLabel).
		$afterLabel.
		(count($items)?
		$beforeGroup.
		sprintf($groupWrap, implode("", $items)).
		$afterGroup:"");
}
function __log(){
	$r = array();
	foreach(func_get_args() as $v)
		$r[] = json_encode($v);
	printf("\n<script>if(console) console.log(%s)</script>\n", implode(",", $r));
}
my_init();



function social_profile_fields( $user ) { 
?>
	<h3>Social Profiles</h3>
	<table class="form-table">
<?php 	foreach(array(
		"twitter", 
		"facebook", 
		"tumblr"
	) as $p){?>
		<tr>
			<th><label for="<?php echo $p;?>"><?php echo ucwords($p);?></label></th>
			<td>
				<input type="text" name="<?php echo $p;?>" id="<?php echo $p;?>" value="<?php echo esc_attr( get_the_author_meta( $p, $user->ID ) ); ?>" class="regular-text" /><br />
				<span class="description"><?php 
					if($p == "twitter") echo "Please enter your Twitter username.";
					else echo "Please enter your ".ucwords($p)." url";
				?></span>
			</td>
		</tr>
<?php 	}?>
	</table>
<?php }

function save_social_profile_fields( $user_id ) {
	if ( !current_user_can( 'edit_user', $user_id ) )
		return false;
	foreach(array("twitter", "facebook", "tumblr") as $v)
		update_usermeta( $user_id, $v, $_POST[$v] );
}

?>
