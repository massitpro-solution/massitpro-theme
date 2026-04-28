<?php
/**
 * Theme rendering helpers.
 *
 * @package MassITPro
 */

if (! defined('ABSPATH')) {
	exit;
}

/**
 * Render a theme button.
 *
 * @param array<string,mixed> $args Button arguments.
 */
function massitpro_render_button($args) {
	$args = wp_parse_args(
		(array) $args,
		[
			'label'      => '',
			'url'        => '',
			'variant'    => 'default',
			'size'       => 'default',
			'icon'       => '',
			'class'      => '',
			'target'     => '',
			'type'       => 'link',
			'attributes' => [],
		]
	);

	if (! $args['label']) {
		return;
	}

	if ('button' !== $args['type'] && ! $args['url']) {
		return;
	}

	$classes = trim('theme-button theme-button--' . $args['variant'] . ' theme-button--' . $args['size'] . ' ' . $args['class']);
	$icon    = $args['icon'] ? '<span class="theme-button__icon" aria-hidden="true">' . massitpro_svg_icon((string) $args['icon']) . '</span>' : '';
	$attrs   = '';

	foreach ((array) $args['attributes'] as $key => $value) {
		$attrs .= ' ' . esc_attr((string) $key) . '="' . esc_attr((string) $value) . '"';
	}

	if ('button' === $args['type']) {
		printf(
			'<button type="button" class="%1$s"%2$s><span>%3$s</span>%4$s</button>',
			esc_attr($classes),
			$attrs,
			esc_html((string) $args['label']),
			$icon
		);

		return;
	}

	$target = $args['target'] ? ' target="' . esc_attr((string) $args['target']) . '" rel="noopener noreferrer"' : '';

	printf(
		'<a class="%1$s" href="%2$s"%3$s%4$s><span>%5$s</span>%6$s</a>',
		esc_attr($classes),
		esc_url((string) $args['url']),
		$target,
		$attrs,
		esc_html((string) $args['label']),
		$icon
	);
}

/**
 * Render an image block when media exists.
 *
 * @param array<string,mixed> $args Media arguments.
 */
function massitpro_render_media($args) {
	$args = wp_parse_args(
		(array) $args,
		[
			'image'  => null,
			'aspect' => 'video',
			'class'  => '',
			'alt'    => '',
			'size'   => 'massitpro-card',
		]
	);

	$image = massitpro_resolve_image_value($args['image']);

	// If we got an attachment ID (integer), convert it to a URL/alt array.
	if (is_numeric($image)) {
		$attachment_id = (int) $image;
		$url           = wp_get_attachment_image_url($attachment_id, $args['size']);

		if (! $url) {
			$url = wp_get_attachment_image_url($attachment_id, 'large');
		}

		if (! $url) {
			$url = wp_get_attachment_image_url($attachment_id, 'full');
		}

		$image = $url
			? [
				'url' => $url,
				'alt' => (string) get_post_meta($attachment_id, '_wp_attachment_image_alt', true),
			]
			: null;
	}

	if (! $image || empty($image['url'])) {
		return;
	}

	$alt = (string) ($args['alt'] ?: ($image['alt'] ?? ''));
	?>
	<div class="media-block media-block--<?php echo esc_attr((string) $args['aspect']); ?> <?php echo esc_attr((string) $args['class']); ?>">
		<img src="<?php echo esc_url((string) $image['url']); ?>" alt="<?php echo esc_attr($alt); ?>" loading="lazy">
	</div>
	<?php
}

/**
 * Render a shared section heading.
 *
 * @param array<string,mixed> $args Heading arguments.
 */
function massitpro_render_section_heading($args) {
	$args = wp_parse_args(
		(array) $args,
		[
			'label'      => '',
			'title'      => '',
			'copy'       => '',
			'link_label' => '',
			'link_url'   => '',
			'align'      => 'left',
		]
	);

	if (! $args['label'] && ! $args['title'] && ! $args['copy']) {
		return;
	}
	?>
	<div class="section-header section-header--<?php echo esc_attr((string) $args['align']); ?>" data-reveal>
		<div class="section-header__copy">
			<?php if ($args['label']) : ?>
				<p class="section-label"><?php echo esc_html((string) $args['label']); ?></p>
			<?php endif; ?>
			<?php if ($args['title']) : ?>
				<h2><?php echo esc_html((string) $args['title']); ?></h2>
			<?php endif; ?>
			<?php if ($args['copy']) : ?>
				<div class="section-copy"><?php echo wp_kses_post((string) $args['copy']); ?></div>
			<?php endif; ?>
		</div>
		<?php if ($args['link_label'] && $args['link_url']) : ?>
			<a class="section-link" href="<?php echo esc_url((string) $args['link_url']); ?>">
				<span><?php echo esc_html((string) $args['link_label']); ?></span>
				<span aria-hidden="true"><?php echo massitpro_svg_icon('arrow-right'); ?></span>
			</a>
		<?php endif; ?>
	</div>
	<?php
}

/**
 * Render accordion items.
 *
 * @param array<int,array<string,mixed>> $items Accordion items.
 * @param float                          $step  Delay step.
 */
function massitpro_render_accordion_items($items, $step = 0.05) {
	foreach ($items as $index => $item) :
		$question = trim((string) ($item['question'] ?? ''));
		$answer   = (string) ($item['answer'] ?? '');

		if (! $question || ! $answer) {
			continue;
		}
		?>
		<article class="content-card accordion-item" data-reveal style="transition-delay: <?php echo esc_attr(number_format($index * $step, 2, '.', '')); ?>s;">
			<button class="accordion-trigger" type="button" data-accordion-trigger aria-expanded="false">
				<span><?php echo esc_html($question); ?></span>
				<span class="accordion-trigger__icon" aria-hidden="true"><?php echo massitpro_svg_icon('chevron-down'); ?></span>
			</button>
			<div class="accordion-panel" data-accordion-panel>
				<div class="accordion-panel__content"><?php echo wp_kses_post($answer); ?></div>
			</div>
		</article>
		<?php
	endforeach;
}

/**
 * Render the shared page hero partial.
 *
 * @param array<string,mixed> $args Hero arguments.
 */
function massitpro_render_page_hero($args) {
	get_template_part('template-parts/site/page-hero', null, $args);
}

/**
 * Resolve the current render post ID.
 *
 * @param int $post_id Optional explicit post ID.
 * @return int
 */
function massitpro_get_render_post_id($post_id = 0) {
	$post_id = (int) $post_id;

	if ($post_id > 0) {
		return $post_id;
	}

	$current_id = (int) get_the_ID();

	if ($current_id > 0) {
		return $current_id;
	}

	return (int) get_queried_object_id();
}

/**
 * Get a post title string.
 *
 * @param int $post_id Post ID.
 * @return string
 */
function massitpro_get_post_title_text($post_id) {
	return trim(wp_strip_all_tags((string) get_the_title($post_id)));
}

/**
 * Get a post excerpt string.
 *
 * @param int $post_id Post ID.
 * @return string
 */
function massitpro_get_post_excerpt_text($post_id) {
	$excerpt = trim((string) get_post_field('post_excerpt', $post_id));

	if ($excerpt) {
		return wp_strip_all_tags($excerpt);
	}

	return trim(wp_strip_all_tags((string) get_the_excerpt($post_id)));
}

/**
 * Resolve post content HTML.
 *
 * @param int $post_id Post ID.
 * @return string
 */
function massitpro_get_post_content_html($post_id) {
	return massitpro_get_page_body_content($post_id);
}

/**
 * Build a plain-text summary from the excerpt or page content.
 *
 * @param int $post_id Post ID.
 * @return string
 */
function massitpro_get_post_summary_text($post_id) {
	$summary = massitpro_get_post_excerpt_text($post_id);

	if ($summary) {
		return $summary;
	}

	$content = trim(wp_strip_all_tags((string) get_post_field('post_content', $post_id)));

	if (! $content) {
		return '';
	}

	return wp_trim_words($content, 28, '...');
}

/**
 * Resolve a featured image attachment ID from a page URL.
 *
 * Tries url_to_postid() first, then prepends home_url() for relative paths,
 * then falls back to get_page_by_path() — so service/industry cards auto-pull
 * the featured image from the linked page without requiring a manual upload.
 *
 * @param string $url Page URL (absolute or relative).
 * @return int Attachment ID, or 0 if not found.
 */
function massitpro_resolve_linked_page_image_id( $url ) {
	if ( ! $url ) {
		return 0;
	}

	// 1. Try direct lookup.
	$post_id = url_to_postid( $url );

	// 2. If URL is relative, prepend home_url and retry.
	if ( ! $post_id && strpos( $url, 'http' ) !== 0 ) {
		$post_id = url_to_postid( home_url( '/' . ltrim( $url, '/' ) ) );
	}

	// 3. Fall back to path-based lookup.
	if ( ! $post_id ) {
		$path = trim( (string) parse_url( $url, PHP_URL_PATH ), '/' );
		if ( $path ) {
			$page = get_page_by_path( $path );
			if ( $page instanceof WP_Post ) {
				$post_id = $page->ID;
			}
		}
	}

	return $post_id ? (int) get_post_thumbnail_id( $post_id ) : 0;
}

/**
 * Filter repeater rows by required keys.
 *
 * @param array<int,array<string,mixed>> $rows Row values.
 * @param array<int,string>              $keys Required keys.
 * @return array<int,array<string,mixed>>
 */
function massitpro_filter_rows($rows, $keys) {
	$filtered = [];

	foreach ((array) $rows as $row) {
		foreach ($keys as $key) {
			if (! massitpro_is_empty_value($row[$key] ?? null)) {
				$filtered[] = (array) $row;
				break;
			}
		}
	}

	return $filtered;
}

/**
 * Determine if at least one value contains content.
 *
 * @param mixed ...$values Values to inspect.
 * @return bool
 */
function massitpro_has_any_content(...$values) {
	foreach ($values as $value) {
		if (! massitpro_is_empty_value($value)) {
			return true;
		}
	}

	return false;
}

/**
 * Normalize CTA buttons for rendering.
 *
 * @param array<int,array<string,mixed>> $buttons Button rows.
 * @return array<int,array<string,string>>
 */
function massitpro_get_buttons($buttons) {
	return massitpro_normalize_button_rows((array) $buttons);
}

/**
 * Prepare page hero data from the current page and native hero meta.
 *
 * @param int                 $post_id  Post ID.
 * @param array<string,mixed> $defaults Optional defaults.
 * @return array<string,mixed>
 */
function massitpro_prepare_page_hero($post_id, $defaults = []) {
	$post_id = massitpro_get_render_post_id($post_id);
	$hero    = (array) massitpro_get_section_meta('hero', $post_id, []);

	return [
		'label'    => trim((string) ($hero['eyebrow'] ?? ($defaults['label'] ?? ''))),
		'title'    => trim((string) ($hero['title_override'] ?? '')) ?: massitpro_get_post_title_text($post_id) ?: (string) ($defaults['title'] ?? ''),
		'subtitle' => trim((string) ($hero['subtitle'] ?? '')) ?: massitpro_get_post_excerpt_text($post_id) ?: (string) ($defaults['subtitle'] ?? ''),
		'image'    => massitpro_resolve_image_value($hero['image'] ?? null) ?: massitpro_get_post_display_image($post_id),
		'buttons'  => massitpro_get_buttons($hero['buttons'] ?? []),
	];
}

/**
 * Render a row of buttons.
 *
 * @param array<int,array<string,string>> $buttons Button rows.
 * @param string                          $class   Wrapper classes.
 */
function massitpro_render_button_row($buttons, $class = 'button-row') {
	$buttons = array_values((array) $buttons);

	if (! $buttons) {
		return;
	}
	?>
	<div class="<?php echo esc_attr($class); ?>">
		<?php foreach ($buttons as $button) : ?>
			<?php massitpro_render_button($button); ?>
		<?php endforeach; ?>
	</div>
	<?php
}

/**
 * Render a two-column intro or overview section.
 *
 * @param string              $field_name Section field name.
 * @param int                 $post_id    Post ID.
 * @param array<string,mixed> $args       Render arguments.
 */
function massitpro_render_intro_section($field_name, $post_id, $args = []) {
	$args    = wp_parse_args(
		(array) $args,
		[
			'surface_class' => '',
			'reverse'       => false,
			'media_aspect'  => 'square',
			'section_class' => '',
		]
	);
	$post_id = massitpro_get_render_post_id($post_id);
	$group   = (array) massitpro_get_section_meta($field_name, $post_id, []);
	$heading = trim((string) ($group['heading'] ?? ''));
	$body    = (string) ($group['body'] ?? '');
	$image   = massitpro_resolve_image_value($group['image'] ?? null) ?: massitpro_get_post_display_image($post_id);

	if (! $body) {
		$body = massitpro_get_post_content_html($post_id);
	}

	if (! massitpro_has_any_content($heading, $body)) {
		return;
	}
	?>
	<section class="section-padding section-spacing <?php echo esc_attr(trim((string) $args['surface_class'] . ' ' . (string) $args['section_class'])); ?>">
		<div class="site-shell">
			<div class="split-feature<?php echo $image ? '' : ' split-feature--single'; ?><?php echo ! empty($args['reverse']) ? ' split-feature--reverse' : ''; ?>">
				<?php if ($body || $heading) : ?>
					<div class="split-feature__copy content-card entry-content" data-reveal>
						<?php if ($heading) : ?>
							<h2><?php echo esc_html($heading); ?></h2>
						<?php endif; ?>
						<?php if ($body) : ?>
							<div class="section-copy section-copy--rich"><?php echo wp_kses_post($body); ?></div>
						<?php endif; ?>
					</div>
				<?php endif; ?>
				<?php if ($image) : ?>
					<div class="split-feature__media" data-reveal>
						<?php massitpro_render_media(['image' => $image, 'aspect' => $args['media_aspect']]); ?>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</section>
	<?php
}

/**
 * Render a text-list section.
 *
 * @param string              $field_name Section field name.
 * @param int                 $post_id    Post ID.
 * @param array<string,mixed> $args       Render arguments.
 */
function massitpro_render_text_list_section($field_name, $post_id, $args = []) {
	$args    = wp_parse_args(
		(array) $args,
		[
			'surface_class' => '',
			'section_class' => '',
		]
	);
	$group   = (array) massitpro_get_section_meta($field_name, massitpro_get_render_post_id($post_id), []);
	$heading = trim((string) ($group['heading'] ?? ''));
	$body    = (string) ($group['body'] ?? '');
	$items   = massitpro_filter_rows((array) ($group['items'] ?? []), ['text']);

	if (! massitpro_has_any_content($heading, $body, $items)) {
		return;
	}
	?>
	<section class="section-padding section-spacing <?php echo esc_attr(trim((string) $args['surface_class'] . ' ' . (string) $args['section_class'])); ?>">
		<div class="site-shell">
			<?php massitpro_render_section_heading(['title' => $heading, 'copy' => $body]); ?>
			<?php if ($items) : ?>
				<div class="content-card list-card list-card--stacked" data-reveal>
					<ul>
						<?php foreach ($items as $item) : ?>
							<li><?php echo esc_html((string) $item['text']); ?></li>
						<?php endforeach; ?>
					</ul>
				</div>
			<?php endif; ?>
		</div>
	</section>
	<?php
}

/**
 * Render a process-step section.
 *
 * @param string              $field_name Section field name.
 * @param int                 $post_id    Post ID.
 * @param array<string,mixed> $args       Render arguments.
 */
function massitpro_render_process_section($field_name, $post_id, $args = []) {
	$args    = wp_parse_args(
		(array) $args,
		[
			'surface_class' => 'surface-sand',
			'section_class' => '',
		]
	);
	$group   = (array) massitpro_get_section_meta($field_name, massitpro_get_render_post_id($post_id), []);
	$heading = trim((string) ($group['heading'] ?? ''));
	$body    = (string) ($group['body'] ?? '');
	$steps   = massitpro_filter_rows((array) ($group['steps'] ?? []), ['step_label', 'title', 'body']);

	if (! massitpro_has_any_content($heading, $body, $steps)) {
		return;
	}
	?>
	<section class="section-padding section-spacing <?php echo esc_attr(trim((string) $args['surface_class'] . ' ' . (string) $args['section_class'])); ?>">
		<div class="site-shell">
			<?php massitpro_render_section_heading(['title' => $heading, 'copy' => $body]); ?>
			<?php if ($steps) : ?>
				<div class="process-grid">
					<?php foreach ($steps as $index => $step) : ?>
						<article class="content-card process-card" data-reveal style="transition-delay: <?php echo esc_attr(number_format($index * 0.05, 2, '.', '')); ?>s;">
							<?php if (! empty($step['step_label'])) : ?>
								<p class="process-card__step"><?php echo esc_html((string) $step['step_label']); ?></p>
							<?php endif; ?>
							<?php if (! empty($step['title'])) : ?>
								<h3><?php echo esc_html((string) $step['title']); ?></h3>
							<?php endif; ?>
							<?php if (! empty($step['body'])) : ?>
								<p><?php echo esc_html((string) $step['body']); ?></p>
							<?php endif; ?>
						</article>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>
	</section>
	<?php
}

/**
 * Render a simple icon-card section.
 *
 * @param string              $field_name Section field name.
 * @param int                 $post_id    Post ID.
 * @param array<string,mixed> $args       Render arguments.
 */
