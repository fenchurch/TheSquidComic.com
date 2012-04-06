<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
<?php wp_head(); /* see functions.php hook_wp_head_0 */?>
<?php

?>
<style>
	body{
		font-family:"Helvetica Neue", Helvetica, Arial, Sans Serif;
	}
	body:after{
		<?php random_backgroundImages_fromDir(); //Random generated ?>
	}
	.branding:after{
		<?php random_backgroundImages_fromDir(); //Random generated ?>
	}
	.body>div{
		background:#fff;
	}
</style>
</head>
<body id='body' <?php body_class(); ?>>
