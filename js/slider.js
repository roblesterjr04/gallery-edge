var curscroll = 0;

jQuery(function() {
	//jQuery('.image-container').removeClass('clear').addClass('start');
	jQuery('.fadein').addClass('doshow');
	jQuery('.fadeout').addClass('dohide');
	//jQuery('.tag-line-box').addClass('dropit');
	setTimeout(function() {
		setInterval(function(){slideTransition();}, 3000);
	}, 0);
	jQuery('.item-inner > a, .item-inner > span > h3 > a, #galleryContainer .galleryImage').click(function(event) {
		event.preventDefault();
		jQuery('.galleryViewContainer').fadeIn();
		var id = jQuery(this).find('.sell_media_image').attr('data-sell_media_item_id');
		if (!id) id = jQuery(this).parent().parent().parent().find('.sell_media_image').attr('data-sell_media_item_id');
		if (!id) id = jQuery(this).attr('data-id');
		console.log(id);
		if (id) jQuery.post(whost, {gallery: id}, function(data) {
			jQuery('.galleryViewContainer .place').append(data);
			jQuery('.galleryViewContainer .wait').fadeOut('slow', function() {
				jQuery('.galleryViewContainer .place').fadeIn('slow');
			});
		});
	});
	
	//var imageHeight = jQuery('.featured-area').css('background-position');
	
	jQuery(document).scroll(function() {
		//var s = imageHeight.replace(/px/g, '').split(' ');
		//console.log(s);
		s = jQuery(document).scrollTop() /2;
		//console.log(s);
		jQuery('.featured-area').css('background-position', '50% ' + s + 'px');
	});
	jQuery('.galleryViewContainer .place').on('click', '.close', function() {
		jQuery(this).parent().parent().fadeOut('slow', function() {
			jQuery('.galleryViewContainer .place').html('').hide();
			jQuery('.galleryViewContainer .wait').show();
		});
	});
	
});

jQuery(window).load(function() { setMasonry(); });

function setMasonry() {
	var container = document.querySelector('#galleryContainer');
	var msnry = new Masonry( container, {
	  // options
	  itemSelector: '.galleryImage'
	});
	//jQuery('.galleryImage').fadeIn();
}


function slideTransition() {
	var countChildren = jQuery('.image-slide').length;
	var currentID = jQuery('.image-slide.active').attr('data-slide');
	var nextID = parseInt(currentID) + 1;
	if (nextID >= countChildren) nextID = 0;
	//console.log(currentID);
	jQuery('.image-slide.active').fadeOut(1500, function() {
		jQuery('.image-slide.active').removeClass('active');
		jQuery('div[data-slide="'+nextID+'"]').addClass('active');
	});
	jQuery('div[data-slide="'+nextID+'"]').fadeIn(1500, function() {
		
	});
}