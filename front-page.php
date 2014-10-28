<?php get_header(); ?>
<div class="homewrap">
<div class="image-container clear slider"><?php slider_images(); ?></div>
<div class="home-title fadeout">
	<h1><img src="<?php echo get_option('gallery_logo') ?>" class="site-logo" /><?php bloginfo('name'); ?></h1>
	<p><?php bloginfo('description'); ?></p>
</div>
</div>
<?php get_footer(); ?>