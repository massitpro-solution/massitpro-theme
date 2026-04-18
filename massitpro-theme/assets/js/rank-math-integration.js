( function() {
	if ( ! window.wp || ! window.wp.hooks ) {
		return;
	}

	wp.hooks.addFilter(
		'rank_math_content',
		'massitpro',
		function( content ) {
			var extra = [];

			// Find all massitpro meta inputs and textareas in the page
			var inputs = document.querySelectorAll(
				'input[name^="massitpro_"], textarea[name^="massitpro_"]'
			);

			inputs.forEach( function( el ) {
				var val = el.value ? el.value.trim() : '';
				if ( val && val.length > 3 && ! isNumeric( val ) && ! isUrl( val ) ) {
					extra.push( val );
				}
			} );

			if ( extra.length ) {
				return content + ' ' + extra.join( ' ' );
			}

			return content;
		}
	);

	function isNumeric( str ) {
		return /^\d+$/.test( str );
	}

	function isUrl( str ) {
		return /^https?:\/\//i.test( str ) || /^\/[a-z0-9\-\/]+\/?$/i.test( str );
	}
} )();
