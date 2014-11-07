<?php

include('css/slider.php');
include('Mobile_Detect.php');


function add_theme_support_items() {
	add_theme_support('menus');
	add_theme_support('post-thumbnails');
	add_theme_support('post-formats', array('quote', 'image', 'video'));
	add_theme_support('widgets');
}
add_action('after_setup_theme', 'add_theme_support_items');

global $addthis;
if (get_option('show_sharing_controls') != 'hide') $addthis = '<!-- Go to www.addthis.com/dashboard to customize your tools -->
<div class="'.get_option('show_sharing_controls').' theme-addthis"></div>';

register_nav_menus( array(
	'Main Navigation' => 'Top navigation menu',
) );

function do_widget_shortcode($content) {
	return do_shortcode($content);
}
add_filter('widget_text', 'do_widget_shortcode');

function featured_zone($id = false, $title = false) {
	if (!$id) $attachment = get_post_thumbnail_id();
	else $attachment = get_post_thumbnail_id($id);
	$detect = new Mobile_Detect;
	$featured = wp_get_attachment_image_src($attachment, $detect->isMobile() ? 'medium' : 'large');
	?>
	<div class="featured-area">
		<style>body { background-image: url(<?php echo $featured[0]; ?>); background-color: rgb(10,10,10); }</style>
		<h1 class="<?php echo $pb ?: 'plain-title'; ?>"><?php if (!$title) the_title(); else echo $title; ?></h1>
	</div>
	<?php
}

function galleryView($id) {
	
	$post = get_post($id);
	$image = gallery_get_image($id);
	$type = $post->post_type;
	?>
	<div class="image"><img src="<?php echo $image; ?>" /></div>
	<div class="title"><h1><?php echo $post->post_title; ?></h1></div>
	<div class="close dashicons dashicons-no-alt"></div>
	<?php
		if ($type == 'attachment') {
			//echo '<div class="buy"><h2><a href="#">Purchase</a></h2></div>';
		}
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
	wp_enqueue_style('styles', get_stylesheet_uri(), array(), time() );
	wp_enqueue_style('dashicons');
	wp_enqueue_script(
		'custom-script',
		get_stylesheet_directory_uri() . '/js/slider.js',
		array( 'jquery' ),
		time(),
		true
	);
	wp_enqueue_script(
		'masonry',
		get_stylesheet_directory_uri() . '/js/masonry.pkgd.min.js',
		array( 'jquery' ),
		time(),
		true
	);
}
add_action('wp_enqueue_scripts', 'gallery_enqueue_styles');

function slider_images() {
	$images = get_images(1);
	$index = 0;
	$detect = new Mobile_Detect;
	
	foreach($images as $image) {
		$featured = wp_get_attachment_image_src($image->ID, $detect->isMobile() ? 'medium' : 'large');
		?>
		<div style="background-image:url(<?php echo $featured[0]; ?>);" data-slide="<?php echo $index; ?>" class="image-slide <?php echo $index==0?'active':''; ?>">
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
    register_sidebar( array(
        'name' => __( 'Main Sidebar', 'gallery-edge' ),
        'id' => 'page_right',
        'description' => __( 'Widgets in this area will be shown on all posts and pages.', 'gallery-edge' ),
        'before_title' => '<h3>',
        'after_title' => '</h3>',
    ) );
}
add_action( 'widgets_init', 'theme_slug_widgets_init' );

function print_gallery_logo() {
	echo '<input type="text" value="'.get_option('gallery_logo').'" name="gallery_logo" />';
}

function print_show_sharing() {
	tool_dropdown('show_sharing_controls', array(
		'hide'=>'None',
		'addthis_native_toolbox'=>'Original Sharing Buttons',
		'addthis_sharing_toolbox'=>'Sharing Buttons</option>',
		'addthis_responsive_sharing'=>'Responsive Buttons</option>'
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

function print_gallery_page() {
	wp_dropdown_pages( array( 'name'=>'gallery_page', 'selected'=>get_option('gallery_page') ) ); 
}

function register_settings() {
	register_setting('reading', 'gallery_page', 'esc_attr');
	add_settings_field('gallery_page', '<label for="gallery_page">'.__('Gallery Page', 'gallery_page').'</label>', 'print_gallery_page', 'reading');
	register_setting('general', 'gallery_logo', 'esc_attr');
    add_settings_field('gallery_logo', '<label for="gallery_logo">'.__('Gallery Logo Url' , 'gallery_logo' ).'</label>' , 'print_gallery_logo', 'general');
    register_setting('general', 'admin_name', 'esc_attr');
    add_settings_field('admin_name', '<label for="admin_name">'.__('Full Name', 'admin_name').'</label>', 'print_admin_name', 'general');
    register_setting('reading', 'show_sharing_controls', 'esc_attr');
    add_settings_field('show_sharing_controls', '<label for="show_sharing_controls">'.__('Sharing Controls', 'show_sharing_controls').'</label>','print_show_sharing', 'reading');
}
add_action('admin_init', 'register_settings');

function gallery_get_image($id) {
	$upload_dir = wp_upload_dir();
	$image = get_post_meta($id, '_sell_media_attached_file', true);
	if (!$image) {
		$image = wp_get_attachment_image_src($id, 'large');
		return $image[0];
	}
	return $upload_dir['baseurl'] . '/' . $image;
}

function gallery_content($content) {
	global $post;
	global $addthis;
	$detect = new Mobile_Detect;
	if ($post->ID == get_option('gallery_page')) {
		$images = get_images();
		$content .= '<div id="galleryContainer">';
		foreach ($images as $image) {
			$content .= '<div class="galleryImage" data-id="'.$image->ID.'">';
			$featured = wp_get_attachment_image_src($image->ID, 'large');
			$content .= '<div class="imageInner">';
			$content .= '<div class="imageData"><p>'.$image->post_title.'</p></div>';
			$content .= '<div class="image"><img src="'.$featured[0].'" /></div>';
			$content .= '</div>';
			$content .= '</div>';
		}
		$content .= '</div>';
	}
	if ((is_single() || is_page()) && !$detect->isMobile()) $content = $addthis . $content;
	return $content;
}
add_filter('the_content', 'gallery_content', 10, 1);

function check_for_gallery() {
	if ($_POST['gallery']) {
		galleryView($_POST['gallery']);
		exit;
	}
}
add_action('init', 'check_for_gallery');