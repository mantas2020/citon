$(function () {
	$(".attachment>a, a[rel='modal']").attr('data-uk-lightbox', true);
	$("a").each(function () {
		if ($(this).attr('href')) {
			if ($(this).attr('href').match(/\.(jpg|png|gif)/i)) {
				$(this).parent().attr('uk-lightbox', 'animation: push');
			}
		}
	});

	$('.offcanvas, [href="#__search"]').attr('uk-toggle', '');
	$('.sow-masonry-grid').attr('uk-scrollspy', 'cls: uk-animation-scale-up; target: .sow-masonry-grid-item; delay: 100;');
	$('.sow-masonry-grid-item').addClass('uk-transition-toggle').find('img').addClass('uk-transition-scale-up uk-transition-opaque');
	$('.sow-masonry-grid-item').addClass('uk-transition-toggle').find('img').after('<div class="uk-position-center"><span class="uk-transition-fade" uk-icon="icon: plus; ratio: 2"></span></div>');

	$('.contacts >div:first-child').attr('uk-scrollspy', 'cls: uk-animation-slide-left-small; repeat: true; delay: 200;');
	$('.contacts >div:last-child').attr('uk-scrollspy', 'cls: uk-animation-slide-right-small; repeat: true; delay: 200;');

	$('.scrollspy-action .uk-container>div:first-child').attr('uk-scrollspy', 'cls: uk-animation-slide-left-small; repeat: true; delay: 200;');
	$('.scrollspy-action .uk-container>div:last-child').attr('uk-scrollspy', 'cls: uk-animation-slide-right-small; repeat: true; delay: 200;');
	$('.scrollspy-action .uk-container>div:not(:first-child):not(:last-child)').attr('uk-scrollspy', 'cls: uk-animation-slide-bottom-small; repeat: true; delay: 200;');

});

$(window).load(function () {
	$(".lds-ellipsis-absolute").fadeOut("slow");
	$('main').css({'padding-bottom': parseInt($('footer').outerHeight(true))});

});

$( window ).resize(function() {
	$('main').css({'padding-bottom': parseInt($('footer').outerHeight(true))});
});



