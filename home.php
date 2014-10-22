<?php include_once( ABSPATH . 'wp-admin/includes/plugin.php' ); $pb = is_plugin_active('wr-pagebuilder'); ?>
<?php get_header(); ?>

<div class="pagewrap">

<div class="content">
<?php 
	$pageid = get_option( 'page_for_posts' );
	$page_object = get_post($pageid);
	$title = $page_object->post_title;
	
	featured_zone($pageid, $title);

?>


<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
<div class="plain-content">
<a href="<?php the_permalink(); ?>"><h2><?php the_title(); ?></h2></a>
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