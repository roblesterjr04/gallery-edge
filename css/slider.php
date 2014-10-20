<?php

header("Content-type: text/css; charset: UTF-8");

function build_slider() {
	$images = get_images();
	$count = count($images);
	
	for ($i = 1; $i <= $count; $i++) {
		$image = $images[$i-1];
		?>
		.image-container.start #image<?php echo $image->ID; ?> {
			-webkit-transition-property: opacity;
			-webkit-transition-duration: 2s;
			-webkit-transition-delay: <?php echo $i*3; ?>s;
			
		}
		
		
		
		<?php
	}
	
	?>
	.image-container.start .image-slide {
			opacity: 1;
		}
	<?php
	
	exit;
}

function slider_init() {
	if ($_GET['slider']=='css') {
		build_slider();
	}
}
add_action('init', 'slider_init');