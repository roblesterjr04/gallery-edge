<?php include_once( ABSPATH . 'wp-admin/includes/plugin.php' ); $pb = is_plugin_active('wr-pagebuilder'); ?>
<?php get_header(); ?>

<div class="pagewrap">

<div class="content">
<?php if (have_posts()) : while (have_posts()) : the_post(); 

featured_zone(); 

$class = get_option('gallery_page') == get_the_ID() ? 'gallery' : '';

	echo '<div class="plain-content '.$class.'">';
	the_content(); 
	echo '</div>';


?>
<?php endwhile; ?>
<?php else : ?>

<p>Sorry, nothing to see.</p>

<?php endif; ?>
</div>


</div>

<?php get_footer(); ?>