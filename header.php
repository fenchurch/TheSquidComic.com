<!DOCTYPE html>
<?php
	$home = home_url("/");
?>
<html <?php language_attributes(); ?>>
<head><?php wp_head(); /* see functions.php hook_wp_head_0 */?>
<style>
	body{
		font-family:"Helvetica Neue", Helvetica, Arial, Sans Serif
	}
	body::after{
	<?php 
		random_backgroundImages_fromDir(); //Random generated 
	?>
	}
	.body>div{
		background:#fff;
	}
</style>
</head>
<body id='body' <?php body_class(); ?>>
	<header class='head'>
		<div>
			<div class='l'>
				<ul>
					<li>
						<a href='<?php echo $home;?>' rel='home'>Home</a>
					</li>
				</ul>
				<?php wp_nav_menu("container=false"); echo "\n";?>
			</div>
			<hr>
		</div>
	</header>
	<div class='branding'>
		<div>
			<a href='<?php echo $home;?>' rel='home'>
			<?php if( $img = get_header_image()){?>
				<img src='<?php echo $img;?>'/>
			<?php }else{?>
			<?php }?>
			</a>	
		</div>
	</div>
	<div class='body'>
		<!--.body-->
		<div>
		.Body
