<!DOCTYPE>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php wp_head(); ?>
<script>var whost = '<?php bloginfo('wpurl'); ?>';</script>
</head>
<body class="<?php echo is_front_page() ? 'homepage' : 'page'; ?>">
<div class="top-nav fadein">
<div class="tag-line-box">
			<a href="<?php bloginfo('wpurl'); ?>"><h1><img src="<?php echo get_option('gallery_logo') ?>" class="site-logo" /><?php bloginfo('name'); ?></h1></a>
			<p><?php bloginfo('description'); ?></p>
		</div>
	<?php wp_nav_menu( array('menu' => 'Main Navigation' )); ?>
</div>