function massitpro_render_icon_cards_section($field_name, $post_id, $args = []) {
	$args    = wp_parse_args(
		(array) $args,
		[
			'surface_class' => '',
			'section_class' => '',
			'card_class'    => '',
		]
	);
	$group   = (array) massitpro_get_section_meta($field_name, massitpro_get_render_post_id($post_id), []);
	$heading = trim((string) ($group['heading'] ?? ''));
	$body    = (string) ($group['body'] ?? '');
	$items   = massitpro_filter_rows((array) ($group['items'] ?? []), ['title', 'body']);

	if (! massitpro_has_any_content($heading, $body, $items)) {
		return;
	}
	?>
	<section class="section-padding section-spacing <?php echo esc_attr(trim((string) $args['surface_class'] . ' ' . (string) $args['section_class'])); ?>">
		<div class="site-shell">
			<?php massitpro_render_section_heading(['title' => $heading, 'copy' => $body]); ?>
			<?php if ($items) : ?>
				<div class="cards-grid cards-grid--3">
					<?php foreach ($items as $index => $item) : ?>
						<article class="content-card icon-feature-card <?php echo esc_attr((string) $args['card_class']); ?>" data-reveal style="transition-delay: <?php echo esc_attr(number_format($index * 0.05, 2, '.', '')); ?>s;">
							<div class="card-icon" aria-hidden="true"><?php echo massitpro_svg_icon((string) ($item['icon'] ?? 'check')); ?></div>
							<h3><?php echo esc_html((string) ($item['title'] ?? '')); ?></h3>
							<?php if (! empty($item['body'])) : ?>
								<p><?php echo esc_html((string) $item['body']); ?></p>
							<?php endif; ?>
						</article>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>
	</section>
	<?php
}

/**
 * Render a card grid from repeater items that may include images or links.
 *
 * @param string              $field_name Section field name.
 * @param int                 $post_id    Post ID.
 * @param array<string,mixed> $args       Render arguments.
 */
function massitpro_render_image_cards_section($field_name, $post_id, $args = []) {
	$args    = wp_parse_args(
		(array) $args,
		[
			'surface_class' => '',
			'section_class' => '',
			'card_class'    => '',
			'cards_class'   => 'cards-grid cards-grid--3',
			'heading_align' => 'left',
			'icon_default'  => '',
		]
	);
	$group   = (array) massitpro_get_section_meta($field_name, massitpro_get_render_post_id($post_id), []);
	$eyebrow = trim((string) ($group['eyebrow'] ?? ''));
	$heading = trim((string) ($group['heading'] ?? ''));
	$body    = (string) ($group['body'] ?? '');
	$items   = massitpro_filter_rows((array) ($group['items'] ?? []), ['title', 'body', 'image', 'icon', 'link']);

	if (! massitpro_has_any_content($eyebrow, $heading, $body, $items)) {
		return;
	}
	?>
	<section class="section-padding section-spacing <?php echo esc_attr(trim((string) $args['surface_class'] . ' ' . (string) $args['section_class'])); ?>">
		<div class="site-shell">
			<?php massitpro_render_section_heading(['label' => $eyebrow, 'title' => $heading, 'copy' => $body, 'align' => $args['heading_align']]); ?>
			<?php if ($items) : ?>
				<div class="<?php echo esc_attr((string) $args['cards_class']); ?>">
					<?php foreach ($items as $index => $item) : ?>
						<?php $link  = massitpro_normalize_link($item['link'] ?? []); ?>
						<?php $image = massitpro_resolve_image_value($item['image'] ?? null); ?>
						<?php $icon  = trim((string) ($item['icon'] ?? '')) ?: (string) $args['icon_default']; ?>
						<article class="content-card media-card feature-grid-card <?php echo esc_attr((string) $args['card_class']); ?><?php echo ($image || $icon) ? '' : ' feature-grid-card--text'; ?>" data-reveal style="transition-delay: <?php echo esc_attr(number_format($index * 0.05, 2, '.', '')); ?>s;">
							<?php if ($image) : ?>
								<?php if ($link['url']) : ?>
									<a href="<?php echo esc_url($link['url']); ?>"<?php echo $link['target'] ? ' target="' . esc_attr($link['target']) . '" rel="noopener noreferrer"' : ''; ?>>
										<?php massitpro_render_media(['image' => $image, 'aspect' => 'video']); ?>
									</a>
								<?php else : ?>
									<?php massitpro_render_media(['image' => $image, 'aspect' => 'video']); ?>
								<?php endif; ?>
							<?php endif; ?>
							<div class="media-card__body">
								<?php if ($icon) : ?>
									<span class="card-icon-chip" aria-hidden="true"><?php echo massitpro_svg_icon($icon); ?></span>
								<?php endif; ?>
								<?php if (! empty($item['title'])) : ?>
									<h3>
										<?php if ($link['url']) : ?>
											<a href="<?php echo esc_url($link['url']); ?>"<?php echo $link['target'] ? ' target="' . esc_attr($link['target']) . '" rel="noopener noreferrer"' : ''; ?>><?php echo esc_html((string) $item['title']); ?></a>
										<?php else : ?>
											<?php echo esc_html((string) $item['title']); ?>
										<?php endif; ?>
									</h3>
								<?php endif; ?>
								<?php if (! empty($item['body'])) : ?>
									<p><?php echo esc_html((string) $item['body']); ?></p>
								<?php endif; ?>
							</div>
						</article>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>
	</section>
	<?php
}

/**
 * Resolve FAQ items from FAQ posts.
 *
 * @param array<int,WP_Post> $posts FAQ posts.
 * @return array<int,array<string,mixed>>
 */
function massitpro_get_faq_items($posts) {
	$items = [];

	foreach ((array) $posts as $post) {
		if (! $post instanceof WP_Post) {
			continue;
		}

		$answer = massitpro_get_post_content_html($post->ID);

		if (! $answer) {
			$answer = wpautop(wp_kses_post((string) $post->post_excerpt));
		}

		if (! $answer) {
			continue;
		}

		$items[] = [
			'question'    => get_the_title($post),
			'answer'      => $answer,
			'group_label' => '',
			'order'       => (int) $post->menu_order,
		];
	}

	usort(
		$items,
		static function ($first, $second) {
			$first_order  = (int) ($first['order'] ?? 0);
			$second_order = (int) ($second['order'] ?? 0);

			if ($first_order === $second_order) {
				return strcasecmp((string) ($first['question'] ?? ''), (string) ($second['question'] ?? ''));
			}

			return $first_order <=> $second_order;
		}
	);

	return $items;
}

/**
 * Render a page card.
 *
 * @param WP_Post $post    Page object.
 * @param int     $index   Card index.
 * @param string  $variant Card variant.
 */
function massitpro_render_page_card($post, $index = 0, $variant = 'media') {
	$image   = massitpro_get_post_display_image($post->ID);
	$excerpt = massitpro_get_post_summary_text($post->ID);

	if ('compact' === $variant) {
		?>
		<article class="content-card compact-service-card" data-reveal style="transition-delay: <?php echo esc_attr(number_format($index * 0.05, 2, '.', '')); ?>s;">
			<div class="service-card__top">
				<div class="card-icon" aria-hidden="true"><?php echo massitpro_svg_icon('arrow-right'); ?></div>
				<span class="service-card__number"><?php echo esc_html(str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT)); ?></span>
			</div>
			<h3><a href="<?php echo esc_url(get_permalink($post)); ?>"><?php echo esc_html(get_the_title($post)); ?></a></h3>
			<?php if ($excerpt) : ?>
				<p><?php echo esc_html($excerpt); ?></p>
			<?php endif; ?>
			<a class="section-link section-link--inline" href="<?php echo esc_url(get_permalink($post)); ?>">
				<span><?php esc_html_e('Learn More', 'massitpro'); ?></span>
				<span aria-hidden="true"><?php echo massitpro_svg_icon('arrow-right'); ?></span>
			</a>
		</article>
		<?php
		return;
	}
	?>
	<article class="content-card media-card feature-grid-card<?php echo $image ? '' : ' feature-grid-card--text'; ?>" data-reveal style="transition-delay: <?php echo esc_attr(number_format($index * 0.05, 2, '.', '')); ?>s;">
		<?php if ($image) : ?>
			<a href="<?php echo esc_url(get_permalink($post)); ?>">
				<?php massitpro_render_media(['image' => $image, 'aspect' => 'video']); ?>
			</a>
		<?php endif; ?>
		<div class="media-card__body">
			<h3><a href="<?php echo esc_url(get_permalink($post)); ?>"><?php echo esc_html(get_the_title($post)); ?></a></h3>
			<?php if ($excerpt) : ?>
				<p><?php echo esc_html($excerpt); ?></p>
			<?php endif; ?>
		</div>
	</article>
	<?php
}

/**
 * Render an alternating feature row for a page relationship item.
 *
 * @param WP_Post $post  Page object.
 * @param int     $index Item index.
 */
function massitpro_render_page_feature_row($post, $index = 0) {
	$image   = massitpro_get_post_display_image($post->ID);
	$summary = massitpro_get_post_summary_text($post->ID);
	?>
	<article class="content-card feature-row<?php echo $image ? '' : ' feature-row--text-only'; ?><?php echo $index % 2 ? ' feature-row--reverse' : ''; ?>" data-reveal style="transition-delay: <?php echo esc_attr(number_format($index * 0.06, 2, '.', '')); ?>s;">
		<div class="feature-row__copy">
			<span class="feature-row__number"><?php echo esc_html(str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT)); ?></span>
			<h3><?php echo esc_html(get_the_title($post)); ?></h3>
			<?php if ($summary) : ?>
				<p><?php echo esc_html($summary); ?></p>
			<?php endif; ?>
			<a class="section-link section-link--inline" href="<?php echo esc_url(get_permalink($post)); ?>">
				<span><?php esc_html_e('Explore Service', 'massitpro'); ?></span>
				<span aria-hidden="true"><?php echo massitpro_svg_icon('arrow-right'); ?></span>
			</a>
		</div>
		<?php if ($image) : ?>
			<div class="feature-row__media">
				<a href="<?php echo esc_url(get_permalink($post)); ?>">
					<?php massitpro_render_media(['image' => $image, 'aspect' => 'video']); ?>
				</a>
			</div>
		<?php endif; ?>
	</article>
	<?php
}

/**
 * Render a project card.
 *
 * @param WP_Post $post    Project object.
 * @param int     $index   Card index.
 * @param string  $variant Card variant.
 */
function massitpro_render_project_card($post, $index = 0, $variant = 'media') {
	$image    = massitpro_get_post_display_image($post->ID);
	$client   = '';
	$industry = '';
	$excerpt  = massitpro_get_post_summary_text($post->ID);
	?>
	<article class="content-card media-card feature-grid-card<?php echo $image ? '' : ' feature-grid-card--text'; ?><?php echo 'dark' === $variant ? ' feature-grid-card--dark' : ''; ?>" data-reveal style="transition-delay: <?php echo esc_attr(number_format($index * 0.05, 2, '.', '')); ?>s;">
		<?php if ($image) : ?>
			<a href="<?php echo esc_url(get_permalink($post)); ?>">
				<?php massitpro_render_media(['image' => $image, 'aspect' => 'video']); ?>
			</a>
		<?php endif; ?>
		<div class="media-card__body">
			<div class="meta-row">
				<?php if ($industry) : ?>
					<span class="chip chip--teal"><?php echo esc_html($industry); ?></span>
				<?php endif; ?>
				<?php if ($client) : ?>
					<span class="meta-date"><?php echo esc_html($client); ?></span>
				<?php endif; ?>
			</div>
			<h3><a href="<?php echo esc_url(get_permalink($post)); ?>"><?php echo esc_html(get_the_title($post)); ?></a></h3>
			<?php if ($excerpt) : ?>
				<p><?php echo esc_html($excerpt); ?></p>
			<?php endif; ?>
		</div>
	</article>
	<?php
}

/**
 * Render a testimonial card.
 *
 * @param WP_Post $post  Testimonial object.
 * @param int     $index Card index.
 */
function massitpro_render_testimonial_card($post, $index = 0) {
	$rating  = 5;
	$name    = get_the_title($post);
	$role    = '';
	$company = trim((string) get_post_field('post_excerpt', $post->ID));
	$quote   = massitpro_get_post_content_html($post->ID) ?: wpautop(wp_kses_post((string) $post->post_excerpt));
	?>
	<article class="content-card testimonial-card feature-quote-card" data-reveal style="transition-delay: <?php echo esc_attr(number_format($index * 0.05, 2, '.', '')); ?>s;">
		<div class="testimonial-stars testimonial-stars--left" aria-hidden="true">
			<?php for ($star = 0; $star < $rating; $star++) : ?>
				<?php echo massitpro_svg_icon('star'); ?>
			<?php endfor; ?>
		</div>
		<?php if ($quote) : ?>
			<blockquote><?php echo wp_kses_post($quote); ?></blockquote>
		<?php endif; ?>
		<div class="testimonial-meta testimonial-meta--left">
			<span class="testimonial-meta__name"><?php echo esc_html($name); ?></span>
			<?php if ($role || $company) : ?>
				<span><?php echo esc_html(trim($role . ($role && $company ? ', ' : '') . $company)); ?></span>
			<?php endif; ?>
		</div>
	</article>
	<?php
}

/**
 * Render a blog post card.
 *
 * @param WP_Post $post  Post object.
 * @param int     $index Card index.
 */
function massitpro_render_post_card($post, $index = 0) {
	$image    = massitpro_get_post_display_image($post->ID);
	$excerpt  = massitpro_get_post_summary_text($post->ID);
	$category = get_the_category($post->ID);
	?>
	<article class="content-card media-card feature-grid-card<?php echo $image ? '' : ' feature-grid-card--text'; ?>" data-reveal style="transition-delay: <?php echo esc_attr(number_format($index * 0.05, 2, '.', '')); ?>s;">
		<?php if ($image) : ?>
			<a href="<?php echo esc_url(get_permalink($post)); ?>">
				<?php massitpro_render_media(['image' => $image, 'aspect' => 'video']); ?>
			</a>
		<?php endif; ?>
		<div class="media-card__body">
			<div class="meta-row">
				<?php if (! empty($category[0])) : ?>
					<span class="chip chip--teal"><?php echo esc_html($category[0]->name); ?></span>
				<?php endif; ?>
				<span class="meta-date"><?php echo esc_html(get_the_date('', $post)); ?></span>
			</div>
			<h3><a href="<?php echo esc_url(get_permalink($post)); ?>"><?php echo esc_html(get_the_title($post)); ?></a></h3>
			<?php if ($excerpt) : ?>
				<p><?php echo esc_html($excerpt); ?></p>
			<?php endif; ?>
		</div>
	</article>
	<?php
}

/**
 * Render a relationship card section.
 *
 * @param string              $field_name    Section key.
 * @param string              $items_key     Relationship items key.
 * @param callable            $card_renderer Card renderer.
 * @param int                 $post_id       Post ID.
 * @param array<string,mixed> $args          Render arguments.
 */
function massitpro_render_relationship_cards_section($field_name, $items_key, $card_renderer, $post_id, $args = []) {
	$args    = wp_parse_args(
		(array) $args,
		[
			'surface_class' => '',
			'section_class' => '',
			'cards_class'   => 'cards-grid cards-grid--3',
			'heading_args'  => [],
		]
	);
	$group   = (array) massitpro_get_section_meta($field_name, massitpro_get_render_post_id($post_id), []);
	$heading = trim((string) ($group['heading'] ?? ''));
	$body    = (string) ($group['body'] ?? '');
	$items   = massitpro_normalize_related_posts($group[$items_key] ?? []);

	if (! massitpro_has_any_content($heading, $body, $items)) {
		return;
	}
	?>
	<section class="section-padding section-spacing <?php echo esc_attr(trim((string) $args['surface_class'] . ' ' . (string) $args['section_class'])); ?>">
		<div class="site-shell">
			<?php massitpro_render_section_heading(wp_parse_args((array) $args['heading_args'], ['title' => $heading, 'copy' => $body])); ?>
			<?php if ($items) : ?>
				<div class="<?php echo esc_attr((string) $args['cards_class']); ?>">
					<?php foreach ($items as $index => $item) : ?>
						<?php call_user_func($card_renderer, $item, $index); ?>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>
	</section>
	<?php
}

/**
 * Render an alternating feature-row relationship section.
 *
 * @param string              $field_name Section key.
 * @param string              $items_key  Relationship items key.
 * @param int                 $post_id    Post ID.
 * @param array<string,mixed> $args       Render arguments.
 */
function massitpro_render_relationship_feature_rows_section($field_name, $items_key, $post_id, $args = []) {
	$args    = wp_parse_args(
		(array) $args,
		[
			'surface_class' => '',
			'section_class' => '',
		]
	);
	$group   = (array) massitpro_get_section_meta($field_name, massitpro_get_render_post_id($post_id), []);
	$heading = trim((string) ($group['heading'] ?? ''));
	$body    = (string) ($group['body'] ?? '');
	$items   = massitpro_normalize_related_posts($group[$items_key] ?? []);

	if (! massitpro_has_any_content($heading, $body, $items)) {
		return;
	}
	?>
	<section class="section-padding section-spacing <?php echo esc_attr(trim((string) $args['surface_class'] . ' ' . (string) $args['section_class'])); ?>">
		<div class="site-shell">
			<?php massitpro_render_section_heading(['title' => $heading, 'copy' => $body]); ?>
			<?php if ($items) : ?>
				<div class="feature-row-stack">
					<?php foreach ($items as $index => $item) : ?>
						<?php massitpro_render_page_feature_row($item, $index); ?>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>
	</section>
	<?php
}

/**
 * Render a relationship section as location-style pills.
 *
 * @param string              $field_name Section key.
 * @param string              $items_key  Relationship items key.
 * @param int                 $post_id    Post ID.
 * @param array<string,mixed> $args       Render arguments.
 */
