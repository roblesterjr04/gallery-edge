<?php include_once( ABSPATH . 'wp-admin/includes/plugin.php' ); $pb = is_plugin_active('wr-pagebuilder'); ?>
<?php get_header(); ?>

<div class="pagewrap">
<div class="tag-line-box">
			<a href="<?php bloginfo('wpurl'); ?>"><h1><img src="http://localhost:8888/aljohnson/wp-content/uploads/2014/10/ajlogo.png" class="site-logo" /><?php bloginfo('name'); ?></h1></a>
			<p><?php bloginfo('description'); ?></p>
		</div>

<div class="content">

<div class="featured-area">
<?php 
	$pageid = get_option( 'gallery_page' );
	$attachment = get_post_thumbnail_id($pageid);
	$featured_url = wp_get_attachment_url($attachment);
	$page_object = get_post($pageid);
	$title = $page_object->post_title;
?>
<img src="<?php echo $featured_url; ?>" class="featured-image" />
<h1 class="<?php echo $pb ?: 'plain-title'; ?>"><?php echo $title; ?></h1>
</div>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
<div class="plain-content">
<h2><?php the_title(); ?></h2>
<?php 

the_content();

?>
</div>
<?php endwhile; ?>
<?php else : ?>

<p>Sorry, nothing to see.</p>

<?php endif; ?>
</div>


</div>

<?php get_footer(); ?>