<?php
	$sidebar = "inkblot-footer-columns";
	$widgets = get_option('sidebars_widgets');
?>
	<!--.foot-->
	<footer class="foot">
		<div>
			<ul class='cols _auto <?php echo "_".count($widgets[$sidebar]);?> '>
				<?php dynamic_sidebar("$sidebar")?>
			</ul>
			<hr>
			<ul class='copy'>
				<?php dynamic_sidebar( 'inkblot-footer-span' ); echo "&copy ".date('Y');?>
			</ul>
		</div>
	</footer>
	<!--.foot-->
	<?php wp_footer(); ?>

	</body>
</html>