<div class="footer">
	<div class="bottom-nav"><?php wp_nav_menu( array('menu' => 'Main Navigation' )); ?></div>
	<div class="copyright">Copyright <?php echo get_option('admin_name') ?> <?php echo date('Y'); ?>. Web site by <a href="#">Robert Lester</a>.</div>
</div>
<div class="galleryViewContainer">
	<div class="place"></div>
	<img src="<?php echo get_stylesheet_directory_uri() . '/img/Preloader_4.gif' ?>" class="wait" />
</div>
<?php wp_footer(); ?>
</body>
</html>