function massitpro_render_relationship_pills_section($field_name, $items_key, $post_id, $args = []) {
	$args    = wp_parse_args(
		(array) $args,
		[
			'surface_class' => 'surface-sand',
			'section_class' => '',
		]
	);
	$group   = (array) massitpro_get_section_meta($field_name, massitpro_get_render_post_id($post_id), []);
	$heading = trim((string) ($group['heading'] ?? ''));
	$body    = (string) ($group['body'] ?? '');
	$items   = massitpro_normalize_related_posts($group[$items_key] ?? []);

	if (! massitpro_has_any_content($heading, $body, $items)) {
		return;
	}
	?>
	<section class="section-padding section-spacing <?php echo esc_attr(trim((string) $args['surface_class'] . ' ' . (string) $args['section_class'])); ?>">
		<div class="site-shell">
			<?php massitpro_render_section_heading(['title' => $heading, 'copy' => $body, 'align' => 'center']); ?>
			<?php if ($items) : ?>
				<div class="location-pills" data-reveal>
					<?php foreach ($items as $item) : ?>
						<a class="content-card location-pill" href="<?php echo esc_url(get_permalink($item)); ?>">
							<span aria-hidden="true"><?php echo massitpro_svg_icon('map-pin'); ?></span>
							<span><?php echo esc_html(get_the_title($item)); ?></span>
						</a>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>
	</section>
	<?php
}

/**
 * Render a featured project section from a post object group.
 *
 * @param string              $field_name Section key.
 * @param int                 $post_id    Post ID.
 * @param array<string,mixed> $args       Render arguments.
 */
function massitpro_render_featured_project_section($field_name, $post_id, $args = []) {
	$args    = wp_parse_args(
		(array) $args,
		[
			'surface_class' => 'surface-sand',
			'section_class' => '',
		]
	);
	$group   = (array) massitpro_get_section_meta($field_name, massitpro_get_render_post_id($post_id), []);
	$heading = trim((string) ($group['heading'] ?? ''));
	$body    = (string) ($group['body'] ?? '');
	$project = get_post((int) ($group['project'] ?? 0));

	if (! $project instanceof WP_Post && ! massitpro_has_any_content($heading, $body)) {
		return;
	}
	?>
	<section class="section-padding section-spacing <?php echo esc_attr(trim((string) $args['surface_class'] . ' ' . (string) $args['section_class'])); ?>">
		<div class="site-shell">
			<?php massitpro_render_section_heading(['title' => $heading, 'copy' => $body]); ?>
			<?php if ($project instanceof WP_Post) : ?>
				<?php $image = massitpro_get_post_display_image($project->ID); ?>
				<?php $summary = massitpro_get_post_summary_text($project->ID); ?>
				<article class="dark-card project-feature<?php echo $image ? '' : ' project-feature--text-only'; ?>" data-reveal>
					<div class="project-feature__copy">
						<span class="chip chip--dark"><?php esc_html_e('Featured Project', 'massitpro'); ?></span>
						<h3><a href="<?php echo esc_url(get_permalink($project)); ?>"><?php echo esc_html(get_the_title($project)); ?></a></h3>
						<?php if ($summary) : ?>
							<p><?php echo esc_html($summary); ?></p>
						<?php endif; ?>
						<a class="section-link section-link--inline" href="<?php echo esc_url(get_permalink($project)); ?>">
							<span><?php esc_html_e('View Project', 'massitpro'); ?></span>
							<span aria-hidden="true"><?php echo massitpro_svg_icon('arrow-right'); ?></span>
						</a>
					</div>
					<?php if ($image) : ?>
						<div class="project-feature__media">
							<a href="<?php echo esc_url(get_permalink($project)); ?>">
								<?php massitpro_render_media(['image' => $image, 'aspect' => 'video']); ?>
							</a>
						</div>
					<?php endif; ?>
				</article>
			<?php endif; ?>
		</div>
	</section>
	<?php
}

/**
 * Render a related-links section.
 *
 * @param string              $field_name Section key.
 * @param int                 $post_id    Post ID.
 * @param array<string,mixed> $args       Render arguments.
 */
function massitpro_render_related_links_section($field_name, $post_id, $args = []) {
	$args    = wp_parse_args(
		(array) $args,
		[
			'surface_class' => '',
			'section_class' => '',
		]
	);
	$group   = (array) massitpro_get_section_meta($field_name, massitpro_get_render_post_id($post_id), []);
	$heading = trim((string) ($group['heading'] ?? ''));
	$body    = (string) ($group['body'] ?? '');
	$items   = massitpro_filter_rows((array) ($group['items'] ?? []), ['link', 'description']);

	if (! massitpro_has_any_content($heading, $body, $items)) {
		return;
	}
	?>
	<section class="section-padding section-spacing <?php echo esc_attr(trim((string) $args['surface_class'] . ' ' . (string) $args['section_class'])); ?>">
		<div class="site-shell">
			<?php massitpro_render_section_heading(['title' => $heading, 'copy' => $body]); ?>
			<?php if ($items) : ?>
				<div class="content-card list-card" data-reveal>
					<ul>
						<?php foreach ($items as $item) : ?>
							<?php $link = massitpro_normalize_link($item['link'] ?? []); ?>
							<?php if (! $link['url'] || ! $link['label']) : ?>
								<?php continue; ?>
							<?php endif; ?>
							<li>
								<a href="<?php echo esc_url($link['url']); ?>"<?php echo $link['target'] ? ' target="' . esc_attr($link['target']) . '" rel="noopener noreferrer"' : ''; ?>>
									<span><?php echo esc_html($link['label']); ?></span>
									<span aria-hidden="true"><?php echo massitpro_svg_icon('arrow-right'); ?></span>
								</a>
							</li>
						<?php endforeach; ?>
					</ul>
				</div>
			<?php endif; ?>
		</div>
	</section>
	<?php
}

/**
 * Render an FAQ section from a relationship group.
 *
 * @param string              $field_name Section key.
 * @param string              $items_key  Relationship key.
 * @param int                 $post_id    Post ID.
 * @param array<string,mixed> $args       Render arguments.
 */
function massitpro_render_faq_section($field_name, $items_key, $post_id, $args = []) {
	$args    = wp_parse_args(
		(array) $args,
		[
			'surface_class' => '',
			'section_class' => '',
		]
	);
	$group   = (array) massitpro_get_section_meta($field_name, massitpro_get_render_post_id($post_id), []);
	$heading = trim((string) ($group['heading'] ?? ''));
	$body    = (string) ($group['body'] ?? '');
	$items   = massitpro_get_faq_items(massitpro_normalize_related_posts($group[$items_key] ?? []));

	if (! massitpro_has_any_content($heading, $body, $items)) {
		return;
	}
	?>
	<section class="section-padding section-spacing <?php echo esc_attr(trim((string) $args['surface_class'] . ' ' . (string) $args['section_class'])); ?>">
		<div class="site-shell site-shell--faq">
			<div class="faq-split">
				<?php if ($heading || $body) : ?>
					<div class="faq-split__intro" data-reveal>
						<?php massitpro_render_section_heading(['title' => $heading, 'copy' => $body]); ?>
					</div>
				<?php endif; ?>
				<div class="faq-split__list">
				<?php massitpro_render_accordion_items($items); ?>
				</div>
			</div>
		</div>
	</section>
	<?php
}

/**
 * Render a CTA block section.
 *
 * @param int                 $post_id Post ID.
 * @param array<string,mixed> $args    Render arguments.
 */
function massitpro_render_cta_block($post_id, $args = []) {
	$args    = wp_parse_args(
		(array) $args,
		[
			'section_class' => '',
		]
	);
	$group   = (array) massitpro_get_section_meta('cta_block', massitpro_get_render_post_id($post_id), []);
	$eyebrow = trim((string) ($group['eyebrow'] ?? ''));
	$heading = trim((string) ($group['heading'] ?? ''));
	$body    = (string) ($group['body'] ?? '');
	$image   = massitpro_resolve_image_value($group['image'] ?? null);
	$buttons = massitpro_get_buttons($group['buttons'] ?? []);

	if (! massitpro_has_any_content($eyebrow, $heading, $body, $buttons)) {
		return;
	}
	?>
	<section class="section-padding section-spacing surface-navy <?php echo esc_attr((string) $args['section_class']); ?>">
		<div class="site-shell">
			<div class="cta-shell">
				<div class="cta-shell__copy" data-reveal>
					<?php massitpro_render_section_heading(['label' => $eyebrow, 'title' => $heading, 'copy' => $body]); ?>
					<?php massitpro_render_button_row($buttons, 'button-row'); ?>
				</div>
				<?php if ($image) : ?>
					<div class="cta-shell__media" data-reveal><?php massitpro_render_media(['image' => $image, 'aspect' => 'video']); ?></div>
				<?php endif; ?>
			</div>
		</div>
	</section>
	<?php
}

/**
 * Render a content section from page content and optional featured image.
 *
 * @param int $post_id Post ID.
 */
function massitpro_render_default_page_body($post_id = 0) {
	$post_id = massitpro_get_render_post_id($post_id);
	$content = massitpro_get_post_content_html($post_id);
	$image   = massitpro_get_post_display_image($post_id);

	if (! massitpro_has_any_content($content, $image)) {
		return;
	}
	?>
	<section class="section-padding section-spacing">
		<div class="site-shell<?php echo $image ? '' : ' site-shell--content'; ?>">
			<div class="split-feature<?php echo $image ? '' : ' split-feature--single'; ?>">
				<?php if ($content) : ?>
					<div class="split-feature__copy content-card entry-content" data-reveal><?php echo wp_kses_post($content); ?></div>
				<?php endif; ?>
				<?php if ($image) : ?>
					<div class="split-feature__media" data-reveal><?php massitpro_render_media(['image' => $image, 'aspect' => 'wide']); ?></div>
				<?php endif; ?>
			</div>
		</div>
	</section>
	<?php
}

/**
 * Render a spotlight section with one image and optional link.
 *
 * @param string              $field_name Section key.
 * @param int                 $post_id    Post ID.
 * @param array<string,mixed> $args       Render arguments.
 */
function massitpro_render_spotlight_section($field_name, $post_id, $args = []) {
	$args    = wp_parse_args(
		(array) $args,
		[
			'surface_class' => '',
			'section_class' => '',
			'reverse'       => false,
		]
	);
	$group   = (array) massitpro_get_section_meta($field_name, massitpro_get_render_post_id($post_id), []);
	$eyebrow = trim((string) ($group['eyebrow'] ?? ''));
	$heading = trim((string) ($group['heading'] ?? ''));
	$body    = (string) ($group['body'] ?? '');
	$image   = massitpro_resolve_image_value($group['image'] ?? null);
	$link    = massitpro_normalize_link($group['link'] ?? []);

	if (! massitpro_has_any_content($eyebrow, $heading, $body, $link['url'], $link['label'])) {
		return;
	}
	?>
	<section class="section-padding section-spacing <?php echo esc_attr(trim((string) $args['surface_class'] . ' ' . (string) $args['section_class'])); ?>">
		<div class="site-shell">
			<div class="split-feature<?php echo $image ? '' : ' split-feature--single'; ?><?php echo ! empty($args['reverse']) ? ' split-feature--reverse' : ''; ?>">
				<div class="split-feature__copy content-card entry-content" data-reveal>
					<?php if ($eyebrow) : ?>
						<p class="section-label"><?php echo esc_html($eyebrow); ?></p>
					<?php endif; ?>
					<?php if ($heading) : ?>
						<h2><?php echo esc_html($heading); ?></h2>
					<?php endif; ?>
					<?php if ($body) : ?>
						<div class="section-copy section-copy--rich"><?php echo wp_kses_post($body); ?></div>
					<?php endif; ?>
					<?php if ($link['url'] && $link['label']) : ?>
						<div class="button-row">
							<?php massitpro_render_button(['label' => $link['label'], 'url' => $link['url'], 'target' => $link['target'], 'variant' => 'action', 'size' => 'default']); ?>
						</div>
					<?php endif; ?>
				</div>
				<?php if ($image) : ?>
					<div class="split-feature__media" data-reveal><?php massitpro_render_media(['image' => $image, 'aspect' => 'video']); ?></div>
				<?php endif; ?>
			</div>
		</div>
	</section>
	<?php
}

/**
 * Render a stats band from a native stats section.
 *
 * @param string              $field_name Section key.
 * @param int                 $post_id    Post ID.
 * @param array<string,mixed> $args       Render arguments.
 */
function massitpro_render_stats_band_section($field_name, $post_id, $args = []) {
	$args    = wp_parse_args(
		(array) $args,
		[
			'surface_class' => 'surface-sand',
			'section_class' => '',
			'heading_align' => 'center',
		]
	);
	$group   = (array) massitpro_get_section_meta($field_name, massitpro_get_render_post_id($post_id), []);
	$items   = massitpro_filter_rows((array) ($group['items'] ?? []), ['value', 'label', 'description']);

	if (! massitpro_has_any_content($group['heading'] ?? '', $group['body'] ?? '', $items, $group['eyebrow'] ?? '')) {
		return;
	}
	?>
	<section class="section-padding section-spacing <?php echo esc_attr(trim((string) $args['surface_class'] . ' ' . (string) $args['section_class'])); ?>">
		<div class="site-shell">
			<?php massitpro_render_section_heading(['label' => $group['eyebrow'] ?? '', 'title' => $group['heading'] ?? '', 'copy' => $group['body'] ?? '', 'align' => $args['heading_align']]); ?>
			<?php if ($items) : ?>
				<div class="stats-grid cards-grid--4">
					<?php foreach ($items as $index => $item) : ?>
						<article class="content-card stat-card" data-reveal style="transition-delay: <?php echo esc_attr(number_format($index * 0.05, 2, '.', '')); ?>s;">
							<div class="card-icon" aria-hidden="true"><?php echo massitpro_svg_icon((string) ($item['icon'] ?? 'clock')); ?></div>
							<div class="stat-card__value"><?php echo esc_html((string) ($item['value'] ?? '')); ?></div>
							<h3><?php echo esc_html((string) ($item['label'] ?? '')); ?></h3>
							<?php if (! empty($item['description'])) : ?>
								<p><?php echo esc_html((string) $item['description']); ?></p>
							<?php endif; ?>
						</article>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>
	</section>
	<?php
}

/**
 * Render a cards grid driven by manual card entries with a link.
 *
 * @param string              $field_name Section key.
 * @param int                 $post_id    Post ID.
 * @param array<string,mixed> $opts       Render arguments.
 */
function massitpro_render_link_cards_section($field_name, $post_id, $opts = []) {
	$opts    = wp_parse_args(
		(array) $opts,
		[
			'surface_class' => '',
			'section_class' => '',
			'card_class'    => '',
			'cards_class'   => 'cards-grid cards-grid--3',
		]
	);
	$group   = (array) massitpro_get_section_meta($field_name, massitpro_get_render_post_id($post_id), []);
	$eyebrow = trim((string) ($group['eyebrow'] ?? ''));
	$heading = trim((string) ($group['heading'] ?? ''));
	$body    = (string) ($group['body'] ?? '');
	$items   = massitpro_filter_rows((array) ($group['items'] ?? []), ['title', 'body', 'link_label', 'link_url']);

	if (! massitpro_has_any_content($eyebrow, $heading, $body, $items)) {
		return;
	}
	?>
	<section class="section-padding section-spacing <?php echo esc_attr(trim((string) $opts['surface_class'] . ' ' . (string) $opts['section_class'])); ?>">
		<div class="site-shell">
			<?php massitpro_render_section_heading(['label' => $eyebrow, 'title' => $heading, 'copy' => $body]); ?>
			<?php if ($items) : ?>
				<div class="<?php echo esc_attr((string) $opts['cards_class']); ?>">
					<?php foreach ($items as $index => $item) : ?>
						<?php
						$title      = trim((string) ($item['title'] ?? ''));
						$body_text  = trim((string) ($item['body'] ?? ''));
						$icon       = (string) ($item['icon'] ?? 'check');
						$link_label = trim((string) ($item['link_label'] ?? ''));
						$link_url   = trim((string) ($item['link_url'] ?? ''));
						?>
						<article class="content-card icon-feature-card <?php echo esc_attr((string) $opts['card_class']); ?>" data-reveal style="transition-delay: <?php echo esc_attr(number_format($index * 0.05, 2, '.', '')); ?>s;">
							<?php if ($icon) : ?>
								<div class="card-icon" aria-hidden="true"><?php echo massitpro_svg_icon($icon); ?></div>
							<?php endif; ?>
							<?php if ($title) : ?>
								<h3>
									<?php if ($link_url) : ?>
										<a href="<?php echo esc_url($link_url); ?>"><?php echo esc_html($title); ?></a>
									<?php else : ?>
										<?php echo esc_html($title); ?>
									<?php endif; ?>
								</h3>
							<?php endif; ?>
							<?php if ($body_text) : ?>
								<p><?php echo esc_html($body_text); ?></p>
							<?php endif; ?>
							<?php if ($link_url && $link_label) : ?>
								<a class="section-link section-link--inline" href="<?php echo esc_url($link_url); ?>">
									<span><?php echo esc_html($link_label); ?></span>
									<span aria-hidden="true"><?php echo massitpro_svg_icon('arrow-right'); ?></span>
								</a>
							<?php elseif ($link_url) : ?>
								<a class="section-link section-link--inline" href="<?php echo esc_url($link_url); ?>">
									<span><?php esc_html_e('Learn More', 'massitpro'); ?></span>
									<span aria-hidden="true"><?php echo massitpro_svg_icon('arrow-right'); ?></span>
								</a>
							<?php endif; ?>
						</article>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>
	</section>
	<?php
}

