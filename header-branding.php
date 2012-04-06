<!--.branding-->
		<div class='branding'>
			<div>
				<a href='<?php echo  home_url("/");?>' rel='home'><?php 
		if( $img = get_header_image()){
			?><img src='<?php echo $img;?>' alt='<?php bloginfo("description");?>'/><?php 
		}else{
			?><h1><b><?php bloginfo( 'name' ); ?></b></h1><h2><?php bloginfo('description'); ?></h2><?php
		}?></a>
			</div>
		</div>
<!--.branding end-->
