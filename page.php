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
	echo '<div class="plain-right">';
	if (!is_single()) dynamic_sidebar($post->post_name);
	dynamic_sidebar('page_right');
	echo '</div>';
	echo '</div>';


?>
<?php endwhile; ?>
<?php else : ?>

<p>Sorry, nothing to see.</p>

<?php endif; ?>
</div>


</div>

<?php get_footer(); ?>