/**
 * Render an accordion FAQ section from manual question/answer cards.
 *
 * @param string              $field_name Section key.
 * @param int                 $post_id    Post ID.
 * @param array<string,mixed> $opts       Render arguments.
 */
function massitpro_render_faq_cards_section($field_name, $post_id, $opts = []) {
	$opts    = wp_parse_args(
		(array) $opts,
		[
			'surface_class' => '',
			'section_class' => '',
		]
	);
	$group   = (array) massitpro_get_section_meta($field_name, massitpro_get_render_post_id($post_id), []);
	$heading = trim((string) ($group['heading'] ?? ''));
	$body    = (string) ($group['body'] ?? '');
	$items   = [];

	foreach ((array) ($group['items'] ?? []) as $row) {
		$question = trim((string) ($row['question'] ?? ''));
		$answer   = trim((string) ($row['answer'] ?? ''));

		if (! $question || ! $answer) {
			continue;
		}

		$items[] = [
			'question' => $question,
			'answer'   => wpautop($answer),
		];
	}

	if (! massitpro_has_any_content($heading, $body, $items)) {
		return;
	}
	?>
	<section class="section-padding section-spacing <?php echo esc_attr(trim((string) $opts['surface_class'] . ' ' . (string) $opts['section_class'])); ?>">
		<div class="site-shell site-shell--faq">
			<div class="faq-split">
				<?php if ($heading || $body) : ?>
					<div class="faq-split__intro" data-reveal>
						<?php massitpro_render_section_heading(['title' => $heading, 'copy' => $body]); ?>
					</div>
				<?php endif; ?>
				<div class="faq-split__list">
					<?php massitpro_render_accordion_items($items); ?>
				</div>
			</div>
		</div>
	</section>
	<?php
}

/**
 * Render a testimonial cards grid from manual card entries.
 *
 * @param string              $field_name Section key.
 * @param int                 $post_id    Post ID.
 * @param array<string,mixed> $opts       Render arguments.
 */
function massitpro_render_manual_testimonials_section($field_name, $post_id, $opts = []) {
	$opts    = wp_parse_args(
		(array) $opts,
		[
			'surface_class' => '',
			'section_class' => '',
			'cards_class'   => 'cards-grid cards-grid--3',
		]
	);
	$group   = (array) massitpro_get_section_meta($field_name, massitpro_get_render_post_id($post_id), []);
	$heading = trim((string) ($group['heading'] ?? ''));
	$body    = (string) ($group['body'] ?? '');
	$items   = massitpro_filter_rows((array) ($group['items'] ?? []), ['quote', 'name', 'company']);

	if (! massitpro_has_any_content($heading, $body, $items)) {
		return;
	}
	?>
	<section class="section-padding section-spacing <?php echo esc_attr(trim((string) $opts['surface_class'] . ' ' . (string) $opts['section_class'])); ?>">
		<div class="site-shell">
			<?php massitpro_render_section_heading(['title' => $heading, 'copy' => $body]); ?>
			<?php if ($items) : ?>
				<div class="<?php echo esc_attr((string) $opts['cards_class']); ?>">
					<?php foreach ($items as $index => $item) : ?>
						<?php
						$quote    = trim((string) ($item['quote'] ?? ''));
						$name     = trim((string) ($item['name'] ?? ''));
						$role     = trim((string) ($item['role'] ?? ''));
						$company  = trim((string) ($item['company'] ?? ''));
						$industry = trim((string) ($item['industry_tag'] ?? ''));
						?>
						<article class="content-card testimonial-card" data-industry="<?php echo esc_attr($industry); ?>" data-reveal style="transition-delay: <?php echo esc_attr(number_format($index * 0.05, 2, '.', '')); ?>s;">
							<div class="testimonial-stars testimonial-stars--left" aria-hidden="true">
								<?php for ($star = 0; $star < 5; $star++) : ?>
									<?php echo massitpro_svg_icon('star'); ?>
								<?php endfor; ?>
							</div>
							<?php if ($quote) : ?>
								<blockquote><?php echo esc_html($quote); ?></blockquote>
							<?php endif; ?>
							<?php if ($name || $role || $company || $industry) : ?>
								<div class="testimonial-meta testimonial-meta--left">
									<?php if ($name) : ?>
										<span class="testimonial-meta__name"><?php echo esc_html($name); ?></span>
									<?php endif; ?>
									<?php if ($role || $company) : ?>
										<span><?php echo esc_html(trim($role . ($role && $company ? ', ' : '') . $company)); ?></span>
									<?php endif; ?>
									<?php if ($industry) : ?>
										<span class="chip chip--teal"><?php echo esc_html($industry); ?></span>
									<?php endif; ?>
								</div>
							<?php endif; ?>
						</article>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>
	</section>
	<?php
}

/**
 * Render the About page team highlights split section.
 *
 * @param string $meta_key Section key.
 * @param int    $post_id  Post ID.
 */
function massitpro_render_team_highlights_section($meta_key, $post_id) {
	$post_id = massitpro_get_render_post_id($post_id);
	$group   = (array) massitpro_get_section_meta($meta_key, $post_id, []);
	$eyebrow = trim((string) ($group['eyebrow'] ?? ''));
	$heading = trim((string) ($group['heading'] ?? ''));
	$body    = (string) ($group['body'] ?? '');
	$items   = massitpro_filter_rows((array) ($group['items'] ?? []), ['title', 'body']);
	$image   = massitpro_resolve_image_value($group['image'] ?? null);

	if (! $image) {
		$image = massitpro_resolve_image_value(massitpro_get_native_meta('team_image', $post_id));
	}

	if (! massitpro_has_any_content($eyebrow, $heading, $body, $items)) {
		return;
	}
	?>
	<section class="section-padding section-spacing">
		<div class="site-shell">
			<div class="split-feature<?php echo $image ? '' : ' split-feature--single'; ?>">
				<?php if ($image) : ?>
					<div class="split-feature__media" data-reveal>
						<?php massitpro_render_media(['image' => $image, 'aspect' => 'tall']); ?>
					</div>
				<?php endif; ?>
				<div class="split-feature__copy" data-reveal>
					<?php if ($eyebrow || $heading || $body) : ?>
						<div class="content-card entry-content">
							<?php if ($eyebrow) : ?>
								<p class="section-label"><?php echo esc_html($eyebrow); ?></p>
							<?php endif; ?>
							<?php if ($heading) : ?>
								<h2><?php echo esc_html($heading); ?></h2>
							<?php endif; ?>
							<?php if ($body) : ?>
								<div class="section-copy section-copy--rich"><?php echo wp_kses_post($body); ?></div>
							<?php endif; ?>
						</div>
					<?php endif; ?>
					<?php foreach ($items as $index => $item) : ?>
						<article class="content-card icon-feature-card" data-reveal style="transition-delay: <?php echo esc_attr(number_format($index * 0.05, 2, '.', '')); ?>s;">
							<div class="soft-icon" aria-hidden="true"><?php echo massitpro_svg_icon((string) ($item['icon'] ?? 'check')); ?></div>
							<?php if (! empty($item['title'])) : ?>
								<h3><?php echo esc_html((string) $item['title']); ?></h3>
							<?php endif; ?>
							<?php if (! empty($item['body'])) : ?>
								<p><?php echo esc_html((string) $item['body']); ?></p>
							<?php endif; ?>
						</article>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</section>
	<?php
}

/**
 * Render the About page certifications pill row.
 *
 * @param string $meta_key Section key.
 * @param int    $post_id  Post ID.
 */
function massitpro_render_certifications_section($meta_key, $post_id) {
	$post_id = massitpro_get_render_post_id($post_id);
	$group   = (array) massitpro_get_section_meta($meta_key, $post_id, []);
	$eyebrow = trim((string) ($group['eyebrow'] ?? ''));
	$heading = trim((string) ($group['heading'] ?? ''));
	$body    = (string) ($group['body'] ?? '');
	$items   = massitpro_filter_rows((array) ($group['items'] ?? []), ['title']);

	if (! $items) {
		return;
	}
	?>
	<section class="section-padding section-spacing surface-sand">
		<div class="site-shell">
			<?php massitpro_render_section_heading(['label' => $eyebrow, 'title' => $heading, 'copy' => $body, 'align' => 'center']); ?>
			<div class="chips" data-reveal>
				<?php foreach ($items as $item) : ?>
					<?php $title = trim((string) ($item['title'] ?? '')); ?>
					<?php if (! $title) : ?>
						<?php continue; ?>
					<?php endif; ?>
					<span class="chip content-card">
						<span class="soft-icon" aria-hidden="true"><?php echo massitpro_svg_icon('check'); ?></span>
						<span><?php echo esc_html($title); ?></span>
					</span>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
	<?php
}

/**
 * Render the featured testimonial split block on the testimonials page.
 *
 * @param int $post_id Post ID.
 */
function massitpro_render_featured_testimonial_section($post_id) {
	$post_id = massitpro_get_render_post_id($post_id);
	$group   = (array) massitpro_get_section_meta('featured_testimonial', $post_id, []);
	$quote   = trim((string) ($group['featured_quote'] ?? ''));
	$name    = trim((string) ($group['featured_name'] ?? ''));
	$role    = trim((string) ($group['featured_role'] ?? ''));
	$company = trim((string) ($group['featured_company'] ?? ''));
	$industry = trim((string) ($group['featured_industry'] ?? ''));
	$image   = massitpro_resolve_image_value($group['featured_image'] ?? null);

	if (! $quote) {
		return;
	}
	?>
	<section class="section-padding section-spacing">
		<div class="site-shell">
			<div class="content-card split-feature<?php echo $image ? '' : ' split-feature--single'; ?>" data-reveal>
				<div class="split-feature__copy entry-content">
					<blockquote><?php echo esc_html($quote); ?></blockquote>
					<div class="testimonial-stars testimonial-stars--left" aria-hidden="true">
						<?php for ($star = 0; $star < 5; $star++) : ?>
							<?php echo massitpro_svg_icon('star'); ?>
						<?php endfor; ?>
					</div>
					<?php if ($name) : ?>
						<p class="testimonial-meta__name"><?php echo esc_html($name); ?></p>
					<?php endif; ?>
					<?php if ($role || $company) : ?>
						<p><?php echo esc_html(trim($role . ($role && $company ? ', ' : '') . $company)); ?></p>
					<?php endif; ?>
					<?php if ($industry) : ?>
						<span class="chip chip--teal"><?php echo esc_html($industry); ?></span>
					<?php endif; ?>
				</div>
				<?php if ($image) : ?>
					<div class="split-feature__media">
						<?php massitpro_render_media(['image' => $image, 'aspect' => 'video', 'class' => 'split-feature__media']); ?>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</section>
	<?php
}

/**
 * Render the testimonial filter tabs and card grid from manual card entries.
 *
 * @param int $post_id Post ID.
 */
function massitpro_render_testimonial_filter_grid($post_id) {
	$post_id = massitpro_get_render_post_id($post_id);
	$group   = (array) massitpro_get_section_meta('testimonials_section', $post_id, []);
	$items   = massitpro_filter_rows((array) ($group['items'] ?? []), ['quote', 'name', 'company']);

	if (! $items) {
		return;
	}

	$tags = ['all'];

	foreach ($items as $item) {
		$tag = trim((string) ($item['industry_tag'] ?? ''));

		if ($tag && ! in_array($tag, $tags, true)) {
			$tags[] = $tag;
		}
	}
	?>
	<section class="section-padding section-spacing">
		<div class="site-shell">
			<div class="filter-row" data-reveal>
				<?php foreach ($tags as $index => $tag) : ?>
					<button type="button" class="filter-btn filter-chip<?php echo 0 === $index ? ' is-active' : ''; ?>" data-filter="<?php echo esc_attr($tag); ?>">
						<?php echo esc_html('all' === $tag ? __('All', 'massitpro') : $tag); ?>
					</button>
				<?php endforeach; ?>
			</div>
			<div class="cards-grid cards-grid--3">
				<?php foreach ($items as $index => $item) : ?>
					<?php
					$quote    = trim((string) ($item['quote'] ?? ''));
					$name     = trim((string) ($item['name'] ?? ''));
					$role     = trim((string) ($item['role'] ?? ''));
					$company  = trim((string) ($item['company'] ?? ''));
					$industry = trim((string) ($item['industry_tag'] ?? ''));
					?>
					<article class="content-card testimonial-card" data-industry="<?php echo esc_attr($industry); ?>" data-reveal style="transition-delay: <?php echo esc_attr(number_format($index * 0.05, 2, '.', '')); ?>s;">
						<div class="testimonial-stars testimonial-stars--left" aria-hidden="true">
							<?php for ($star = 0; $star < 5; $star++) : ?>
								<?php echo massitpro_svg_icon('star'); ?>
							<?php endfor; ?>
						</div>
						<?php if ($quote) : ?>
							<blockquote><?php echo esc_html($quote); ?></blockquote>
						<?php endif; ?>
						<?php if ($name || $role || $company || $industry) : ?>
							<div class="testimonial-meta testimonial-meta--left">
								<?php if ($name) : ?>
									<span class="testimonial-meta__name"><?php echo esc_html($name); ?></span>
								<?php endif; ?>
								<?php if ($role || $company) : ?>
									<span><?php echo esc_html(trim($role . ($role && $company ? ', ' : '') . $company)); ?></span>
								<?php endif; ?>
								<?php if ($industry) : ?>
									<span class="chip chip--teal"><?php echo esc_html($industry); ?></span>
								<?php endif; ?>
							</div>
						<?php endif; ?>
					</article>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
	<?php
}

/**
 * Render the community spotlight split with the image on the left.
 *
 * @param string $meta_key Section key.
 * @param int    $post_id  Post ID.
 */
function massitpro_render_community_spotlight_section($meta_key, $post_id) {
	$post_id = massitpro_get_render_post_id($post_id);
	$group   = (array) massitpro_get_section_meta($meta_key, $post_id, []);
	$heading = trim((string) ($group['heading'] ?? ''));

	if (! $heading) {
		return;
	}

	massitpro_render_spotlight_section($meta_key, $post_id, ['reverse' => true]);
}

/**
 * Render a testimonials grid sourced from the testimonial CPT.
 *
 * @param int                 $post_id Current page post ID.
 * @param array<string,mixed> $opts    Render options.
 */
function massitpro_render_cpt_testimonials_section($post_id = 0, $opts = []) {
	$opts     = wp_parse_args(
		(array) $opts,
		[
			'surface_class' => '',
			'section_class' => '',
			'cards_class'   => 'cards-grid cards-grid--3',
		]
	);
	$post_id  = massitpro_get_render_post_id($post_id);
	$group    = (array) massitpro_get_section_meta('testimonials_section', $post_id, []);
	$eyebrow  = trim((string) ($group['eyebrow'] ?? ''));
	$heading  = trim((string) ($group['heading'] ?? ''));
	$body     = (string) ($group['body'] ?? '');
	$posts    = massitpro_query_random_posts('testimonial', 6);

	if (! $posts) {
		return;
	}

	$items = [];

	foreach ($posts as $testimonial_post) {
		$items[] = massitpro_get_testimonial_data($testimonial_post);
	}
	?>
	<section class="section-padding section-spacing <?php echo esc_attr(trim((string) $opts['surface_class'] . ' ' . (string) $opts['section_class'])); ?>">
		<div class="site-shell">
			<?php massitpro_render_section_heading(['label' => $eyebrow, 'title' => $heading, 'copy' => $body]); ?>
			<div class="<?php echo esc_attr((string) $opts['cards_class']); ?>">
				<?php foreach ($items as $index => $item) : ?>
					<?php
					$quote    = trim((string) ($item['quote'] ?? ''));
					$name     = trim((string) ($item['name'] ?? ''));
					$role     = trim((string) ($item['role'] ?? ''));
					$company  = trim((string) ($item['company'] ?? ''));
					$industry = trim((string) ($item['industry'] ?? ''));
					?>
					<article class="content-card testimonial-card" data-industry="<?php echo esc_attr($industry); ?>" data-reveal style="transition-delay: <?php echo esc_attr(number_format($index * 0.05, 2, '.', '')); ?>s;">
						<div class="testimonial-stars testimonial-stars--left" aria-hidden="true">
							<?php for ($star = 0; $star < 5; $star++) : ?>
								<?php echo massitpro_svg_icon('star'); ?>
							<?php endfor; ?>
						</div>
						<?php if ($quote) : ?>
							<blockquote><?php echo esc_html($quote); ?></blockquote>
						<?php endif; ?>
						<?php if ($name || $role || $company || $industry) : ?>
							<div class="testimonial-meta testimonial-meta--left">
								<?php if ($name) : ?>
									<span class="testimonial-meta__name"><?php echo esc_html($name); ?></span>
								<?php endif; ?>
								<?php if ($role || $company) : ?>
									<span><?php echo esc_html(trim($role . ($role && $company ? ', ' : '') . $company)); ?></span>
								<?php endif; ?>
								<?php if ($industry) : ?>
									<span class="chip chip--teal"><?php echo esc_html($industry); ?></span>
								<?php endif; ?>
							</div>
						<?php endif; ?>
					</article>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
	<?php
}

/**
 * Render the most recent featured testimonial from the CPT.
 *
 * @param int $post_id Current page post ID.
 */
