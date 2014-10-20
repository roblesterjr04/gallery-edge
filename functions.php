<?php

include('css/slider.php');

add_theme_support('menus');

register_nav_menus( array(
	'Main Navigation' => 'Top navigation menu',
) );

/* For adding custom field to gallery popup */
function add_image_attachment_fields_to_edit($form_fields, $post) {
  // $form_fields is a an array of fields to include in the attachment form
  // $post is nothing but attachment record in the database
  //     $post->post_type == 'attachment'
  // attachments are considered as posts in WordPress. So value of post_type in wp_posts table will be attachment
  // now add our custom field to the $form_fields array
  // input type="text" name/id="attachments[$attachment->ID][custom1]"
  $credit = get_post_meta($post->ID, "_pubgallery", true);
  $home = get_post_meta($post->ID, "_onhome", true);
  $credit = $credit=='on' ? 'checked' : '';
  $home = $home=="on" ? 'checked' : '';
  $form_fields["pubgallery"] = array(
    "label" => __("Plublish to gallery"),
    "input" => "html",
    "html"=>"
    <input type=\"hidden\" name=\"attachments[{$post->ID}][pubgallery]\" value=\"off\" />
    <input type=\"checkbox\" name=\"attachments[{$post->ID}][pubgallery]\" $credit />
    
    "
  );
  $form_fields["onhome"] = array(
    "label" => __("Use as home page image"),
    "input" => "html",
    "html"=>"
    <input type=\"hidden\" name=\"attachments[{$post->ID}][onhome]\" value=\"off\" />
    <input type=\"checkbox\" name=\"attachments[{$post->ID}][onhome]\" $home />
    
    "
  );
   return $form_fields;
}
// now attach our function to the hook
add_filter("attachment_fields_to_edit", "add_image_attachment_fields_to_edit", null, 2);

function add_image_attachment_fields_to_save($post, $attachment) {
  // $attachment part of the form $_POST ($_POST[attachments][postID])
        // $post['post_type'] == 'attachment'
  if( isset($attachment['pubgallery']) ){
    // update_post_meta(postID, meta_key, meta_value);
    update_post_meta($post['ID'], '_pubgallery', $attachment['pubgallery']);
    update_post_meta($post['ID'], '_onhome', $attachment['onhome']);
  }
  return $post;
}
// now attach our function to the hook.
add_filter("attachment_fields_to_save", "add_image_attachment_fields_to_save", null , 2);

function gallery_enqueue_styles() {
	wp_enqueue_style('styles', get_stylesheet_uri() );
	wp_enqueue_style('slider-php', get_bloginfo('wpurl') . '?slider=css');
	wp_enqueue_script(
		'custom-script',
		get_stylesheet_directory_uri() . '/js/slider.js',
		array( 'jquery' )
	);
}
add_action('wp_enqueue_scripts', 'gallery_enqueue_styles');

function slider_images() {
	$images = get_images();
	?>
	<div style="background-color: white;" class="start-slide"></div>
	<?php
	foreach($images as $image) {
		?>
		<div style="background-image:url(<?php echo $image->guid; ?>);" id="image<?php echo $image->ID; ?>" class="image-slide"></div>
		<?php
	}
	
}

function get_images() {
	$meta = array(
		array(
			'key'=>'_onhome',
			'value'=>'on'
		),
		array(
			'key'=>'_pubgallery',
			'value'=>'on'
		)
	);
	$images = get_posts(array('post_type'=>'attachment', 'posts_per_page'=>-1, 'meta_query'=>$meta));
	return $images;
}