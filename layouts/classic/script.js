justRowsThemesConfig['classic'] = {
	'options': {
			'caption-height-zero' : true,
			'margin' : 10
		},
	'init-callback': false,
	'append-callback': false
};

jQuery(document).ready(function($) {
	$('.justrows-theme-classic')
		.on('mouseenter', '.jr-element', function(){
			$caption = $(this).children('.jr-caption');
			$caption.stop().animate(
				{
					'height' : parseInt($caption.attr('data-jr-caption-height')) + 20, // (height + padding-top + padding-bottom)
					'padding-top' : 10,
					'padding-bottom' : 10
				},
				400
			);
		})
		.on('mouseleave', '.jr-element', function(){
			$caption = $(this).children('.jr-caption');
			$caption.stop().animate(
				{
					'height' : 0,
					'padding-top' : 0,
					'padding-bottom' : 0
				},
				400
			);
		});
});