function massitpro_render_cpt_testimonials_featured($post_id = 0) {
	$post_id = massitpro_get_render_post_id($post_id);
	$posts   = massitpro_query_posts(
		[
			'post_type'           => 'testimonial',
			'post_status'         => 'publish',
			'posts_per_page'      => 1,
			'orderby'             => 'date',
			'order'               => 'DESC',
			'ignore_sticky_posts' => true,
		]
	);

	if (! $posts) {
		return;
	}

	$data     = massitpro_get_testimonial_data($posts[0]);
	$quote    = trim((string) ($data['quote'] ?? ''));
	$name     = trim((string) ($data['name'] ?? ''));
	$role     = trim((string) ($data['role'] ?? ''));
	$company  = trim((string) ($data['company'] ?? ''));
	$industry = trim((string) ($data['industry'] ?? ''));
	$image    = massitpro_resolve_image_value($data['image'] ?? null);

	if (! $quote) {
		return;
	}
	?>
	<section class="section-padding section-spacing">
		<div class="site-shell">
			<div class="content-card split-feature<?php echo $image ? '' : ' split-feature--single'; ?>" data-reveal>
				<div class="split-feature__copy entry-content">
					<blockquote><?php echo esc_html($quote); ?></blockquote>
					<div class="testimonial-stars testimonial-stars--left" aria-hidden="true">
						<?php for ($star = 0; $star < 5; $star++) : ?>
							<?php echo massitpro_svg_icon('star'); ?>
						<?php endfor; ?>
					</div>
					<?php if ($name) : ?>
						<p class="testimonial-meta__name"><?php echo esc_html($name); ?></p>
					<?php endif; ?>
					<?php if ($role || $company) : ?>
						<p><?php echo esc_html(trim($role . ($role && $company ? ', ' : '') . $company)); ?></p>
					<?php endif; ?>
					<?php if ($industry) : ?>
						<span class="chip chip--teal"><?php echo esc_html($industry); ?></span>
					<?php endif; ?>
				</div>
				<?php if ($image) : ?>
					<div class="split-feature__media">
						<?php massitpro_render_media(['image' => $image, 'aspect' => 'video']); ?>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</section>
	<?php
}

/**
 * Render a projects grid sourced from the project CPT.
 *
 * @param int                 $post_id Current page post ID.
 * @param array<string,mixed> $opts    Render options.
 */
function massitpro_render_cpt_projects_section($post_id = 0, $opts = []) {
	$opts    = wp_parse_args(
		(array) $opts,
		[
			'surface_class' => '',
			'section_class' => '',
			'cards_class'   => 'cards-grid cards-grid--3',
		]
	);
	$post_id = massitpro_get_render_post_id($post_id);
	$group   = (array) massitpro_get_section_meta('projects_section', $post_id, []);
	$eyebrow = trim((string) ($group['eyebrow'] ?? ''));
	$heading = trim((string) ($group['heading'] ?? ''));
	$body    = (string) ($group['body'] ?? '');
	$posts   = massitpro_query_random_posts('project', 3);

	if (! $posts) {
		return;
	}

	$items = [];

	foreach ($posts as $project_post) {
		$items[] = massitpro_get_project_data($project_post);
	}

	$projects_entry = massitpro_get_canonical_page('projects');
	$projects_url   = $projects_entry ? massitpro_entry_url($projects_entry) : '';
	?>
	<section class="section-padding section-spacing <?php echo esc_attr(trim((string) $opts['surface_class'] . ' ' . (string) $opts['section_class'])); ?>">
		<div class="site-shell">
			<?php massitpro_render_section_heading(['label' => $eyebrow, 'title' => $heading, 'copy' => $body]); ?>
			<div class="<?php echo esc_attr((string) $opts['cards_class']); ?>">
				<?php foreach ($items as $index => $item) : ?>
					<?php
					$title    = trim((string) ($item['title'] ?? ''));
					$desc     = trim((string) ($item['desc'] ?? ''));
					$category = trim((string) ($item['category'] ?? ''));
					$subtitle = trim((string) ($item['subtitle'] ?? ''));
					$label    = $category ?: $subtitle;
					$image    = massitpro_resolve_image_value($item['image'] ?? null);
					$link     = trim((string) ($item['link'] ?? ''));
					?>
					<article class="content-card media-card feature-grid-card<?php echo $image ? '' : ' feature-grid-card--text'; ?>" data-reveal style="transition-delay: <?php echo esc_attr(number_format($index * 0.05, 2, '.', '')); ?>s;">
						<?php if ($image && $link) : ?>
							<a href="<?php echo esc_url($link); ?>">
								<?php massitpro_render_media(['image' => $image, 'aspect' => 'video']); ?>
							</a>
						<?php elseif ($image) : ?>
							<?php massitpro_render_media(['image' => $image, 'aspect' => 'video']); ?>
						<?php endif; ?>
						<div class="media-card__body">
							<?php if ($label) : ?>
								<div class="meta-row">
									<span class="chip chip--teal"><?php echo esc_html($label); ?></span>
								</div>
							<?php endif; ?>
							<?php if ($title) : ?>
								<h3>
									<?php if ($link) : ?>
										<a href="<?php echo esc_url($link); ?>"><?php echo esc_html($title); ?></a>
									<?php else : ?>
										<?php echo esc_html($title); ?>
									<?php endif; ?>
								</h3>
							<?php endif; ?>
							<?php if ($desc) : ?>
								<p><?php echo esc_html($desc); ?></p>
							<?php endif; ?>
						</div>
					</article>
				<?php endforeach; ?>
			</div>
			<?php if ($projects_url) : ?>
				<div class="button-row button-row--center">
					<?php
					massitpro_render_button(
						[
							'label'   => __('View All Projects', 'massitpro'),
							'url'     => $projects_url,
							'variant' => 'action',
							'size'    => 'default',
						]
					);
					?>
				</div>
			<?php endif; ?>
		</div>
	</section>
	<?php
}

/**
 * Render a blog grid of the most recent posts.
 *
 * @param int $post_id Current page post ID.
 */
function massitpro_render_cpt_blog_section($post_id = 0) {
	$post_id = massitpro_get_render_post_id($post_id);
	$group   = (array) massitpro_get_section_meta('blog_section', $post_id, []);
	$eyebrow = trim((string) ($group['eyebrow'] ?? ''));
	$heading = trim((string) ($group['heading'] ?? ''));
	$body    = (string) ($group['body'] ?? '');
	$posts   = massitpro_query_posts(
		[
			'post_type'           => 'post',
			'post_status'         => 'publish',
			'posts_per_page'      => 3,
			'ignore_sticky_posts' => true,
		]
	);

	if (! $posts) {
		return;
	}
	?>
	<section class="section-padding section-spacing">
		<div class="site-shell">
			<?php massitpro_render_section_heading(['label' => $eyebrow, 'title' => $heading, 'copy' => $body]); ?>
			<div class="cards-grid cards-grid--3">
				<?php foreach ($posts as $index => $post) : ?>
					<?php massitpro_render_post_card($post, $index); ?>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
	<?php
}

/**
 * Render the FAQ "Quick Answers" split-feature section.
 *
 * @param string $meta_key Section meta key.
 * @param int    $post_id  Post ID.
 */
function massitpro_render_faq_quick_answers($meta_key, $post_id) {
	$post_id = massitpro_get_render_post_id($post_id);
	$group   = (array) massitpro_get_section_meta($meta_key, $post_id, []);
	$eyebrow = trim((string) ($group['eyebrow'] ?? ''));
	$heading = trim((string) ($group['heading'] ?? ''));
	$image   = $group['image'] ?? null;
	$items   = [];

	foreach ((array) ($group['items'] ?? []) as $row) {
		$question = trim((string) ($row['question'] ?? ''));
		$answer   = trim((string) ($row['answer'] ?? ''));

		if (! $question || ! $answer) {
			continue;
		}

		$items[] = [
			'question' => $question,
			'answer'   => $answer,
		];

		if (count($items) >= 4) {
			break;
		}
	}

	if (! $items && ! $heading && ! $eyebrow) {
		return;
	}
	?>
	<section class="section-padding section-spacing">
		<div class="site-shell">
			<div class="split-feature<?php echo $image ? '' : ' split-feature--single'; ?>">
				<div class="split-feature__copy" data-reveal>
					<?php if ($eyebrow) : ?>
						<p class="section-label"><?php echo esc_html($eyebrow); ?></p>
					<?php endif; ?>
					<?php if ($heading) : ?>
						<h2><?php echo esc_html($heading); ?></h2>
					<?php endif; ?>
					<?php if ($items) : ?>
						<div class="faq-quick-answers">
							<?php $total = count($items); ?>
							<?php foreach ($items as $qa_index => $item) : ?>
								<div class="faq-quick-answer<?php echo ($qa_index === $total - 1) ? ' faq-quick-answer--last' : ''; ?>">
									<h3><?php echo esc_html($item['question']); ?></h3>
									<p><?php echo esc_html($item['answer']); ?></p>
								</div>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>
				</div>
				<?php if ($image) : ?>
					<div class="split-feature__media" data-reveal>
						<?php massitpro_render_media(['image' => $image, 'aspect' => 'square', 'class' => 'rounded-3xl']); ?>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</section>
	<?php
}

/**
 * Render the full FAQ accordion section grouped by category.
 *
 * @param string $meta_key Section meta key.
 * @param int    $post_id  Post ID.
 */
function massitpro_render_faq_accordion($meta_key, $post_id) {
	$post_id = massitpro_get_render_post_id($post_id);
	$group   = (array) massitpro_get_section_meta($meta_key, $post_id, []);
	$groups  = [];

	foreach ((array) ($group['items'] ?? []) as $row) {
		$question = trim((string) ($row['question'] ?? ''));
		$answer   = trim((string) ($row['answer'] ?? ''));

		if (! $question || ! $answer) {
			continue;
		}

		$category = trim((string) ($row['category'] ?? ''));
		$label    = '' !== $category ? $category : __('General Questions', 'massitpro');

		$groups[$label][] = [
			'question' => $question,
			'answer'   => wpautop($answer),
		];
	}

	if (! $groups) {
		return;
	}
	?>
	<section class="surface-sand section-padding section-spacing">
		<div class="site-shell site-shell--faq">
			<?php foreach ($groups as $label => $group_items) : ?>
				<div class="faq-group" data-reveal>
					<h2><?php echo esc_html((string) $label); ?></h2>
					<div class="faq-group__list">
						<?php massitpro_render_accordion_items($group_items); ?>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</section>
	<?php
}

/**
 * Render the FAQ "Still Have Questions" two-card grid.
 *
 * @param string $meta_key Section meta key.
 * @param int    $post_id  Post ID.
 */
function massitpro_render_faq_still_have_questions($meta_key, $post_id) {
	$post_id = massitpro_get_render_post_id($post_id);
	$group   = (array) massitpro_get_section_meta($meta_key, $post_id, []);
	$cards   = [];

	foreach ((array) ($group['items'] ?? []) as $row) {
		$title = trim((string) ($row['title'] ?? ''));
		$body  = trim((string) ($row['body'] ?? ''));
		$label = trim((string) ($row['link_label'] ?? ''));
		$url   = trim((string) ($row['link_url'] ?? ''));

		if (! $title && ! $body && ! $label) {
			continue;
		}

		$cards[] = [
			'title'      => $title,
			'body'       => $body,
			'link_label' => $label,
			'link_url'   => $url,
		];

		if (count($cards) >= 2) {
			break;
		}
	}

	if (! $cards) {
		return;
	}
	?>
	<section class="section-padding section-spacing">
		<div class="site-shell">
			<div class="cards-grid cards-grid--2">
				<?php foreach ($cards as $card_index => $card) : ?>
					<?php $variant = 0 === $card_index ? 'action' : 'outline'; ?>
					<article class="content-card" data-reveal style="transition-delay: <?php echo esc_attr(number_format($card_index * 0.05, 2, '.', '')); ?>s;">
						<?php if ($card['title']) : ?>
							<h3><?php echo esc_html($card['title']); ?></h3>
						<?php endif; ?>
						<?php if ($card['body']) : ?>
							<p><?php echo esc_html($card['body']); ?></p>
						<?php endif; ?>
						<?php if ($card['link_label'] && $card['link_url']) : ?>
							<div class="button-row">
								<?php massitpro_render_button([
									'label'   => $card['link_label'],
									'url'     => $card['link_url'],
									'variant' => $variant,
									'size'    => 'lg',
								]); ?>
							</div>
						<?php endif; ?>
					</article>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
	<?php
}

/**
 * Render the FAQ "Related Resources" section — latest 3 blog posts.
 *
 * @param int $post_id Post ID.
 */
function massitpro_render_faq_related_resources($post_id = 0) {
	$post_id = massitpro_get_render_post_id($post_id);
	$posts   = massitpro_query_posts(
		[
			'post_type'           => 'post',
			'post_status'         => 'publish',
			'posts_per_page'      => 3,
			'orderby'             => 'date',
			'order'               => 'DESC',
			'ignore_sticky_posts' => true,
		]
	);

	if (! $posts) {
		return;
	}
	?>
	<section class="surface-sand section-padding section-spacing">
		<div class="site-shell">
			<?php massitpro_render_section_heading([
				'label' => __('Learn More', 'massitpro'),
				'title' => __('Related Resources', 'massitpro'),
				'align' => 'center',
			]); ?>
			<div class="cards-grid cards-grid--3">
				<?php foreach ($posts as $index => $related_post) : ?>
					<?php massitpro_render_post_card($related_post, $index); ?>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
	<?php
}

/**
 * Render the homepage.
 */
function massitpro_render_homepage() {
	$post_id            = massitpro_get_render_post_id();
	$hero_args          = massitpro_prepare_page_hero( $post_id );
	$hero_args['image'] = null;

	massitpro_render_page_hero( $hero_args );
	massitpro_render_homepage_trust_ticker( $post_id );
	massitpro_render_stats_band_section( 'stats_section', $post_id, [
		'surface_class' => 'surface-stone-alt',
		'section_class' => 'hp-why-trust',
	] );
	massitpro_render_services_carousel_section( 'services_carousel_section', $post_id, [
		'surface_class'  => 'surface-sand-warm',
		'carousel_key'   => 'biz-services',
		'view_all_label' => __( 'View Business Services', 'massitpro' ),
		'view_all_url'   => massitpro_homepage_get_page_url( 'services' ) ?: home_url( '/services/' ),
	] );
	massitpro_render_homepage_other_services( $post_id );
	massitpro_render_industries_flipcard_section( 'industries_section', $post_id, [
		'surface_class' => 'surface-stone-alt',
		'carousel_key'  => 'hp-industries',
	] );
	massitpro_render_homepage_locations_pills( $post_id );
	massitpro_render_homepage_projects_section( $post_id );
	massitpro_render_homepage_testimonial_slider( $post_id );
	massitpro_render_homepage_blog_section( $post_id );
	massitpro_render_cta_block( $post_id, [ 'section_class' => 'cta-shell--center' ] );
}

/**
 * Helper: resolve a canonical page URL by key.
 *
 * @param string $key Canonical page key.
 * @return string
 */
function massitpro_homepage_get_page_url( $key ) {
	$entry = massitpro_get_canonical_page( (string) $key );
	return $entry ? massitpro_entry_url( $entry ) : '';
}

/**
 * Render the Trusted Companies ticker section.
 *
 * @param int $post_id Post ID.
 */
function massitpro_render_homepage_trust_ticker( $post_id = 0 ) {
	$post_id = massitpro_get_render_post_id( $post_id );
	$group   = (array) massitpro_get_section_meta( 'trust_strip', $post_id, [] );
	$eyebrow = trim( (string) ( $group['eyebrow'] ?? '' ) );
	$heading = trim( (string) ( $group['heading'] ?? '' ) );
	$items   = massitpro_filter_rows( (array) ( $group['items'] ?? [] ), [ 'label' ] );

	$fallback_companies = [
		'Dell', 'Microsoft', 'Comcast', 'Asus', 'Ring',
		'Sophos', 'TP-Link', 'WordPress', 'Wix', 'Intel',
		'Cisco', 'LastPass', 'Google', 'GoDaddy',
	];

	$labels = [];
	foreach ( $items as $item ) {
		$l = trim( (string) ( $item['label'] ?? '' ) );
		if ( $l ) {
			$labels[] = $l;
		}
	}
	if ( ! $labels ) {
		$labels = $fallback_companies;
	}
	?>
	<section class="section-padding section-spacing-small surface-stone-alt hp-trust-ticker-section">
		<div class="site-shell">
			<?php if ( $eyebrow || $heading ) : ?>
				<div class="hp-trust-ticker__header" data-reveal>
					<?php if ( $eyebrow ) : ?>
						<p class="section-label section-label--center"><?php echo esc_html( $eyebrow ); ?></p>
					<?php endif; ?>
					<?php if ( $heading ) : ?>
						<h2 class="hp-trust-ticker__title"><?php echo esc_html( $heading ); ?></h2>
					<?php endif; ?>
				</div>
			<?php endif; ?>
		</div>
		<div class="hp-trust-ticker" aria-hidden="true">
			<div class="hp-trust-ticker__track">
				<?php for ( $pass = 0; $pass < 2; $pass++ ) : ?>
					<?php foreach ( $labels as $index => $label ) :
						$slug     = sanitize_title( $label );
						$img_path = get_template_directory_uri() . '/assets/images/partners/' . $slug . '.png';
					?>
						<div class="hp-trust-ticker__item">
							<div class="hp-trust-ticker__logo">
								<img
									src="<?php echo esc_url( $img_path ); ?>"
									alt="<?php echo esc_attr( $label ); ?>"
									loading="lazy"
									onerror="this.style.display='none';this.nextElementSibling.style.display='flex';"
								>
								<span class="hp-trust-ticker__name" style="display:none;"><?php echo esc_html( $label ); ?></span>
							</div>
						</div>
					<?php endforeach; ?>
				<?php endfor; ?>
			</div>
		</div>
	</section>
	<?php
}

