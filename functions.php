<?php

include('css/slider.php');

add_theme_support('menus');
add_theme_support('post-thumbnails');
add_theme_support('widgets');

global $addthis;
if (get_option('show_sharing_controls') != 'hide') $addthis = '<!-- Go to www.addthis.com/dashboard to customize your tools -->
<div class="'.get_option('show_sharing_controls').'"></div>';

register_nav_menus( array(
	'Main Navigation' => 'Top navigation menu',
) );

function featured_zone($id = false, $title = false) {
	if (!$id) $attachment = get_post_thumbnail_id();
	else $attachment = get_post_thumbnail_id($id);
	$featured_url = wp_get_attachment_url($attachment);

	?>
	<div class="featured-area" style="background-image: url(<?php echo $featured_url; ?>);">
		<h1 class="<?php echo $pb ?: 'plain-title'; ?>"><?php if (!$title) the_title(); else echo $title; ?></h1>
	</div>
	<?php
}

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
	//wp_enqueue_style('slider-php', get_bloginfo('wpurl') . '?slider=css');
	wp_enqueue_script(
		'custom-script',
		get_stylesheet_directory_uri() . '/js/slider.js',
		array( 'jquery' )
	);
	wp_enqueue_script(
		'masonry',
		get_stylesheet_directory_uri() . '/js/masonry.pkgd.min.js',
		array( 'jquery' )
	);
}
add_action('wp_enqueue_scripts', 'gallery_enqueue_styles');

function slider_images() {
	$images = get_images(1);
	$index = 0;
	foreach($images as $image) {
		?>
		<div style="background-image:url(<?php echo $image->guid; ?>);" data-slide="<?php echo $index; ?>" class="image-slide <?php echo $index==0?'active':''; ?>">
			<div class="image-details">
				<h3></h3>
				<p></p>
			</div>
		</div>
		<?php
		$index++;
	}
	
}

function get_images($home = false) {
	$meta = array(
		array(
			'key'=>'_pubgallery',
			'value'=>'on'
		)
	);
	if ($home) {
		$meta[] = array(
			'key'=>'_onhome',
			'value'=>'on'
		);
	}
	$images = get_posts(array('post_type'=>'attachment', 'posts_per_page'=>-1, 'meta_query'=>$meta));
	return $images;
}

function theme_slug_widgets_init() {
	$pages = get_posts(array('post_type'=>'page', 'posts_per_page'=>-1, 'post_status'=>'publish'));
	foreach ($pages as $page) {
	    register_sidebar( array(
	        'name' => __( $page->post_title, 'gallery-edge' ),
	        'id' => $page->post_name,
	        'description' => __( 'Widgets in this area will appear on the '.$page->post_title.' page.', 'gallery-edge' ),
	        'before_title' => '<h3>',
	        'after_title' => '</h3>',
	    ) );
    }
}
add_action( 'widgets_init', 'theme_slug_widgets_init' );

function print_gallery_page() {
	wp_dropdown_pages(array('name'=>'gallery_page','selected'=>get_option('gallery_page')));
}

function print_show_sharing() {
	tool_dropdown('show_sharing_controls', array(
		'hide'=>'None',
		'addthis_native_toolbox'=>'Original Sharing Buttons',
		'addthis_sharing_toolbox'=>'Sharing Buttons</option>'
	), get_option('show_sharing_controls'));
}

function tool_dropdown($name, $values, $selected = '') {
	echo '<select name="'.$name.'" id="'.$name.'">';
	foreach ($values as $key=>$value) {
		echo '<option value="'.$key.'" '.($key == $selected ? 'selected':'').'>'.$value.'</option>';
	}
	echo '</select>';
}

function print_admin_name() {
	echo '<input type="text" name="admin_name" value="'.get_option('admin_name').'" />';
}

function register_settings() {
	register_setting('reading', 'gallery_page', 'esc_attr');
    add_settings_field('gallery_page', '<label for="gallery_page">'.__('Gallery Page' , 'gallery_page' ).'</label>' , 'print_gallery_page', 'reading');
    register_setting('general', 'admin_name', 'esc_attr');
    add_settings_field('admin_name', '<label for="admin_name">'.__('Full Name', 'admin_name').'</label>', 'print_admin_name', 'general');
    register_setting('reading', 'show_sharing_controls', 'esc_attr');
    add_settings_field('show_sharing_controls', '<label for="show_sharing_controls">'.__('Sharing Controls', 'show_sharing_controls').'</label>','print_show_sharing', 'reading');
}
add_action('admin_init', 'register_settings');

function gallery_content($content) {
	global $post;
	global $addthis;
	$id = $post->ID;
	if ($id == get_option('gallery_page')) {
		$content = '';
		$images = get_images();
		$content .= '<div class="tile-container js-masonry">';
		foreach($images as $image) {
			//$data = get_metadata('post', $image->ID, "_wp_attachment_metadata", true);
			$img = wp_get_attachment_image_src( $image->ID, 'large' );
			if ($img[1] > $img[2]*1.3) $class='w2';
			if ($img[1] > $img[2]*1.7) $class='w3';
			$content .= '<div class="item '.$class.'">';
				$content .= '<img src="' . $img[0] . '" />';
				$content .= '<div class="metadata">';
				$content .= '<h2>'.$image->post_title.'</h2>';
				$content .= '</div>';
			$content .= '</div>';
		}
		$content .= '</div>';
	}
	if (is_page() || is_single()) $content .= $addthis;
	return $content;
}
add_filter('the_content', 'gallery_content');