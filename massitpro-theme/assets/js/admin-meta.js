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

	$(document).on('click', '.massitpro-gallery-button', function (event) {
		event.preventDefault();

		var $field = $(this).closest('.massitpro-gallery-field');
		var $input = $field.find('.massitpro-gallery-field__input');
		var existing = $input.val() ? $input.val().split(',').map(Number).filter(Boolean) : [];

		var frame = wp.media({
			title: 'Select Gallery Images',
			button: { text: 'Use Selected' },
			multiple: 'add',
			library: { type: 'image' }
		});

		frame.on('open', function () {
			var selection = frame.state().get('selection');
			existing.forEach(function (id) {
				var att = wp.media.attachment(id);
				att.fetch();
				selection.add(att);
			});
		});

		frame.on('select', function () {
			var ids = [];
			var html = '';
			frame.state().get('selection').each(function (att) {
				var data = att.toJSON();
				ids.push(data.id);
				var thumb = (data.sizes && data.sizes.thumbnail) ? data.sizes.thumbnail.url : data.url;
				html += '<img src="' + thumb + '" alt="" style="width:60px;height:60px;object-fit:cover;border-radius:4px;">';
			});
			$input.val(ids.join(','));
			$field.find('.massitpro-gallery-field__preview').html(html);
		});

		frame.open();
	});

	$(document).on('click', '.massitpro-gallery-clear', function (event) {
		event.preventDefault();
		var $field = $(this).closest('.massitpro-gallery-field');
		$field.find('.massitpro-gallery-field__input').val('');
		$field.find('.massitpro-gallery-field__preview').empty();
	});
}(jQuery));