/**
 * Render the Other Services two-card section.
 *
 * @param int $post_id Post ID.
 */
function massitpro_render_homepage_other_services( $post_id = 0 ) {
	$post_id = massitpro_get_render_post_id( $post_id );
	$group   = (array) massitpro_get_section_meta( 'secondary_services_section', $post_id, [] );
	$eyebrow = trim( (string) ( $group['eyebrow'] ?? '' ) );
	$heading = trim( (string) ( $group['heading'] ?? '' ) );
	$body    = (string) ( $group['body'] ?? '' );
	$items   = massitpro_filter_rows( (array) ( $group['items'] ?? [] ), [ 'title' ] );

	if ( ! massitpro_has_any_content( $eyebrow, $heading, $body, $items ) ) {
		return;
	}
	?>
	<section class="section-padding section-spacing surface-sand-warm">
		<div class="site-shell">
			<?php if ( $eyebrow || $heading || $body ) : ?>
				<div class="section-header section-header--center" data-reveal>
					<div class="section-header__copy">
						<?php if ( $eyebrow ) : ?>
							<p class="section-label"><?php echo esc_html( $eyebrow ); ?></p>
						<?php endif; ?>
						<?php if ( $heading ) : ?>
							<h2><?php echo esc_html( $heading ); ?></h2>
						<?php endif; ?>
						<?php if ( $body ) : ?>
							<div class="section-copy"><?php echo wp_kses_post( $body ); ?></div>
						<?php endif; ?>
					</div>
				</div>
			<?php endif; ?>
			<?php if ( $items ) : ?>
				<div class="other-services-grid">
					<?php foreach ( array_slice( $items, 0, 2 ) as $index => $item ) :
						$title      = trim( (string) ( $item['title'] ?? '' ) );
						$icon       = trim( (string) ( $item['icon'] ?? '' ) );
						$body_text  = trim( (string) ( $item['body'] ?? '' ) );
						$tags_raw   = trim( (string) ( $item['description'] ?? '' ) );
						$link_label = trim( (string) ( $item['link_label'] ?? '' ) );
						$link_url   = trim( (string) ( $item['link_url'] ?? '' ) );
						$image_id   = ! empty( $item['image'] ) ? (int) $item['image'] : 0;
						$img_src    = $image_id ? wp_get_attachment_image_url( $image_id, 'massitpro-card' ) : '';
						$tags       = $tags_raw ? array_values( array_filter( array_map( 'trim', preg_split( '/[,\r\n]+/', $tags_raw ) ) ) ) : [];
					?>
						<article class="other-services-card content-card" data-reveal style="transition-delay: <?php echo esc_attr( number_format( $index * 0.08, 2, '.', '' ) ); ?>s;">
							<div class="other-services-card__media">
								<?php if ( $img_src ) : ?>
									<div class="media-block media-block--video">
										<img src="<?php echo esc_url( $img_src ); ?>" alt="<?php echo esc_attr( $title ); ?>" loading="lazy">
									</div>
								<?php else : ?>
									<div class="other-services-card__placeholder" aria-hidden="true"></div>
								<?php endif; ?>
							</div>
							<div class="other-services-card__body">
								<?php if ( $icon ) : ?>
									<span class="card-icon-chip" aria-hidden="true"><?php echo massitpro_svg_icon( $icon ); ?></span>
								<?php endif; ?>
								<?php if ( $title ) : ?>
									<h4><?php echo esc_html( $title ); ?></h4>
								<?php endif; ?>
								<?php if ( $body_text ) : ?>
									<p><?php echo esc_html( $body_text ); ?></p>
								<?php endif; ?>
								<?php if ( $tags ) : ?>
									<div class="other-services-card__tags">
										<?php foreach ( array_slice( $tags, 0, 4 ) as $tag ) : ?>
											<span class="chip"><?php echo esc_html( $tag ); ?></span>
										<?php endforeach; ?>
									</div>
								<?php endif; ?>
								<?php if ( $link_url ) : ?>
									<a class="section-link section-link--inline" href="<?php echo esc_url( $link_url ); ?>">
										<span><?php echo $link_label ? esc_html( $link_label ) : esc_html__( 'Learn More', 'massitpro' ); ?></span>
										<span aria-hidden="true"><?php echo massitpro_svg_icon( 'arrow-right' ); ?></span>
									</a>
								<?php endif; ?>
							</div>
						</article>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>
	</section>
	<?php
}

/**
 * Render the Service Coverage Areas section as white clickable pills.
 *
 * @param int $post_id Post ID.
 */
function massitpro_render_homepage_locations_pills( $post_id = 0 ) {
	$post_id = massitpro_get_render_post_id( $post_id );
	$group   = (array) massitpro_get_section_meta( 'locations_section', $post_id, [] );
	$eyebrow = trim( (string) ( $group['eyebrow'] ?? '' ) );
	$heading = trim( (string) ( $group['heading'] ?? '' ) );
	$body    = (string) ( $group['body'] ?? '' );
	$items   = massitpro_filter_rows( (array) ( $group['items'] ?? [] ), [ 'title' ] );

	if ( ! massitpro_has_any_content( $eyebrow, $heading, $body, $items ) ) {
		return;
	}
	?>
	<section class="section-padding section-spacing surface-sand-warm">
		<div class="site-shell">
			<div class="section-header section-header--center" data-reveal>
				<div class="section-header__copy">
					<?php if ( $eyebrow ) : ?>
						<p class="section-label"><?php echo esc_html( $eyebrow ); ?></p>
					<?php endif; ?>
					<?php if ( $heading ) : ?>
						<h2><?php echo esc_html( $heading ); ?></h2>
					<?php endif; ?>
					<?php if ( $body ) : ?>
						<div class="section-copy"><?php echo wp_kses_post( $body ); ?></div>
					<?php endif; ?>
				</div>
			</div>
			<?php if ( $items ) : ?>
				<div class="hp-location-pills" data-reveal>
					<?php foreach ( $items as $item ) :
						$name     = trim( (string) ( $item['title'] ?? '' ) );
						$link_url = trim( (string) ( $item['link_url'] ?? '' ) );
						if ( ! $name ) {
							continue;
						}
					?>
						<?php if ( $link_url ) : ?>
							<a class="hp-location-pill" href="<?php echo esc_url( $link_url ); ?>">
						<?php else : ?>
							<span class="hp-location-pill">
						<?php endif; ?>
							<span class="hp-location-pill__icon" aria-hidden="true"><?php echo massitpro_svg_icon( 'map-pin' ); ?></span>
							<span class="hp-location-pill__name"><?php echo esc_html( $name ); ?></span>
						<?php if ( $link_url ) : ?>
							</a>
						<?php else : ?>
							</span>
						<?php endif; ?>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>
	</section>
	<?php
}

/**
 * Render the Featured Projects section with dark navy surface.
 * Header: title/subtitle left, view-all link right. 3 projects.
 *
 * @param int $post_id Post ID.
 */
function massitpro_render_homepage_projects_section( $post_id = 0 ) {
	$post_id = massitpro_get_render_post_id( $post_id );
	$group   = (array) massitpro_get_section_meta( 'projects_section', $post_id, [] );
	$eyebrow = trim( (string) ( $group['eyebrow'] ?? '' ) );
	$heading = trim( (string) ( $group['heading'] ?? '' ) );
	$body    = (string) ( $group['body'] ?? '' );

	$posts = massitpro_query_posts( [
		'post_type'           => 'project',
		'post_status'         => 'publish',
		'posts_per_page'      => 3,
		'orderby'             => [ 'menu_order' => 'ASC', 'date' => 'DESC' ],
		'ignore_sticky_posts' => true,
	] );

	if ( ! $posts ) {
		return;
	}

	$projects_url = massitpro_homepage_get_page_url( 'projects' );
	?>
	<section class="section-padding section-spacing surface-navy">
		<div class="site-shell">
			<div class="hp-projects-header" data-reveal>
				<div class="hp-projects-header__copy">
					<?php if ( $eyebrow ) : ?>
						<p class="section-label section-label--dark"><?php echo esc_html( $eyebrow ); ?></p>
					<?php endif; ?>
					<?php if ( $heading ) : ?>
						<h2><?php echo esc_html( $heading ); ?></h2>
					<?php else : ?>
						<h2><?php esc_html_e( 'Featured Projects', 'massitpro' ); ?></h2>
					<?php endif; ?>
					<?php if ( $body ) : ?>
						<div class="section-copy"><?php echo wp_kses_post( $body ); ?></div>
					<?php endif; ?>
				</div>
				<?php if ( $projects_url ) : ?>
					<a class="hp-view-all-link" href="<?php echo esc_url( $projects_url ); ?>">
						<span><?php esc_html_e( 'View All Projects', 'massitpro' ); ?></span>
						<span aria-hidden="true"><?php echo massitpro_svg_icon( 'arrow-right' ); ?></span>
					</a>
				<?php endif; ?>
			</div>
			<div class="cards-grid cards-grid--3">
				<?php foreach ( $posts as $index => $post ) :
					$data     = massitpro_get_project_data( $post );
					$title    = trim( (string) ( $data['title'] ?? '' ) );
					$subtitle = trim( (string) ( $data['subtitle'] ?? '' ) );
					$category = trim( (string) ( $data['category'] ?? '' ) );
					$desc     = trim( (string) ( $data['desc'] ?? '' ) );
					$image    = massitpro_resolve_image_value( $data['image'] ?? null );
					$link     = trim( (string) ( $data['link'] ?? '' ) );
				?>
					<article class="content-card media-card feature-grid-card feature-grid-card--dark hp-project-card" data-reveal style="transition-delay: <?php echo esc_attr( number_format( $index * 0.05, 2, '.', '' ) ); ?>s;">
						<?php if ( $image ) : ?>
							<a href="<?php echo esc_url( $link ); ?>">
								<?php massitpro_render_media( [ 'image' => $image, 'aspect' => 'video' ] ); ?>
							</a>
						<?php else : ?>
							<div class="location-image-placeholder" aria-hidden="true"></div>
						<?php endif; ?>
						<div class="media-card__body hp-project-card__body">
							<?php if ( $category ) : ?>
								<div class="meta-row">
									<span class="chip chip--dark"><?php echo esc_html( $category ); ?></span>
								</div>
							<?php endif; ?>
							<?php if ( $subtitle ) : ?>
								<p class="section-label section-label--dark hp-project-card__eyebrow"><?php echo esc_html( $subtitle ); ?></p>
							<?php endif; ?>
							<?php if ( $title ) : ?>
								<h2 class="hp-project-card__title">
									<?php if ( $link ) : ?>
										<a href="<?php echo esc_url( $link ); ?>"><?php echo esc_html( $title ); ?></a>
									<?php else : ?>
										<?php echo esc_html( $title ); ?>
									<?php endif; ?>
								</h2>
							<?php endif; ?>
							<?php if ( $desc ) : ?>
								<div class="hp-project-card__detail">
									<h3><?php esc_html_e( 'Overview', 'massitpro' ); ?></h3>
									<p><?php echo esc_html( $desc ); ?></p>
								</div>
							<?php endif; ?>
						</div>
					</article>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
	<?php
}

/**
 * Render the homepage testimonial slider: 1 at a time, 3 most recent.
 *
 * @param int $post_id Post ID.
 */
function massitpro_render_homepage_testimonial_slider( $post_id = 0 ) {
	$posts = massitpro_query_posts( [
		'post_type'           => 'testimonial',
		'post_status'         => 'publish',
		'posts_per_page'      => 3,
		'orderby'             => 'date',
		'order'               => 'DESC',
		'ignore_sticky_posts' => true,
	] );

	if ( ! $posts ) {
		return;
	}

	$items = [];
	foreach ( $posts as $p ) {
		$items[] = massitpro_get_testimonial_data( $p );
	}
	?>
	<section class="section-padding section-spacing surface-stone-alt">
		<div class="site-shell">
			<div class="testimonial-slider" data-testimonial-slider>
				<?php foreach ( $items as $index => $item ) :
					$quote   = trim( (string) ( $item['quote'] ?? '' ) );
					$name    = trim( (string) ( $item['name'] ?? '' ) );
					$role    = trim( (string) ( $item['role'] ?? '' ) );
					$company = trim( (string) ( $item['company'] ?? '' ) );
					if ( ! $quote ) {
						continue;
					}
				?>
					<div class="testimonial-slide" data-testimonial-slide>
						<div class="testimonial-stars" aria-hidden="true">
							<?php for ( $star = 0; $star < 5; $star++ ) : ?>
								<?php echo massitpro_svg_icon( 'star' ); ?>
							<?php endfor; ?>
						</div>
						<blockquote><?php echo esc_html( $quote ); ?></blockquote>
						<div class="testimonial-meta">
							<?php if ( $name ) : ?>
								<span class="testimonial-meta__name"><?php echo esc_html( $name ); ?></span>
							<?php endif; ?>
							<?php if ( $role || $company ) : ?>
								<span><?php echo esc_html( trim( $role . ( $role && $company ? ', ' : '' ) . $company ) ); ?></span>
							<?php endif; ?>
						</div>
					</div>
				<?php endforeach; ?>
				<div class="testimonial-slider__controls">
					<button class="icon-button" data-testimonial-prev aria-label="<?php esc_attr_e( 'Previous', 'massitpro' ); ?>">
						<?php echo massitpro_svg_icon( 'arrow-left' ); ?>
					</button>
					<div class="testimonial-dots">
						<?php foreach ( $items as $dot_index => $_ ) : ?>
							<button class="testimonial-dot" data-testimonial-dot aria-label="<?php echo esc_attr( sprintf( __( 'Testimonial %d', 'massitpro' ), $dot_index + 1 ) ); ?>"></button>
						<?php endforeach; ?>
					</div>
					<button class="icon-button" data-testimonial-next aria-label="<?php esc_attr_e( 'Next', 'massitpro' ); ?>">
						<?php echo massitpro_svg_icon( 'arrow-right' ); ?>
					</button>
				</div>
			</div>
		</div>
	</section>
	<?php
}

/**
 * Render the homepage blog section with truncated excerpts.
 * Header: title/subtitle left, view-all link right. 3 articles.
 *
 * @param int $post_id Post ID.
 */
function massitpro_render_homepage_blog_section( $post_id = 0 ) {
	$post_id      = massitpro_get_render_post_id( $post_id );
	$group        = (array) massitpro_get_section_meta( 'blog_section', $post_id, [] );
	$eyebrow      = trim( (string) ( $group['eyebrow'] ?? '' ) );
	$heading      = trim( (string) ( $group['heading'] ?? '' ) );
	$body         = (string) ( $group['body'] ?? '' );
	$posts_count  = max( 1, min( 6, (int) ( $group['posts_count'] ?? 3 ) ) );
	$pinned_posts = massitpro_normalize_related_posts( $group['posts'] ?? [] );

	if ( ! $pinned_posts ) {
		$pinned_posts = massitpro_query_posts( [
			'post_type'           => 'post',
			'post_status'         => 'publish',
			'posts_per_page'      => $posts_count,
			'orderby'             => 'date',
			'order'               => 'DESC',
			'ignore_sticky_posts' => true,
		] );
	}

	if ( ! massitpro_has_any_content( $heading, $body, $pinned_posts ) ) {
		return;
	}

	$blog_url = get_permalink( get_option( 'page_for_posts' ) ) ?: home_url( '/blog/' );
	?>
	<section class="section-padding section-spacing surface-sand-warm">
		<div class="site-shell">
			<div class="hp-section-top-bar" data-reveal>
				<div class="hp-section-top-bar__copy">
					<?php if ( $eyebrow ) : ?>
						<p class="section-label"><?php echo esc_html( $eyebrow ); ?></p>
					<?php endif; ?>
					<?php if ( $heading ) : ?>
						<h2><?php echo esc_html( $heading ); ?></h2>
					<?php endif; ?>
					<?php if ( $body ) : ?>
						<div class="section-copy"><?php echo wp_kses_post( $body ); ?></div>
					<?php endif; ?>
				</div>
				<?php if ( $blog_url ) : ?>
					<a class="hp-view-all-link" href="<?php echo esc_url( $blog_url ); ?>">
						<span><?php esc_html_e( 'View All Articles', 'massitpro' ); ?></span>
						<span aria-hidden="true"><?php echo massitpro_svg_icon( 'arrow-right' ); ?></span>
					</a>
				<?php endif; ?>
			</div>
			<?php if ( $pinned_posts ) : ?>
				<div class="cards-grid cards-grid--3">
					<?php foreach ( array_slice( $pinned_posts, 0, 3 ) as $index => $post ) :
						$image    = massitpro_get_post_display_image( $post->ID );
						$excerpt  = massitpro_get_post_summary_text( $post->ID );
						$category = get_the_category( $post->ID );
						$permalink = get_permalink( $post );
					?>
						<article class="content-card media-card feature-grid-card hp-blog-card" data-reveal style="transition-delay: <?php echo esc_attr( number_format( $index * 0.05, 2, '.', '' ) ); ?>s;">
							<a class="hp-blog-card__media-link" href="<?php echo esc_url( $permalink ); ?>">
								<?php if ( $image ) : ?>
									<?php massitpro_render_media( [ 'image' => $image, 'aspect' => 'video' ] ); ?>
								<?php else : ?>
									<div class="location-image-placeholder" aria-hidden="true"></div>
								<?php endif; ?>
							</a>
							<div class="media-card__body">
								<div class="meta-row">
									<?php if ( ! empty( $category[0] ) ) : ?>
										<span class="chip chip--teal"><?php echo esc_html( $category[0]->name ); ?></span>
									<?php endif; ?>
									<span class="meta-date"><?php echo esc_html( get_the_date( '', $post ) ); ?></span>
								</div>
								<h3><a href="<?php echo esc_url( $permalink ); ?>"><?php echo esc_html( get_the_title( $post ) ); ?></a></h3>
								<?php if ( $excerpt ) : ?>
									<p class="hp-blog-card__excerpt"><?php echo esc_html( $excerpt ); ?></p>
								<?php endif; ?>
								<a class="section-link section-link--inline" href="<?php echo esc_url( $permalink ); ?>">
									<span><?php esc_html_e( 'Read More', 'massitpro' ); ?></span>
									<span aria-hidden="true"><?php echo massitpro_svg_icon( 'arrow-right' ); ?></span>
								</a>
							</div>
						</article>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>
	</section>
	<?php
}

