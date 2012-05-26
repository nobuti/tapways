/* Author: Buti */

var Tapways = {};
Tapways.windowWidth = 0;
Tapways.flag = true;

var updateScroll = function(data){

	var datos = jQuery(data),
		elements = datos.find('article');
	jQuery('section.main').append(elements);
	Tapways.container.imagesLoaded(function(){
		jQuery('.loading').hide();
		if (datos.find('.next-posts a').length != 0){
			jQuery('.next-posts a').show().attr('href', datos.find('.next-posts a').attr('href'));
		} else {
			jQuery('.next-posts a').hide();
		}
		Tapways.container.fite('redraw');
	})
}

var map = function(){
	Tapways.windowWidth = jQuery(window).width();
	if (Tapways.windowWidth <= 495) {
		if (jQuery('.holder').length === 0){
			var holder = jQuery('<div class="holder"></div>');
			jQuery('footer').before(holder);
		}
		jQuery('footer').before(jQuery('.panel'));
		jQuery('.subscribe').appendTo('.holder');
	} else {
		jQuery('header').after(jQuery('.panel'));
		jQuery('header').append(jQuery('.subscribe'));
		if (jQuery('.holder').length != 0){
			jQuery('.holder').remove();
		}
	}
}

jQuery(document).ready(function(){
	jQuery('header .nav ul').Touchdown();
	Tapways.main = jQuery('section');

	jQuery(window).resize(map);

	jQuery('.submit-design').on('click', function(e){
		e.preventDefault();
		jQuery('.panel').addClass('opened');
	});

	jQuery('.close').on('click', function(e){
		e.preventDefault();
		jQuery('.panel').removeClass('opened');
	});

	if (jQuery('section.main article').length != 0){
		Tapways.container = jQuery('section.main');
		Tapways.container.imagesLoaded(function(){
				Tapways.container.fite({
					minwidth:400,
					duration: 300
				});
				map();
			})
	}
	if (jQuery('div.post').length != 0){
		var info = jQuery('.info');
		info.parent().css('min-height',info.height());
		Tapways.container = jQuery('div.post');
		Tapways.container.imagesLoaded(function(){
				Tapways.container.fite({
					responsive: true,
					minwidth: 240,
					duration: 300,
					marginy: 15
				});
				map();
			})
	}
	
	jQuery('.next-posts a').on('click', function(e){
		e.preventDefault();
		jQuery(this).hide();
		jQuery('.loading').show();
		var url = jQuery(this).attr('href');
		jQuery.ajax({ 
			url: url
		}).done(function ( data ) {
				updateScroll(data);
			});
	})
	
	jQuery('.submit').find('form').on('submit', function(e){
		e.preventDefault();
		var email = jQuery('#email').val(),
			url = jQuery('#url').val(),
			error = false;

		if (!/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/.test(email)){
			smoke.signal('Please enter a valid email address.');
			error = true;
		} else if (!/^(([\w]+:)?\/\/)?(([\d\w]|%[a-fA-f\d]{2,2})+(:([\d\w]|%[a-fA-f\d]{2,2})+)?@)?([\d\w][-\d\w]{0,253}[\d\w]\.)+[\w]{2,4}(:[\d]+)?(\/([-+_~.\d\w]|%[a-fA-f\d]{2,2})*)*(\?(&?([-+_~.\d\w]|%[a-fA-f\d]{2,2})=?)*)?(#([-+_~.\d\w]|%[a-fA-f\d]{2,2})*)?$/.test(url)){
			smoke.signal('Please enter a valid url.');
			error = true;
		}

		if (!error){
			jQuery.ajax({
				url: adminURL, 
				data: { action:'sbdesign', email: email, url: url },
				dataType : "json",
				type : 'POST',
				async : false,
				success: function(response){
					if(response.type == "success") {
						jQuery('.panel').removeClass('opened');
						jQuery('#email').val('');
						jQuery('#url').val('');
					}
					smoke.signal(response.message);
				}
			});	
		}
		

	})
});
