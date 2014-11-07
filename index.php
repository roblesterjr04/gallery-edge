<?php include_once( ABSPATH . 'wp-admin/includes/plugin.php' ); $pb = is_plugin_active('wr-pagebuilder'); ?>
<?php get_header(); ?>

<div class="pagewrap">
<!-- index.php -->

	<?php 
		$pageid = get_option( 'page_for_posts' );
		$page_object = get_post($pageid);
		$title = $page_object->post_title;
		
		featured_zone($pageid, $title);
		
	?>
	<div class="content">
		<h1><?php echo $title; ?></h1>
		<div class="plain-content">
			<div class="plain-left">
			
			<?php if (have_posts()) : while (have_posts()) : the_post();
				$format = get_post_format();
				if ($format) $format .= ' dashicons'; ?>
			<div class="post-item">
				<a href="<?php the_permalink(); ?>"><h2><span class="format-<?php echo $format; ?>"></span><?php the_title(); ?></h2></a>
				<p class="thedate"><?php the_date(); ?></p>
				<?php 
				
				the_content();
				
				?>
			</div>
			<?php endwhile; ?>
			<?php else : ?>
			
				<p>Sorry, nothing to see.</p>
			
			<?php endif; ?>
			</div>
			<div class="plain-right">
			<?php dynamic_sidebar($post->post_name);
				dynamic_sidebar('page_right'); ?>
			</div>
		</div>
	</div>


</div>

<?php get_footer(); ?>