/**
 * Render the about page body.
 *
 * @param int $post_id Post ID.
 */
function massitpro_render_about_page_body($post_id = 0) {
	$post_id = massitpro_get_render_post_id($post_id);
	massitpro_render_intro_section('intro_section', $post_id);
	massitpro_render_stats_band_section('stats_section', $post_id, ['surface_class' => 'surface-sand']);
	massitpro_render_icon_cards_section('value_cards_section', $post_id);
	massitpro_render_process_section('process_section', $post_id, ['surface_class' => 'surface-sand']);
	massitpro_render_team_highlights_section('team_highlights_section', $post_id);
	massitpro_render_certifications_section('certifications_section', $post_id);
	massitpro_render_spotlight_section('focus_section', $post_id);
	massitpro_render_cta_block($post_id);
}

/**
 * Render a services hub page body.
 *
 * @param int $post_id Post ID.
 */
function massitpro_render_services_page_body($post_id = 0) {
	$post_id = massitpro_get_render_post_id($post_id);
	massitpro_render_image_cards_section('business_services_section', $post_id);
	massitpro_render_icon_cards_section('why_choose_section', $post_id, ['surface_class' => 'surface-sand']);
	massitpro_render_image_cards_section('residential_services_section', $post_id, ['surface_class' => 'surface-sand']);
	massitpro_render_spotlight_section('web_design_spotlight', $post_id);
	massitpro_render_process_section('process_section', $post_id, ['surface_class' => 'surface-sand']);
	massitpro_render_cta_block($post_id);
}

/**
 * Render a service landing page body.
 *
 * @param int $post_id Post ID.
 */
function massitpro_render_service_group_page_body($post_id = 0) {
	$post_id = massitpro_get_render_post_id($post_id);

	massitpro_render_intro_section('intro_section', $post_id);
	massitpro_render_image_cards_section('services_section', $post_id);
	massitpro_render_icon_cards_section('benefits_section', $post_id, ['surface_class' => 'surface-sand']);
	massitpro_render_process_section('process_section', $post_id, ['surface_class' => 'surface-sand']);
	massitpro_render_faq_cards_section('faq_section', $post_id);
	massitpro_render_cta_block($post_id);
}

/**
 * Render an industries hub page body.
 *
 * @param int $post_id Post ID.
 */
function massitpro_render_industries_page_body($post_id = 0) {
	$post_id = massitpro_get_render_post_id($post_id);
	massitpro_render_intro_section('intro_section', $post_id, ['surface_class' => 'surface-sand']);
	massitpro_render_image_cards_section('featured_industries_section', $post_id);
	massitpro_render_icon_cards_section('value_cards_section', $post_id);
	massitpro_render_icon_cards_section('compliance_cards_section', $post_id, ['surface_class' => 'surface-sand']);
	massitpro_render_featured_project_section('featured_project_section', $post_id, ['surface_class' => 'surface-sand']);
	massitpro_render_cta_block($post_id);
}

/**
 * Render a locations hub page body.
 *
 * @param int $post_id Post ID.
 */
function massitpro_render_locations_page_body($post_id = 0) {
	$post_id = massitpro_get_render_post_id($post_id);
	massitpro_render_intro_section('intro_section', $post_id);
	massitpro_render_image_cards_section('featured_locations_section', $post_id, ['surface_class' => 'surface-sand']);
	massitpro_render_icon_cards_section('service_highlights_section', $post_id);
	massitpro_render_image_cards_section('local_advantage_section', $post_id, ['surface_class' => 'surface-sand']);
	massitpro_render_cta_block($post_id);
}

/**
 * Render a service detail page body.
 *
 * @param int $post_id Post ID.
 */
function massitpro_render_service_detail_page_body($post_id = 0) {
	$post_id = massitpro_get_render_post_id($post_id);
	massitpro_render_intro_section('intro_section', $post_id);
	massitpro_render_text_list_section('deliverables_section', $post_id);
	massitpro_render_image_cards_section('capabilities_section', $post_id, ['surface_class' => 'surface-sand']);
	massitpro_render_process_section('process_section', $post_id, ['surface_class' => 'surface-sand']);
	massitpro_render_text_list_section('ideal_for_section', $post_id);
	massitpro_render_link_cards_section('related_services_section', $post_id);
	massitpro_render_cpt_projects_section($post_id);
	massitpro_render_cpt_testimonials_section($post_id);
	massitpro_render_faq_cards_section('faq_section', $post_id);
	massitpro_render_cta_block($post_id);
}

/**
 * Render an industry detail page body.
 *
 * @param int $post_id Post ID.
 */
function massitpro_render_industry_detail_page_body($post_id = 0) {
	$post_id = massitpro_get_render_post_id($post_id);
	massitpro_render_intro_section('overview_section', $post_id);
	massitpro_render_image_cards_section('pain_points_section', $post_id, ['surface_class' => 'surface-sand']);
	massitpro_render_link_cards_section('recommended_services_section', $post_id);
	massitpro_render_image_cards_section('sub_clusters_section', $post_id);
	massitpro_render_icon_cards_section('compliance_section', $post_id, ['surface_class' => 'surface-sand']);
	massitpro_render_featured_project_section('featured_project_section', $post_id, ['surface_class' => 'surface-sand']);
	massitpro_render_faq_cards_section('faq_section', $post_id);
	massitpro_render_related_links_section('related_links_section', $post_id);
	massitpro_render_cta_block($post_id);
}

/**
 * Render a services carousel section for location-detail pages.
 * Resolves featured images from each card's link_url.
 *
 * @param string              $field_name Section field name.
 * @param int                 $post_id    Post ID.
 * @param array<string,mixed> $args       Render arguments.
 */
function massitpro_render_services_carousel_section( $field_name, $post_id, $args = [] ) {
	$args    = wp_parse_args(
		(array) $args,
		[
			'surface_class'  => '',
			'section_class'  => '',
			'carousel_key'   => 'services',
			'view_all_label' => '',
			'view_all_url'   => '',
		]
	);
	$group   = (array) massitpro_get_section_meta( $field_name, massitpro_get_render_post_id( $post_id ), [] );
	$eyebrow = trim( (string) ( $group['eyebrow'] ?? '' ) );
	$heading = trim( (string) ( $group['heading'] ?? '' ) );
	$body    = (string) ( $group['body'] ?? '' );
	$items   = massitpro_filter_rows( (array) ( $group['items'] ?? [] ), [ 'title', 'body', 'link_label', 'link_url' ] );

	if ( ! massitpro_has_any_content( $eyebrow, $heading, $body, $items ) ) {
		return;
	}

	$key = esc_attr( (string) $args['carousel_key'] );
	?>
	<section class="section-padding section-spacing <?php echo esc_attr( trim( (string) $args['surface_class'] . ' ' . (string) $args['section_class'] ) ); ?>">
		<div class="site-shell">
			<div class="carousel-header carousel-header--stacked">
				<div class="section-header__copy">
					<?php massitpro_render_section_heading( [ 'label' => $eyebrow, 'title' => $heading, 'copy' => $body ] ); ?>
				</div>
				<div class="carousel-header__right">
					<div class="carousel-header__controls">
						<button class="icon-button" data-carousel-prev="<?php echo $key; ?>" aria-label="<?php esc_attr_e( 'Previous', 'massitpro' ); ?>">
							<?php echo massitpro_svg_icon( 'arrow-left' ); ?>
						</button>
						<button class="icon-button" data-carousel-next="<?php echo $key; ?>" aria-label="<?php esc_attr_e( 'Next', 'massitpro' ); ?>">
							<?php echo massitpro_svg_icon( 'arrow-right' ); ?>
						</button>
					</div>
					<?php if ( ! empty( $args['view_all_label'] ) && ! empty( $args['view_all_url'] ) ) : ?>
						<a class="carousel-view-all-link" href="<?php echo esc_url( (string) $args['view_all_url'] ); ?>">
							<span><?php echo esc_html( (string) $args['view_all_label'] ); ?></span>
							<span aria-hidden="true"><?php echo massitpro_svg_icon( 'arrow-right' ); ?></span>
						</a>
					<?php endif; ?>
				</div>
			</div>
			<?php if ( $items ) : ?>
				<div class="scroll-strip" data-carousel="<?php echo $key; ?>">
					<?php foreach ( $items as $index => $item ) :
						$title      = trim( (string) ( $item['title'] ?? '' ) );
						$body_text  = trim( (string) ( $item['body'] ?? '' ) );
						$link_label = trim( (string) ( $item['link_label'] ?? '' ) );
						$link_url   = trim( (string) ( $item['link_url'] ?? '' ) );
						$image_id   = $link_url ? massitpro_resolve_linked_page_image_id( $link_url ) : 0;
						$img_src    = $image_id ? wp_get_attachment_image_url( $image_id, 'massitpro-card' ) : '';
					?>
						<article class="scroll-strip__item content-card media-card feature-grid-card" data-reveal style="transition-delay: <?php echo esc_attr( number_format( $index * 0.05, 2, '.', '' ) ); ?>s;">
							<?php if ( $img_src ) : ?>
								<?php if ( $link_url ) : ?>
									<a href="<?php echo esc_url( $link_url ); ?>">
										<div class="media-block media-block--video"><img src="<?php echo esc_url( $img_src ); ?>" alt="<?php echo esc_attr( $title ); ?>" loading="lazy"></div>
									</a>
								<?php else : ?>
									<div class="media-block media-block--video"><img src="<?php echo esc_url( $img_src ); ?>" alt="<?php echo esc_attr( $title ); ?>" loading="lazy"></div>
								<?php endif; ?>
							<?php else : ?>
								<div class="location-image-placeholder" aria-hidden="true"></div>
							<?php endif; ?>
							<div class="media-card__body">
								<?php if ( $title ) : ?>
									<h3>
										<?php if ( $link_url ) : ?>
											<a href="<?php echo esc_url( $link_url ); ?>"><?php echo esc_html( $title ); ?></a>
										<?php else : ?>
											<?php echo esc_html( $title ); ?>
										<?php endif; ?>
									</h3>
								<?php endif; ?>
								<?php if ( $body_text ) : ?>
									<p><?php echo esc_html( $body_text ); ?></p>
								<?php endif; ?>
								<?php if ( $link_url ) : ?>
									<a class="section-link section-link--inline" href="<?php echo esc_url( $link_url ); ?>">
										<span><?php echo $link_label ? esc_html( $link_label ) : esc_html__( 'Learn More', 'massitpro' ); ?></span>
										<span aria-hidden="true"><?php echo massitpro_svg_icon( 'arrow-right' ); ?></span>
									</a>
								<?php endif; ?>
							</div>
						</article>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>
	</section>
	<?php
}

/**
 * Render an industries flip-card carousel for location-detail pages.
 * Card front: title and link. Card back: featured image of linked page.
 *
 * @param string              $field_name Section field name.
 * @param int                 $post_id    Post ID.
 * @param array<string,mixed> $args       Render arguments.
 */
function massitpro_render_industries_flipcard_section( $field_name, $post_id, $args = [] ) {
	$args    = wp_parse_args(
		(array) $args,
		[
			'surface_class' => '',
			'section_class' => '',
			'carousel_key'  => 'industries',
		]
	);
	$group   = (array) massitpro_get_section_meta( $field_name, massitpro_get_render_post_id( $post_id ), [] );
	$eyebrow = trim( (string) ( $group['eyebrow'] ?? '' ) );
	$heading = trim( (string) ( $group['heading'] ?? '' ) );
	$body    = (string) ( $group['body'] ?? '' );
	$items   = massitpro_filter_rows( (array) ( $group['items'] ?? [] ), [ 'title', 'body', 'link_url' ] );

	if ( ! massitpro_has_any_content( $eyebrow, $heading, $body, $items ) ) {
		return;
	}

	$key = esc_attr( (string) $args['carousel_key'] );
	?>
	<section class="section-padding section-spacing <?php echo esc_attr( trim( (string) $args['surface_class'] . ' ' . (string) $args['section_class'] ) ); ?>">
		<div class="site-shell">
			<div class="carousel-header">
				<div class="section-header__copy">
					<?php massitpro_render_section_heading( [ 'label' => $eyebrow, 'title' => $heading, 'copy' => $body ] ); ?>
				</div>
				<div class="carousel-header__controls">
					<button class="icon-button" data-carousel-prev="<?php echo $key; ?>" aria-label="<?php esc_attr_e( 'Previous', 'massitpro' ); ?>">
						<?php echo massitpro_svg_icon( 'arrow-left' ); ?>
					</button>
					<button class="icon-button" data-carousel-next="<?php echo $key; ?>" aria-label="<?php esc_attr_e( 'Next', 'massitpro' ); ?>">
						<?php echo massitpro_svg_icon( 'arrow-right' ); ?>
					</button>
				</div>
			</div>
			<?php if ( $items ) : ?>
				<div class="scroll-strip" data-carousel="<?php echo $key; ?>">
					<?php foreach ( $items as $index => $item ) :
						$title     = trim( (string) ( $item['title'] ?? '' ) );
						$body_text = trim( (string) ( $item['body'] ?? '' ) );
						$link_url  = trim( (string) ( $item['link_url'] ?? '' ) );
						$image_id  = $link_url ? massitpro_resolve_linked_page_image_id( $link_url ) : 0;
						$delay     = esc_attr( number_format( $index * 0.05, 2, '.', '' ) );
					?>
						<?php if ( $link_url ) : ?>
							<a class="scroll-strip__item flip-card" href="<?php echo esc_url( $link_url ); ?>" data-reveal style="transition-delay: <?php echo $delay; ?>s;">
						<?php else : ?>
							<div class="scroll-strip__item flip-card" data-reveal style="transition-delay: <?php echo $delay; ?>s;">
						<?php endif; ?>
							<div class="flip-card__inner">
								<div class="flip-card__front">
									<?php if ( $title ) : ?>
										<h3><?php echo esc_html( $title ); ?></h3>
									<?php endif; ?>
									<?php if ( $body_text ) : ?>
										<p><?php echo esc_html( $body_text ); ?></p>
									<?php endif; ?>
									<?php if ( $link_url ) : ?>
										<span class="section-link section-link--inline" aria-hidden="true">
											<span><?php esc_html_e( 'Learn More', 'massitpro' ); ?></span>
											<span><?php echo massitpro_svg_icon( 'arrow-right' ); ?></span>
										</span>
									<?php endif; ?>
								</div>
								<div class="flip-card__back">
									<?php if ( $image_id ) :
										$src = wp_get_attachment_image_url( $image_id, 'large' );
										if ( $src ) : ?>
											<img src="<?php echo esc_url( $src ); ?>" alt="<?php echo esc_attr( $title ); ?>" loading="lazy">
										<?php endif; ?>
									<?php else : ?>
										<div class="flip-card__back-placeholder" aria-hidden="true"></div>
									<?php endif; ?>
								</div>
							</div>
						<?php if ( $link_url ) : ?>
							</a>
						<?php else : ?>
							</div>
						<?php endif; ?>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>
	</section>
	<?php
}

/**
 * Render the related links section as a split layout for location-detail pages.
 * Left: image placeholder. Right: eyebrow + heading + body + link items with icon, label, description.
 *
 * @param string              $field_name Section field name.
 * @param int                 $post_id    Post ID.
 * @param array<string,mixed> $args       Render arguments.
 */
