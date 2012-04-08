<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
<?php wp_head(); /* see functions.php hook_wp_head_0 */?>
<?php

?>
<style>
	body:after{
		<?php random_backgroundImages_fromDir(); //Random generated bubbles?>
	}
	.branding:after{
		<?php random_backgroundImages_fromDir(); //Random generated bubbles?>
	}
</style>
</head>
<body id='body' <?php body_class(); ?>>
