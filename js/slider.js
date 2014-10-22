jQuery(function() {
	//jQuery('.image-container').removeClass('clear').addClass('start');
	jQuery('.fadein').addClass('doshow');
	jQuery('.fadeout').addClass('dohide');
	//jQuery('.tag-line-box').addClass('dropit');
	setTimeout(function() {
		setInterval(function(){slideTransition();}, 3000);
	}, 0);
});



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