function massitpro_render_related_links_split_section( $field_name, $post_id, $args = [] ) {
	$args    = wp_parse_args(
		(array) $args,
		[
			'surface_class' => '',
			'section_class' => '',
		]
	);
	$group   = (array) massitpro_get_section_meta( $field_name, massitpro_get_render_post_id( $post_id ), [] );
	$eyebrow = trim( (string) ( $group['eyebrow'] ?? '' ) );
	$heading = trim( (string) ( $group['heading'] ?? '' ) );
	$body    = (string) ( $group['body'] ?? '' );
	$image   = massitpro_resolve_image_value( $group['image'] ?? null );
	$items   = massitpro_filter_rows( (array) ( $group['items'] ?? [] ), [ 'link' ] );

	if ( ! massitpro_has_any_content( $eyebrow, $heading, $body, $items ) ) {
		return;
	}
	?>
	<section class="section-padding section-spacing <?php echo esc_attr( trim( (string) $args['surface_class'] . ' ' . (string) $args['section_class'] ) ); ?>">
		<div class="site-shell">
			<div class="location-split">
				<div class="location-split__media" data-reveal>
					<?php if ( $image ) : ?>
						<?php massitpro_render_media( [ 'image' => $image, 'aspect' => 'square' ] ); ?>
					<?php else : ?>
						<div class="location-image-placeholder location-image-placeholder--tall" aria-hidden="true"></div>
					<?php endif; ?>
				</div>
				<div class="location-split__copy" data-reveal>
					<?php if ( $eyebrow || $heading || $body ) : ?>
						<?php if ( $eyebrow ) : ?>
							<p class="section-label"><?php echo esc_html( $eyebrow ); ?></p>
						<?php endif; ?>
						<?php if ( $heading ) : ?>
							<h2><?php echo esc_html( $heading ); ?></h2>
						<?php endif; ?>
						<?php if ( $body ) : ?>
							<div class="section-copy"><?php echo wp_kses_post( $body ); ?></div>
						<?php endif; ?>
					<?php endif; ?>
					<?php if ( $items ) : ?>
						<div class="related-split-links">
							<?php foreach ( $items as $item ) :
								$link = massitpro_normalize_link( $item['link'] ?? [] );
								if ( ! $link['url'] || ! $link['label'] ) {
									continue;
								}
								$icon = trim( (string) ( $item['icon'] ?? 'arrow-right' ) );
								$desc = trim( (string) ( $item['description'] ?? '' ) );
							?>
								<a class="related-split-link" href="<?php echo esc_url( $link['url'] ); ?>"<?php echo $link['target'] ? ' target="' . esc_attr( $link['target'] ) . '" rel="noopener noreferrer"' : ''; ?>>
									<span class="related-split-link__icon" aria-hidden="true"><?php echo massitpro_svg_icon( $icon ); ?></span>
									<div class="related-split-link__content">
										<span class="related-split-link__label"><?php echo esc_html( $link['label'] ); ?></span>
										<?php if ( $desc ) : ?>
											<p class="related-split-link__desc"><?php echo esc_html( $desc ); ?></p>
										<?php endif; ?>
									</div>
									<span class="related-split-link__arrow" aria-hidden="true"><?php echo massitpro_svg_icon( 'arrow-right' ); ?></span>
								</a>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</section>
	<?php
}

/**
 * Render the location-detail overview section.
 * Always shows the split: text LEFT (eyebrow, heading, body, city badge, CTA), image or placeholder RIGHT.
 *
 * @param string              $field_name Section field name.
 * @param int                 $post_id    Post ID.
 * @param array<string,mixed> $args       Render arguments.
 */
function massitpro_render_location_intro_section( $field_name, $post_id, $args = [] ) {
	$args      = wp_parse_args(
		(array) $args,
		[
			'surface_class' => '',
			'section_class' => '',
		]
	);
	$pid       = massitpro_get_render_post_id( $post_id );
	$group     = (array) massitpro_get_section_meta( $field_name, $pid, [] );
	$eyebrow   = trim( (string) ( $group['eyebrow'] ?? '' ) );
	$heading   = trim( (string) ( $group['heading'] ?? '' ) );
	$body      = (string) ( $group['body'] ?? '' );
	$city      = trim( (string) ( $group['location_city'] ?? '' ) );
	$btn_label = trim( (string) ( $group['button_label'] ?? '' ) );
	$btn_url   = trim( (string) ( $group['button_url'] ?? '' ) );
	$image     = massitpro_resolve_image_value( $group['image'] ?? null ) ?: massitpro_get_post_display_image( $pid );

	if ( ! $body ) {
		$body = massitpro_get_post_content_html( $pid );
	}

	if ( ! $heading ) {
		$heading = get_the_title( $pid );
	}

	if ( ! massitpro_has_any_content( $heading, $body ) ) {
		return;
	}
	?>
	<section class="section-padding section-spacing location-intro-section <?php echo esc_attr( trim( (string) $args['surface_class'] . ' ' . (string) $args['section_class'] ) ); ?>">
		<div class="site-shell">
			<div class="split-feature">
				<div class="split-feature__copy location-intro__copy" data-reveal>
					<?php if ( $eyebrow ) : ?>
						<p class="section-label"><?php echo esc_html( $eyebrow ); ?></p>
					<?php endif; ?>
					<?php if ( $heading ) : ?>
						<h2><?php echo esc_html( $heading ); ?></h2>
					<?php endif; ?>
					<?php if ( $body ) : ?>
						<div class="section-copy"><?php echo wp_kses_post( $body ); ?></div>
					<?php endif; ?>
					<?php if ( $city ) : ?>
						<p class="location-badge">
							<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
							<?php echo esc_html( $city ); ?>
						</p>
					<?php endif; ?>
					<?php if ( $btn_label && $btn_url ) : ?>
						<div class="button-row" style="margin-top:28px;">
							<?php massitpro_render_button( [ 'label' => $btn_label, 'url' => $btn_url, 'variant' => 'primary' ] ); ?>
						</div>
					<?php endif; ?>
				</div>
				<div class="split-feature__media" data-reveal>
					<?php if ( $image ) : ?>
						<?php massitpro_render_media( [ 'image' => $image, 'aspect' => 'square' ] ); ?>
					<?php else : ?>
						<div class="location-image-placeholder" aria-hidden="true"></div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</section>
	<?php
}

/**
 * Render available services as a split layout for location-detail pages.
 * Left: image or navy placeholder. Right: section header + 2-column service list.
 *
 * @param string              $field_name Section field name.
 * @param int                 $post_id    Post ID.
 * @param array<string,mixed> $args       Render arguments.
 */
function massitpro_render_services_split_section( $field_name, $post_id, $args = [] ) {
	$args    = wp_parse_args(
		(array) $args,
		[
			'surface_class' => '',
			'section_class' => '',
		]
	);
	$group   = (array) massitpro_get_section_meta( $field_name, massitpro_get_render_post_id( $post_id ), [] );
	$eyebrow = trim( (string) ( $group['eyebrow'] ?? '' ) );
	$heading = trim( (string) ( $group['heading'] ?? '' ) );
	$body    = (string) ( $group['body'] ?? '' );
	$items   = massitpro_filter_rows( (array) ( $group['items'] ?? [] ), [ 'title', 'link_url' ] );
	$image   = massitpro_resolve_image_value( $group['image'] ?? null );

	if ( ! massitpro_has_any_content( $eyebrow, $heading, $body, $items ) ) {
		return;
	}
	?>
	<section class="section-padding section-spacing <?php echo esc_attr( trim( (string) $args['surface_class'] . ' ' . (string) $args['section_class'] ) ); ?>">
		<div class="site-shell">
			<div class="location-split">
				<div class="location-split__media" data-reveal>
					<?php if ( $image ) : ?>
						<?php massitpro_render_media( [ 'image' => $image, 'aspect' => 'square' ] ); ?>
					<?php else : ?>
						<div class="location-image-placeholder" aria-hidden="true"></div>
					<?php endif; ?>
				</div>
				<div class="location-split__copy" data-reveal>
					<?php massitpro_render_section_heading( [ 'label' => $eyebrow, 'title' => $heading, 'copy' => $body ] ); ?>
					<?php if ( $items ) : ?>
						<div class="services-split__list">
							<?php foreach ( $items as $item ) :
								$title    = trim( (string) ( $item['title'] ?? '' ) );
								$link_url = trim( (string) ( $item['link_url'] ?? '' ) );
								if ( ! $title ) {
									continue;
								}
							?>
								<?php if ( $link_url ) : ?>
									<a href="<?php echo esc_url( $link_url ); ?>">
										<?php echo massitpro_svg_icon( 'check' ); ?>
										<span><?php echo esc_html( $title ); ?></span>
									</a>
								<?php else : ?>
									<span class="services-split__item">
										<?php echo massitpro_svg_icon( 'check' ); ?>
										<span><?php echo esc_html( $title ); ?></span>
									</span>
								<?php endif; ?>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</section>
	<?php
}

/**
 * Render a location detail page body.
 *
 * @param int $post_id Post ID.
 */
function massitpro_render_location_detail_page_body($post_id = 0) {
	$post_id = massitpro_get_render_post_id($post_id);
	massitpro_render_location_intro_section('overview_section', $post_id, ['surface_class' => 'surface-stone-alt']);
	massitpro_render_image_cards_section('why_local_section', $post_id, ['surface_class' => 'surface-sand-warm', 'heading_align' => 'center', 'icon_default' => 'check']);
	massitpro_render_services_carousel_section('available_services_section', $post_id, ['surface_class' => 'surface-stone-alt', 'carousel_key' => 'services']);
	massitpro_render_related_links_split_section('related_links_section', $post_id, ['surface_class' => 'surface-sand-warm']);
	massitpro_render_industries_flipcard_section('served_industries_section', $post_id, ['surface_class' => 'surface-stone-alt', 'carousel_key' => 'industries']);
	massitpro_render_faq_cards_section('faq_section', $post_id, ['surface_class' => 'surface-sand-warm']);
	massitpro_render_cta_block($post_id, ['section_class' => 'cta-shell--center']);
}

/**
 * Render the projects archive-style page body.
 *
 * @param int $post_id Post ID.
 */
function massitpro_render_projects_page_body($post_id = 0) {
	$post_id = massitpro_get_render_post_id($post_id);
	$items   = massitpro_query_posts(
		[
			'post_type'      => 'project',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'orderby'        => ['menu_order' => 'ASC', 'date' => 'DESC'],
		]
	);

	massitpro_render_default_page_body($post_id);

	if (! $items) {
		return;
	}
	?>
	<section class="section-padding section-spacing">
		<div class="site-shell">
			<div class="cards-grid cards-grid--3">
				<?php foreach ($items as $index => $item) : ?>
					<?php massitpro_render_project_card($item, $index); ?>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
	<?php
}

/**
 * Render the testimonials page body.
 *
 * @param int $post_id Post ID.
 */
function massitpro_render_testimonials_page_body($post_id = 0) {
	$post_id = massitpro_get_render_post_id($post_id);
	massitpro_render_cpt_testimonials_featured($post_id);
	massitpro_render_cpt_testimonials_section($post_id);
	massitpro_render_stats_band_section('stats_section', $post_id, ['surface_class' => 'surface-sand']);
	massitpro_render_community_spotlight_section('community_spotlight_section', $post_id);
	massitpro_render_cta_block($post_id);
}

/**
 * Render the FAQ page body.
 *
 * @param int $post_id Post ID.
 */
function massitpro_render_faq_page_body($post_id = 0) {
	$post_id = massitpro_get_render_post_id($post_id);
	massitpro_render_page_hero(massitpro_prepare_page_hero($post_id));
	massitpro_render_faq_quick_answers('quick_answers_section', $post_id);
	massitpro_render_faq_accordion('faq_accordion_section', $post_id);
	massitpro_render_faq_still_have_questions('still_have_questions_section', $post_id);
	massitpro_render_faq_related_resources($post_id);
	massitpro_render_cta_block($post_id);
}

/**
 * Render the contact page body.
 *
 * @param int $post_id Post ID.
 */
function massitpro_render_contact_page_body($post_id = 0) {
	$post_id = massitpro_get_render_post_id($post_id);
	$content = massitpro_get_post_content_html($post_id);
	$image   = massitpro_get_post_display_image($post_id);
	$cards   = [
		['icon' => 'phone', 'label' => __('Phone', 'massitpro'), 'value' => massitpro_theme_option('phone'), 'href' => massitpro_theme_option('phone') ? massitpro_tel_href(massitpro_theme_option('phone')) : ''],
		['icon' => 'mail', 'label' => __('Email', 'massitpro'), 'value' => massitpro_theme_option('email'), 'href' => massitpro_theme_option('email') ? 'mailto:' . massitpro_theme_option('email') : ''],
		['icon' => 'clock', 'label' => __('Hours', 'massitpro'), 'value' => massitpro_theme_option('business_hours'), 'href' => ''],
		['icon' => 'map-pin', 'label' => __('Service Area', 'massitpro'), 'value' => massitpro_theme_option('service_area'), 'href' => ''],
	];

	if (! $content && ! $image && ! array_filter(wp_list_pluck($cards, 'value'))) {
		return;
	}
	?>
	<section class="section-padding section-spacing">
		<div class="site-shell">
			<div class="contact-grid">
				<?php if ($content || $image) : ?>
					<div class="content-card contact-main-card" data-reveal>
						<?php if ($image) : ?>
							<div class="contact-main-card__media"><?php massitpro_render_media(['image' => $image, 'aspect' => 'video']); ?></div>
						<?php endif; ?>
						<?php if ($content) : ?>
							<div class="entry-content"><?php echo wp_kses_post($content); ?></div>
						<?php endif; ?>
					</div>
				<?php endif; ?>
				<div class="contact-info-stack">
					<?php foreach ($cards as $card) : ?>
						<?php if (! $card['value']) : ?>
							<?php continue; ?>
						<?php endif; ?>
						<div class="content-card contact-info-card" data-reveal>
							<div class="soft-icon" aria-hidden="true"><?php echo massitpro_svg_icon($card['icon']); ?></div>
							<div>
								<p class="detail-label"><?php echo esc_html($card['label']); ?></p>
								<?php if ($card['href']) : ?>
									<a href="<?php echo esc_url($card['href']); ?>"><?php echo esc_html($card['value']); ?></a>
								<?php else : ?>
									<p class="contact-info-card__text"><?php echo esc_html($card['value']); ?></p>
								<?php endif; ?>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</section>
	<?php
	massitpro_render_icon_cards_section('trust_cards_section', $post_id, ['surface_class' => 'surface-sand']);
	massitpro_render_process_section('process_section', $post_id);
	massitpro_render_spotlight_section('coverage_section', $post_id, ['surface_class' => 'surface-sand']);
	massitpro_render_cta_block($post_id);
}

/**
 * Render the blog page body.
 *
 * @param int $post_id Post ID.
 */
function massitpro_render_blog_page_body($post_id = 0) {
	$post_id = massitpro_get_render_post_id($post_id);
	$paged   = max(1, (int) get_query_var('paged'), (int) get_query_var('page'));
	$query   = new WP_Query(
		[
			'post_type'           => 'post',
			'post_status'         => 'publish',
			'posts_per_page'      => 6,
			'paged'               => $paged,
			'ignore_sticky_posts' => true,
		]
	);

	if ($post_id) {
		massitpro_render_default_page_body($post_id);
	}
	?>
	<section class="section-padding section-spacing">
		<div class="site-shell">
			<?php if ($query->have_posts()) : ?>
				<div class="cards-grid cards-grid--3">
					<?php $index = 0; ?>
					<?php while ($query->have_posts()) : $query->the_post(); ?>
						<?php massitpro_render_post_card(get_post(), $index); ?>
						<?php $index++; ?>
					<?php endwhile; ?>
				</div>
				<?php if ($query->max_num_pages > 1) : ?>
					<div class="pagination-wrap">
						<?php
						echo wp_kses_post(
							paginate_links(
								[
									'total'   => $query->max_num_pages,
									'current' => $paged,
									'type'    => 'list',
								]
							)
						);
						?>
					</div>
				<?php endif; ?>
			<?php endif; ?>
		</div>
	</section>
	<?php
	wp_reset_postdata();
}

/**
 * Render a page context.
 *
 * @param string              $context Page context.
 * @param array<string,mixed> $payload Optional payload.
 */
function massitpro_render_context_page($context, $payload = []) {
	$post_id = massitpro_get_render_post_id((int) ($payload['post_id'] ?? 0));

	switch ($context) {
		case 'about':
			massitpro_render_page_hero(massitpro_prepare_page_hero($post_id));
			massitpro_render_about_page_body($post_id);
			break;

		case 'services-hub':
			massitpro_render_page_hero(massitpro_prepare_page_hero($post_id));
			massitpro_render_services_page_body($post_id);
			break;

		case 'services-business':
		case 'services-residential':
			massitpro_render_page_hero(massitpro_prepare_page_hero($post_id));
			massitpro_render_service_group_page_body($post_id);
			break;

		case 'service-detail':
			massitpro_render_page_hero(massitpro_prepare_page_hero($post_id));
			massitpro_render_service_detail_page_body($post_id);
			break;

		case 'industries-hub':
			massitpro_render_page_hero(massitpro_prepare_page_hero($post_id));
			massitpro_render_industries_page_body($post_id);
			break;

		case 'industry-detail':
			massitpro_render_page_hero(massitpro_prepare_page_hero($post_id));
			massitpro_render_industry_detail_page_body($post_id);
			break;

		case 'locations-hub':
			massitpro_render_page_hero(massitpro_prepare_page_hero($post_id));
			massitpro_render_locations_page_body($post_id);
			break;

		case 'location-detail':
			massitpro_render_page_hero(massitpro_prepare_page_hero($post_id));
			massitpro_render_location_detail_page_body($post_id);
			break;

		case 'projects':
			massitpro_render_page_hero(massitpro_prepare_page_hero($post_id));
			massitpro_render_projects_page_body($post_id);
			break;

		case 'testimonials':
			massitpro_render_page_hero(massitpro_prepare_page_hero($post_id));
			massitpro_render_testimonials_page_body($post_id);
			break;

		case 'faq':
			massitpro_render_page_hero(massitpro_prepare_page_hero($post_id));
			massitpro_render_faq_page_body($post_id);
			break;

		case 'contact':
			massitpro_render_page_hero(massitpro_prepare_page_hero($post_id));
			massitpro_render_contact_page_body($post_id);
			break;

		case 'blog':
			massitpro_render_page_hero(
				massitpro_prepare_page_hero(
					$post_id,
					[
						'title' => __('Blog', 'massitpro'),
					]
				)
			);
			massitpro_render_blog_page_body($post_id);
			break;

		default:
			massitpro_render_page_hero(massitpro_prepare_page_hero($post_id));
			massitpro_render_default_page_body($post_id);
			break;
	}
}
