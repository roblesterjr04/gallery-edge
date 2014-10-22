<!DOCTYPE>
<html>
<head>
<?php wp_head(); ?>
</head>
<body class="<?php echo is_front_page() ? 'homepage' : 'page'; ?>">
<div class="top-nav fadein">
<div class="tag-line-box">
			<a href="<?php bloginfo('wpurl'); ?>"><h1><img src="http://localhost:8888/aljohnson/wp-content/uploads/2014/10/ajlogo.png" class="site-logo" /><?php bloginfo('name'); ?></h1></a>
			<p><?php bloginfo('description'); ?></p>
		</div>
	<?php wp_nav_menu( array('menu' => 'Main Navigation' )); ?>
</div>