<? 
//Bail if no sidebars
if(	! is_active_sidebar("sidebar1") &&
	! is_active_sidebar("sidebar2")){
		echo "sidebars not active";
	return;
	}
$sidebar = array('<aside id="%1$s" class="%2$s" role="complementary">','</aside>');
?>
			<div class='sidebars'>
				<?php if(is_active_sidebar($s = "sidebar1")){
					printf($sidebar[0], $s, 'sidebar l'); 
					dynamic_sidebar($s); 
					print($sidebar[1]);
				}else echo "<!--$s is not active-->";?>
				<?php if(is_active_sidebar($s = "sidebar2")){
					printf($sidebar[0], $s, 'sidebar r'); 
					dynamic_sidebar($s); 
					print($sidebar[1]);
				}else echo "<!--$s is not active-->";?>
			</div>
