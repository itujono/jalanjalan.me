// Sticky Navigation
jQuery(document).ready(function () {
	if (jQuery().sticky) {
		jQuery(".navigation").sticky({
			topSpacing: 0,
			zIndex: 99
		});;
	}
});

function getCurrentScroll() {
	return window.pageYOffset || document.documentElement.scrollTop;
}

// Nicescroll
/*jQuery(document).ready(function() {
	jQuery("html").niceScroll();
});*/

// Waypoints
jQuery(document).ready(function () {
	//if present, set slideshow height first
	if (jQuery('.home .t3-sl-1').length > 0) {
		jQuery('.home .t3-sl-1').css('height',34/100*(jQuery('.home .t3-sl-1').width())+2);
		jQuery( window ).resize(function() {
			jQuery('.home .t3-sl-1').css('height',34/100*(jQuery('.home .t3-sl-1').width())+2);
		});
	}
});

jQuery(window).load(function () {
	//set waypoints
	if (addon_animations_enable)
		{
		if (jQuery().waypoint) {
			jQuery('.appear').waypoint(function () {
		
				var t = jQuery(this);
		
				if (jQuery(window).width() < 767) {
					t.delay(jQuery(this).data(1));
					t.toggleClass(jQuery(this).data("animated") + " animated").removeClass('appear');
				} else {
					t.delay(jQuery(this).data("start")).queue(function () {
						t.toggleClass(jQuery(this).data("animated") + " animated").removeClass('appear');
					});
				}
			}, {
				offset: '85%',
				triggerOnce: true,
			});
		}
	}
});


//------------------------------
// Parallax scrolling effect
//------------------------------
jQuery(document).ready(function(jQuery){
	var jQueryscrollTop;
	/*var jQueryheaderheight;
	var jQuerycameracaptiontop;
	
	jQueryheaderheight = jQuery('.home .t3-sl-1').height();
	jQuerycameracaptiontop = parseInt(jQuery('.camera_caption').css('top'));*/
	
	jQuery(window).scroll(function(){
		var jQueryiw = jQuery('body').innerWidth();
		jQueryscrollTop = jQuery(window).scrollTop();
		minheight = 110-(jQueryscrollTop/4);
		
		if (minheight > 52) {
			jQuery('.navigation').css({'min-height' : minheight +'px'});
			jQuery('.t3-mainnav').css({'top' : 30-(jQueryscrollTop/8) +'px'});
			jQuery('.logo-img').css({'width': 169 - (jQueryscrollTop/9) +'px'});
		}
		else {
			jQuery('.navigation').css({'min-height' : '52px'});
			jQuery('.t3-mainnav').css({'top' : 0});
			jQuery('.logo-img').css({'width': '140px'});
		}
	
		/*if (jQuerycameracaptiontop) {
			jQuery('.camera_wrap').css({'height': ((- jQueryscrollTop / 2)+ jQueryheaderheight) + 'px' });
			jQuery('.home .t3-sl-1').css({'height': (parseInt(jQuery('.camera_wrap').css('height'))+2)  + 'px' })
			jQuery('.camera_caption').css({'top': jQueryscrollTop/3 + jQuerycameracaptiontop + 'px' });
		}*/
	});
});
