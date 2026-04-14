(function ($) {
	'use strict';

	function updatePreview($field, attachment) {
		var $preview = $field.find('.massitpro-media-field__preview');

		if (!attachment || !attachment.url) {
			$preview.empty();
			return;
		}

		$preview.html('<img src="' + attachment.url + '" alt="" style="max-width:150px;height:auto;">');
	}

	$(document).on('click', '.massitpro-media-button', function (event) {
		event.preventDefault();

		var $field = $(this).closest('.massitpro-media-field');
		var $input = $field.find('.massitpro-media-field__input');
		var frame = wp.media({
			title: 'Select Image',
			button: {
				text: 'Use Image'
			},
			multiple: false,
			library: {
				type: 'image'
			}
		});

		frame.on('select', function () {
			var attachment = frame.state().get('selection').first().toJSON();
			$input.val(attachment.id);
			updatePreview($field, attachment);
		});

		frame.open();
	});

	$(document).on('click', '.massitpro-media-clear', function (event) {
		event.preventDefault();

		var $field = $(this).closest('.massitpro-media-field');
		$field.find('.massitpro-media-field__input').val('');
		updatePreview($field, null);
	});
}(jQuery));
