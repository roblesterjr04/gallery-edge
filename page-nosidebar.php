<?php 

/*
Template Name: Widgets appear on bottom
*/


include_once( ABSPATH . 'wp-admin/includes/plugin.php' ); $pb = is_plugin_active('wr-pagebuilder'); ?>
<?php get_header(); ?>

<div class="pagewrap">


<?php if (have_posts()) : while (have_posts()) : the_post(); 

featured_zone(); 

?><div class="content"><?php
the_title('<h1>','</h1>');
	
$class = get_option('gallery_page') == get_the_ID() ? 'gallery' : '';

	echo '<div class="plain-content '.$class.'">';
	echo '<div class="plain-left">';
	the_content(); 
	echo '</div>';
	echo '</div>';


?>
<?php endwhile; ?>
<?php else : ?>

<p>Sorry, nothing to see.</p>

<?php endif; ?>
<div class="bottombar"><?php dynamic_sidebar('page_right') ?></div>
</div>

</div>

<?php get_footer(); ?>