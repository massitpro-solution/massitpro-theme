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
	$eyebrow = trim((string) ($group['eyebrow'] ?? ''));
	$heading = trim((string) ($group['heading'] ?? ''));
	$body    = (string) ($group['body'] ?? '');
	$steps   = massitpro_filter_rows((array) ($group['steps'] ?? []), ['step_label', 'title', 'body']);

	if (! massitpro_has_any_content($eyebrow, $heading, $body, $steps)) {
		return;
	}
	?>
	<section class="section-padding section-spacing <?php echo esc_attr(trim((string) $args['surface_class'] . ' ' . (string) $args['section_class'])); ?>">
		<div class="site-shell">
			<?php massitpro_render_section_heading(['label' => $eyebrow, 'title' => $heading, 'copy' => $body]); ?>
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
 * Render animated process timeline steps for the Projects page.
 *
 * @param string $field_name Section meta key.
 * @param int    $post_id    Post ID.
 */
function massitpro_render_projects_process_steps($field_name, $post_id) {
	$group   = (array) massitpro_get_section_meta($field_name, massitpro_get_render_post_id($post_id), []);
	$eyebrow = trim((string) ($group['eyebrow'] ?? ''));
	$heading = trim((string) ($group['heading'] ?? ''));
	$body    = (string) ($group['body'] ?? '');
	$steps   = massitpro_filter_rows((array) ($group['steps'] ?? []), ['step_label', 'title', 'body']);

	if (! massitpro_has_any_content($eyebrow, $heading, $body, $steps)) {
		return;
	}
	?>
	<section class="section-padding section-spacing surface-sand projects-process-section">
		<div class="site-shell">
			<?php massitpro_render_section_heading(['label' => $eyebrow, 'title' => $heading, 'copy' => $body, 'align' => 'center']); ?>
			<?php if ($steps) : ?>
				<div class="process-timeline">
					<?php foreach ($steps as $index => $step) : ?>
						<div class="process-timeline__step" data-reveal style="transition-delay: <?php echo esc_attr(number_format($index * 0.12, 2, '.', '')); ?>s;">
							<div class="process-timeline__marker">
								<span class="process-timeline__number"><?php echo esc_html((string) ($step['step_label'] ?: ($index + 1))); ?></span>
							</div>
							<div class="process-timeline__content">
								<?php if (! empty($step['title'])) : ?>
									<h3><?php echo esc_html((string) $step['title']); ?></h3>
								<?php endif; ?>
								<?php if (! empty($step['body'])) : ?>
									<p><?php echo esc_html((string) $step['body']); ?></p>
								<?php endif; ?>
							</div>
						</div>
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
 * Render the About page mission section (2-column: text left, image right).
 *
 * @param string $meta_key Section key.
 * @param int    $post_id  Post ID.
 */
function massitpro_render_about_mission_section($meta_key, $post_id) {
	$post_id = massitpro_get_render_post_id($post_id);
	$group   = (array) massitpro_get_section_meta($meta_key, $post_id, []);
	$eyebrow = trim((string) ($group['eyebrow'] ?? ''));
	$heading = trim((string) ($group['heading'] ?? ''));
	$body    = (string) ($group['body'] ?? '');
	$image   = massitpro_resolve_image_value($group['image'] ?? null) ?: massitpro_get_post_display_image($post_id);

	if (! $body) {
		$body = massitpro_get_post_content_html($post_id);
	}

	if (! massitpro_has_any_content($eyebrow, $heading, $body)) {
		return;
	}
	?>
	<section class="about-mission section-padding section-spacing">
		<div class="site-shell">
			<div class="about-mission__grid">
				<div class="about-mission__copy" data-reveal>
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
				<?php if ($image) : ?>
					<div class="about-mission__media" data-reveal>
						<?php massitpro_render_media(['image' => $image, 'aspect' => 'square']); ?>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</section>
	<?php
}

/**
 * Render the About page process section with alternating image/text cards.
 *
 * @param string $meta_key Section key.
 * @param int    $post_id  Post ID.
 */
function massitpro_render_about_process_section($meta_key, $post_id) {
	$post_id = massitpro_get_render_post_id($post_id);
	$group   = (array) massitpro_get_section_meta($meta_key, $post_id, []);
	$eyebrow = trim((string) ($group['eyebrow'] ?? ''));
	$heading = trim((string) ($group['heading'] ?? ''));
	$body    = (string) ($group['body'] ?? '');
	$steps   = massitpro_filter_rows((array) ($group['steps'] ?? []), ['step_label', 'title', 'body']);

	if (! massitpro_has_any_content($eyebrow, $heading, $body, $steps)) {
		return;
	}
	?>
	<section class="about-process section-padding section-spacing surface-sand">
		<div class="site-shell">
			<?php massitpro_render_section_heading(['label' => $eyebrow, 'title' => $heading, 'copy' => $body, 'align' => 'center']); ?>
			<?php if ($steps) : ?>
				<div class="about-process__steps">
					<?php foreach ($steps as $index => $step) : ?>
						<?php $image = massitpro_resolve_image_value($step['image'] ?? null); ?>
						<?php $reverse = ($index % 2 === 1); ?>
						<article class="about-process__card content-card<?php echo $reverse ? ' about-process__card--reverse' : ''; ?>" data-reveal style="transition-delay: <?php echo esc_attr(number_format($index * 0.05, 2, '.', '')); ?>s;">
							<div class="about-process__card-copy">
								<?php if (! empty($step['step_label'])) : ?>
									<p class="process-card__step"><?php echo esc_html((string) $step['step_label']); ?></p>
								<?php endif; ?>
								<?php if (! empty($step['title'])) : ?>
									<h3><?php echo esc_html((string) $step['title']); ?></h3>
								<?php endif; ?>
								<?php if (! empty($step['body'])) : ?>
									<p><?php echo esc_html((string) $step['body']); ?></p>
								<?php endif; ?>
							</div>
							<?php if ($image) : ?>
								<div class="about-process__card-media">
									<?php massitpro_render_media(['image' => $image, 'aspect' => 'video']); ?>
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
 * Render the About page team section (2-column: image left, text+cards right).
 *
 * @param string $meta_key Section key.
 * @param int    $post_id  Post ID.
 */
function massitpro_render_about_team_section($meta_key, $post_id) {
	$post_id = massitpro_get_render_post_id($post_id);
	$group   = (array) massitpro_get_section_meta($meta_key, $post_id, []);
	$eyebrow = trim((string) ($group['eyebrow'] ?? ''));
	$heading = trim((string) ($group['heading'] ?? ''));
	$body    = (string) ($group['body'] ?? '');
	$items   = array_slice(massitpro_filter_rows((array) ($group['items'] ?? []), ['title', 'body']), 0, 3);
	$image   = massitpro_resolve_image_value($group['image'] ?? null);

	if (! massitpro_has_any_content($eyebrow, $heading, $body, $items)) {
		return;
	}
	?>
	<section class="about-team section-padding section-spacing">
		<div class="site-shell">
			<div class="about-team__grid">
				<?php if ($image) : ?>
					<div class="about-team__media" data-reveal>
						<?php massitpro_render_media(['image' => $image, 'aspect' => 'tall']); ?>
					</div>
				<?php endif; ?>
				<div class="about-team__content" data-reveal>
					<?php if ($eyebrow || $heading || $body) : ?>
						<div class="about-team__intro">
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
						<article class="about-team__card content-card" data-reveal style="transition-delay: <?php echo esc_attr(number_format($index * 0.08, 2, '.', '')); ?>s;">
							<div class="about-team__card-icon" aria-hidden="true">
								<div class="soft-icon"><?php echo massitpro_svg_icon((string) ($item['icon'] ?? 'check')); ?></div>
							</div>
							<div class="about-team__card-copy">
								<?php if (! empty($item['title'])) : ?>
									<h3><?php echo esc_html((string) $item['title']); ?></h3>
								<?php endif; ?>
								<?php if (! empty($item['body'])) : ?>
									<p><?php echo esc_html((string) $item['body']); ?></p>
								<?php endif; ?>
							</div>
						</article>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</section>
	<?php
}

/**
 * Render the About page certifications pill section with icon per pill.
 *
 * @param string $meta_key Section key.
 * @param int    $post_id  Post ID.
 */
function massitpro_render_about_certifications_section($meta_key, $post_id) {
	$post_id = massitpro_get_render_post_id($post_id);
	$group   = (array) massitpro_get_section_meta($meta_key, $post_id, []);
	$eyebrow = trim((string) ($group['eyebrow'] ?? ''));
	$heading = trim((string) ($group['heading'] ?? ''));
	$body    = (string) ($group['body'] ?? '');
	$items   = (array) ($group['items'] ?? []);

	$valid_items = [];
	foreach ($items as $item) {
		$label = trim((string) ($item['label'] ?? ''));
		if ($label) {
			$valid_items[] = $item;
		}
	}

	if (! $valid_items) {
		return;
	}
	?>
	<section class="about-certifications section-padding section-spacing surface-sand">
		<div class="site-shell">
			<?php massitpro_render_section_heading(['label' => $eyebrow, 'title' => $heading, 'copy' => $body, 'align' => 'center']); ?>
			<div class="about-certifications__grid" data-reveal>
				<?php foreach ($valid_items as $item) : ?>
					<span class="about-certifications__pill content-card">
						<span class="soft-icon" aria-hidden="true"><?php echo massitpro_svg_icon((string) ($item['icon'] ?? 'check')); ?></span>
						<span><?php echo esc_html((string) $item['label']); ?></span>
					</span>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
	<?php
}

/**
 * Render the About page Service Coverage Areas as pills.
 *
 * @param string $meta_key Section key.
 * @param int    $post_id  Post ID.
 */
function massitpro_render_about_service_coverage_section($meta_key, $post_id) {
	$post_id = massitpro_get_render_post_id($post_id);
	$group   = (array) massitpro_get_section_meta($meta_key, $post_id, []);
	$eyebrow = trim((string) ($group['eyebrow'] ?? ''));
	$heading = trim((string) ($group['heading'] ?? ''));
	$body    = (string) ($group['body'] ?? '');
	$items   = massitpro_filter_rows((array) ($group['items'] ?? []), ['title']);

	if (! massitpro_has_any_content($eyebrow, $heading, $body, $items)) {
		return;
	}
	?>
	<section class="about-service-coverage section-padding section-spacing">
		<div class="site-shell">
			<div class="section-header section-header--center" data-reveal>
				<div class="section-header__copy">
					<?php if ($eyebrow) : ?>
						<p class="section-label"><?php echo esc_html($eyebrow); ?></p>
					<?php endif; ?>
					<?php if ($heading) : ?>
						<h2><?php echo esc_html($heading); ?></h2>
					<?php endif; ?>
					<?php if ($body) : ?>
						<div class="section-copy"><?php echo wp_kses_post($body); ?></div>
					<?php endif; ?>
				</div>
			</div>
			<?php if ($items) : ?>
				<div class="hp-location-pills" data-reveal>
					<?php foreach ($items as $item) :
						$name     = trim((string) ($item['title'] ?? ''));
						$link_url = trim((string) ($item['link_url'] ?? ''));
						if (! $name) {
							continue;
						}
					?>
						<?php if ($link_url) : ?>
							<a class="hp-location-pill" href="<?php echo esc_url($link_url); ?>">
						<?php else : ?>
							<span class="hp-location-pill">
						<?php endif; ?>
							<span class="hp-location-pill__icon" aria-hidden="true"><?php echo massitpro_svg_icon('map-pin'); ?></span>
							<span class="hp-location-pill__name"><?php echo esc_html($name); ?></span>
						<?php if ($link_url) : ?>
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
 * Render the About page CTA block with centered layout and no card container.
 *
 * @param int $post_id Post ID.
 */
function massitpro_render_about_cta_block($post_id) {
	$group   = (array) massitpro_get_section_meta('cta_block', massitpro_get_render_post_id($post_id), []);
	$eyebrow = trim((string) ($group['eyebrow'] ?? ''));
	$heading = trim((string) ($group['heading'] ?? ''));
	$body    = (string) ($group['body'] ?? '');
	$buttons = massitpro_get_buttons($group['buttons'] ?? []);

	if (! massitpro_has_any_content($eyebrow, $heading, $body, $buttons)) {
		return;
	}
	?>
	<section class="about-cta section-padding section-spacing surface-sand">
		<div class="site-shell">
			<div class="about-cta__inner" data-reveal>
				<?php if ($eyebrow) : ?>
					<p class="section-label"><?php echo esc_html($eyebrow); ?></p>
				<?php endif; ?>
				<?php if ($heading) : ?>
					<h2><?php echo esc_html($heading); ?></h2>
				<?php endif; ?>
				<?php if ($body) : ?>
					<div class="section-copy"><?php echo wp_kses_post($body); ?></div>
				<?php endif; ?>
				<?php massitpro_render_button_row($buttons, 'button-row'); ?>
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
			'page_context'  => '',
		]
	);
	$post_id  = massitpro_get_render_post_id($post_id);
	$group    = (array) massitpro_get_section_meta('testimonials_section', $post_id, []);
	$eyebrow  = trim((string) ($group['eyebrow'] ?? ''));
	$heading  = trim((string) ($group['heading'] ?? ''));
	$body     = (string) ($group['body'] ?? '');

	$is_testimonials_page = 'testimonials' === $opts['page_context'];

	if ($is_testimonials_page) {
		$posts = massitpro_query_posts([
			'post_type'      => 'testimonial',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'orderby'        => 'date',
			'order'          => 'DESC',
		]);
	} else {
		$posts = massitpro_query_random_posts('testimonial', 6);
	}

	if (! $posts) {
		return;
	}

	$items      = [];
	$industries = [];

	foreach ($posts as $testimonial_post) {
		$data = massitpro_get_testimonial_data($testimonial_post);
		$items[] = $data;
		$slug = trim((string) ($data['industry_slug'] ?? ''));
		$name_label = trim((string) ($data['industry'] ?? ''));
		if ($slug && ! isset($industries[$slug])) {
			$industries[$slug] = $name_label;
		}
	}

	$section_classes = trim((string) $opts['surface_class'] . ' ' . (string) $opts['section_class']);
	if ($is_testimonials_page) {
		$section_classes .= ' testimonials-all-reviews-section';
	}
	?>
	<section class="section-padding section-spacing <?php echo esc_attr(trim($section_classes)); ?>" <?php echo $is_testimonials_page ? 'data-mitp-testimonials-section' : ''; ?>>
		<div class="site-shell">
			<?php massitpro_render_section_heading(['label' => $eyebrow, 'title' => $heading, 'copy' => $body]); ?>
			<?php if ($is_testimonials_page && $industries) : ?>
				<div class="testimonials-filter-row">
					<button class="testimonials-filter-btn is-active" data-mitp-testimonials-filter-button="all" type="button"><?php esc_html_e('All', 'massitpro'); ?></button>
					<?php foreach ($industries as $ind_slug => $ind_label) : ?>
						<button class="testimonials-filter-btn" data-mitp-testimonials-filter-button="<?php echo esc_attr($ind_slug); ?>" type="button"><?php echo esc_html($ind_label); ?></button>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
			<div class="<?php echo esc_attr((string) $opts['cards_class']); ?>">
				<?php foreach ($items as $index => $item) : ?>
					<?php
					$quote         = trim((string) ($item['quote'] ?? ''));
					$name          = trim((string) ($item['name'] ?? ''));
					$role          = trim((string) ($item['role'] ?? ''));
					$company       = trim((string) ($item['company'] ?? ''));
					$industry      = trim((string) ($item['industry'] ?? ''));
					$industry_slug = trim((string) ($item['industry_slug'] ?? ''));
					$needs_expand  = $is_testimonials_page && mb_strlen($quote) > 200;
					?>
					<article class="content-card testimonial-card" data-mitp-testimonials-card data-mitp-testimonials-industry="<?php echo esc_attr($industry_slug); ?>" data-reveal style="transition-delay: <?php echo esc_attr(number_format($index * 0.05, 2, '.', '')); ?>s;">
						<div class="testimonial-stars testimonial-stars--left" aria-hidden="true">
							<?php for ($star = 0; $star < 5; $star++) : ?>
								<?php echo massitpro_svg_icon('star'); ?>
							<?php endfor; ?>
						</div>
						<?php if ($quote) : ?>
							<?php if ($needs_expand) : ?>
								<blockquote class="testimonial-quote--truncated"><?php echo esc_html($quote); ?></blockquote>
								<button class="testimonial-read-more" data-mitp-testimonials-read-more type="button"><?php esc_html_e('Read more', 'massitpro'); ?></button>
							<?php else : ?>
								<blockquote><?php echo esc_html($quote); ?></blockquote>
							<?php endif; ?>
						<?php endif; ?>
						<?php if ($name || $role || $company || $industry) : ?>
							<div class="testimonial-meta testimonial-meta--left testimonial-meta--divided">
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
			<?php if ($is_testimonials_page) : ?>
				<nav class="testimonials-pagination" data-mitp-testimonials-pagination aria-label="<?php esc_attr_e('Reviews pagination', 'massitpro'); ?>"></nav>
			<?php endif; ?>
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

	$featured_meta = (array) massitpro_get_section_meta('testimonials_featured_review_section', $post_id, []);
	$override_img  = massitpro_resolve_image_value($featured_meta['image'] ?? null);
	$image         = $override_img ?: massitpro_resolve_image_value($data['image'] ?? null);

	if (! $quote) {
		return;
	}
	?>
	<section class="section-padding section-spacing testimonials-featured-review-section">
		<div class="site-shell">
			<div class="content-card testimonials-featured<?php echo $image ? ' testimonials-featured--has-image' : ''; ?>" data-reveal>
				<div class="testimonials-featured__copy">
					<span class="testimonials-featured__quote-icon" aria-hidden="true"><?php echo massitpro_svg_icon('quote'); ?></span>
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
						<p class="testimonials-featured__role"><?php echo esc_html(trim($role . ($role && $company ? ', ' : '') . $company)); ?></p>
					<?php endif; ?>
					<?php if ($industry) : ?>
						<span class="chip chip--teal"><?php echo esc_html($industry); ?></span>
					<?php endif; ?>
				</div>
				<?php if ($image) : ?>
					<div class="testimonials-featured__media">
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
	<section class="faq-quick-answers-section section-padding section-spacing">
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
					<div class="split-feature__media faq-quick-answers-section__media" data-reveal>
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

	$category_slugs = [];
	foreach (array_keys($groups) as $cat) {
		$category_slugs[$cat] = sanitize_title($cat);
	}
	?>
	<?php
	$faq_heading = trim((string) ($group['heading'] ?? ''));
	$faq_body    = trim((string) ($group['body'] ?? ''));
	?>
	<section class="surface-sand section-padding section-spacing" data-mitp-faq-section>
		<div class="site-shell faq-accordion-shell">
			<?php if ($faq_heading || $faq_body) : ?>
				<?php massitpro_render_section_heading(['title' => $faq_heading, 'copy' => $faq_body, 'align' => 'center']); ?>
			<?php endif; ?>
			<nav class="faq-topic-nav" data-mitp-faq-topic-nav aria-label="<?php esc_attr_e('FAQ Topics', 'massitpro'); ?>">
				<button class="faq-topic-nav__btn is-active" data-mitp-faq-filter-button="all" type="button"><?php esc_html_e('All', 'massitpro'); ?></button>
				<?php foreach ($category_slugs as $cat_label => $cat_slug) : ?>
					<button class="faq-topic-nav__btn" data-mitp-faq-filter-button="<?php echo esc_attr($cat_slug); ?>" type="button"><?php echo esc_html($cat_label); ?></button>
				<?php endforeach; ?>
			</nav>
			<div class="faq-accordion-panel">
				<?php foreach ($groups as $label => $group_items) : ?>
					<div class="faq-group" data-mitp-faq-category="<?php echo esc_attr($category_slugs[$label]); ?>" data-reveal>
						<h2><?php echo esc_html((string) $label); ?></h2>
						<div class="faq-group__list">
							<?php massitpro_render_accordion_items($group_items); ?>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
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
		<div class="site-shell faq-shq-shell">
			<div class="faq-shq-stack">
				<?php foreach ($cards as $card_index => $card) : ?>
					<article class="content-card faq-shq-card" data-reveal style="transition-delay: <?php echo esc_attr(number_format($card_index * 0.05, 2, '.', '')); ?>s;">
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
									'variant' => 'action',
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
	$group   = (array) massitpro_get_section_meta('faq_related_resources_section', $post_id, []);
	$eyebrow = trim((string) ($group['eyebrow'] ?? ''));
	$heading = trim((string) ($group['heading'] ?? ''));
	$body    = (string) ($group['body'] ?? '');
	$count   = max(1, min(6, absint($group['posts_count'] ?? 3)));
	$selected = array_values(array_filter(array_map('absint', (array) ($group['posts'] ?? []))));

	if ($selected) {
		$posts = massitpro_query_posts([
			'post_type'      => 'post',
			'post_status'    => 'publish',
			'post__in'       => array_slice($selected, 0, 3),
			'orderby'        => 'post__in',
			'posts_per_page' => 3,
		]);
	} else {
		$posts = massitpro_query_posts([
			'post_type'           => 'post',
			'post_status'         => 'publish',
			'posts_per_page'      => $count,
			'orderby'             => 'date',
			'order'               => 'DESC',
			'ignore_sticky_posts' => true,
		]);
	}

	if (! $posts) {
		return;
	}

	$display_eyebrow = $eyebrow ?: __('Learn More', 'massitpro');
	$display_heading = $heading ?: __('Related Resources', 'massitpro');
	?>
	<section class="surface-sand section-padding section-spacing faq-related-resources-section">
		<div class="site-shell">
			<?php massitpro_render_section_heading([
				'label' => $display_eyebrow,
				'title' => $display_heading,
				'copy'  => $body,
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
	massitpro_render_about_mission_section('mission_section', $post_id);
	massitpro_render_stats_band_section('stats_section', $post_id, ['surface_class' => 'surface-sand']);
	massitpro_render_icon_cards_section('value_cards_section', $post_id);
	massitpro_render_about_process_section('process_section', $post_id);
	massitpro_render_about_team_section('team_section', $post_id);
	massitpro_render_about_certifications_section('certifications_section', $post_id);
	massitpro_render_about_service_coverage_section('service_coverage_section', $post_id);
	massitpro_render_about_cta_block($post_id);
}

/**
 * Render the services-hub toggle section.
 * Top row (centered): eyebrow, h2, body + Business / Residential toggle.
 * Bottom row: left = image card grid (featured images from linked pages),
 *             right = pill, h3, body, learn-more link, arrow nav.
 *
 * @param int                 $post_id Post ID.
 * @param array<string,mixed> $args    Render arguments.
 */
function massitpro_render_services_toggle_section( $post_id, $args = [] ) {
	$args = wp_parse_args( (array) $args, [
		'surface_class' => '',
		'section_class' => '',
	] );
	$pid = massitpro_get_render_post_id( $post_id );

	/* --- header from business section (has_eyebrow) --- */
	$biz_group  = (array) massitpro_get_section_meta( 'business_services_section', $pid, [] );
	$res_group  = (array) massitpro_get_section_meta( 'residential_services_section', $pid, [] );
	$eyebrow    = trim( (string) ( $biz_group['eyebrow'] ?? '' ) );
	$heading    = trim( (string) ( $biz_group['heading'] ?? '' ) );
	$body       = (string) ( $biz_group['body'] ?? '' );
	$biz_items  = massitpro_filter_rows( (array) ( $biz_group['items'] ?? [] ), [ 'title' ] );
	$res_items  = massitpro_filter_rows( (array) ( $res_group['items'] ?? [] ), [ 'title' ] );

	if ( ! massitpro_has_any_content( $heading, $body, $biz_items, $res_items ) ) {
		return;
	}

	/* Build JSON data so JS can update the detail panel */
	$build_json = function ( $items ) {
		$out = [];
		foreach ( $items as $item ) {
			$out[] = [
				'title'      => trim( (string) ( $item['title'] ?? '' ) ),
				'body'       => trim( (string) ( $item['body'] ?? '' ) ),
				'link_label' => trim( (string) ( $item['link_label'] ?? '' ) ),
				'link_url'   => trim( (string) ( $item['link_url'] ?? '' ) ),
			];
		}
		return $out;
	};
	$biz_json = wp_json_encode( $build_json( $biz_items ) );
	$res_json = wp_json_encode( $build_json( $res_items ) );

	$first_biz = $biz_items ? $biz_items[0] : [];
	?>
	<section class="section-padding section-spacing services-toggle-section <?php echo esc_attr( trim( (string) $args['surface_class'] . ' ' . (string) $args['section_class'] ) ); ?>"
		data-services-toggle
		data-services-biz="<?php echo esc_attr( (string) $biz_json ); ?>"
		data-services-res="<?php echo esc_attr( (string) $res_json ); ?>">
		<div class="site-shell">
			<!-- ── Top row: centered header + tabs ── -->
			<div class="services-toggle__header" data-reveal>
				<?php if ( $eyebrow ) : ?>
					<p class="section-label"><?php echo esc_html( $eyebrow ); ?></p>
				<?php endif; ?>
				<?php if ( $heading ) : ?>
					<h2><?php echo esc_html( $heading ); ?></h2>
				<?php endif; ?>
				<?php if ( $body ) : ?>
					<div class="section-copy"><?php echo wp_kses_post( $body ); ?></div>
				<?php endif; ?>
				<div class="services-toggle__tabs">
					<button class="services-toggle__tab is-active" data-services-tab="business" type="button">
						<?php esc_html_e( 'Business', 'massitpro' ); ?>
					</button>
					<button class="services-toggle__tab" data-services-tab="residential" type="button">
						<?php esc_html_e( 'Residential', 'massitpro' ); ?>
					</button>
				</div>
			</div>

			<!-- ── Bottom row: image cards + detail ── -->
			<div class="services-toggle__content">
				<!-- Left: image card grids -->
				<div class="services-toggle__cards">
					<?php
					$panels = [
						'business'    => $biz_items,
						'residential' => $res_items,
					];
					foreach ( $panels as $key => $items ) :
						$is_biz = 'business' === $key;
					?>
						<div class="services-toggle__panel<?php echo $is_biz ? ' is-active' : ''; ?>" data-services-panel="<?php echo esc_attr( $key ); ?>">
							<div class="services-toggle__grid">
								<?php foreach ( $items as $ci => $item ) :
									$title    = trim( (string) ( $item['title'] ?? '' ) );
									$link_url = trim( (string) ( $item['link_url'] ?? '' ) );
									if ( ! $title ) { continue; }
									$image_id = $link_url ? massitpro_resolve_linked_page_image_id( $link_url ) : 0;
									$img_src  = $image_id ? wp_get_attachment_image_url( $image_id, 'massitpro-card' ) : '';
									$active   = ( 0 === $ci && $is_biz );
								?>
									<a class="services-toggle__card<?php echo $active ? ' is-active' : ''; ?>"
									   href="<?php echo esc_url( $link_url ); ?>"
									   data-card-index="<?php echo esc_attr( (string) $ci ); ?>">
										<?php if ( $img_src ) : ?>
											<img class="services-toggle__card-img" src="<?php echo esc_url( $img_src ); ?>" alt="<?php echo esc_attr( $title ); ?>" loading="lazy">
										<?php else : ?>
											<span class="services-toggle__card-placeholder" aria-hidden="true"></span>
										<?php endif; ?>
										<span class="services-toggle__card-title"><?php echo esc_html( $title ); ?></span>
									</a>
								<?php endforeach; ?>
							</div>
						</div>
					<?php endforeach; ?>
				</div>

				<!-- Right: detail panel -->
				<div class="services-toggle__detail" data-reveal>
					<div class="services-toggle__detail-inner">
						<div class="services-toggle__detail-content">
							<span class="services-toggle__pill" data-detail-pill><?php esc_html_e( 'Business Service', 'massitpro' ); ?></span>
							<h3 class="services-toggle__detail-title" data-detail-title><?php echo esc_html( trim( (string) ( $first_biz['title'] ?? '' ) ) ); ?></h3>
							<p class="services-toggle__detail-body" data-detail-body><?php echo esc_html( trim( (string) ( $first_biz['body'] ?? '' ) ) ); ?></p>
							<?php
							$first_link = trim( (string) ( $first_biz['link_url'] ?? '' ) );
							$first_lbl  = trim( (string) ( $first_biz['link_label'] ?? '' ) );
							?>
							<a class="section-link section-link--inline services-toggle__detail-link" href="<?php echo esc_url( $first_link ); ?>" data-detail-link<?php echo $first_link ? '' : ' hidden'; ?>>
								<span data-detail-link-label><?php echo $first_lbl ? esc_html( $first_lbl ) : esc_html__( 'Learn More', 'massitpro' ); ?></span>
								<span aria-hidden="true"><?php echo massitpro_svg_icon( 'arrow-right' ); ?></span>
							</a>
						</div>
						<div class="services-toggle__detail-nav">
							<button class="icon-button" data-services-detail-prev type="button" aria-label="<?php esc_attr_e( 'Previous', 'massitpro' ); ?>">
								<?php echo massitpro_svg_icon( 'arrow-left' ); ?>
							</button>
							<button class="icon-button" data-services-detail-next type="button" aria-label="<?php esc_attr_e( 'Next', 'massitpro' ); ?>">
								<?php echo massitpro_svg_icon( 'arrow-right' ); ?>
							</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	<?php
}

/**
 * Render a services hub page body.
 *
 * @param int $post_id Post ID.
 */
function massitpro_render_services_page_body($post_id = 0) {
	$post_id = massitpro_get_render_post_id($post_id);
	massitpro_render_services_toggle_section($post_id);
	massitpro_render_stats_band_section('why_trust_section', $post_id, ['surface_class' => 'surface-stone-alt', 'section_class' => 'hp-why-trust']);
	massitpro_render_industries_flipcard_section('served_industries_section', $post_id, ['surface_class' => 'surface-stone-alt', 'carousel_key' => 'hub-industries']);
	massitpro_render_related_links_split_section('related_links_section', $post_id, ['surface_class' => 'surface-sand-warm']);
	massitpro_render_process_section('process_section', $post_id, ['surface_class' => 'surface-sand']);
	massitpro_render_cta_block($post_id, ['section_class' => 'cta-shell--center services-hub-cta-section']);
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
 * Render the locations-hub intro section.
 * Left: eyebrow, h2, body, 3 icon feature items. Right: image.
 *
 * @param string              $field_name Section field name.
 * @param int                 $post_id    Post ID.
 * @param array<string,mixed> $args       Render arguments.
 */
function massitpro_render_location_hub_intro_section( $field_name, $post_id, $args = [] ) {
	$args    = wp_parse_args(
		(array) $args,
		[
			'surface_class' => '',
			'section_class' => '',
		]
	);
	$pid     = massitpro_get_render_post_id( $post_id );
	$group   = (array) massitpro_get_section_meta( $field_name, $pid, [] );
	$eyebrow = trim( (string) ( $group['eyebrow'] ?? '' ) );
	$heading = trim( (string) ( $group['heading'] ?? '' ) );
	$body    = (string) ( $group['body'] ?? '' );
	$image   = massitpro_resolve_image_value( $group['image'] ?? null ) ?: massitpro_get_post_display_image( $pid );
	$items   = massitpro_filter_rows( (array) ( $group['items'] ?? [] ), [ 'title' ] );

	if ( ! $body ) {
		$body = massitpro_get_post_content_html( $pid );
	}

	if ( ! massitpro_has_any_content( $heading, $body ) ) {
		return;
	}
	?>
	<section class="section-padding section-spacing hub-intro-section <?php echo esc_attr( trim( (string) $args['surface_class'] . ' ' . (string) $args['section_class'] ) ); ?>">
		<div class="site-shell">
			<div class="hub-intro">
				<div class="hub-intro__copy" data-reveal>
					<?php if ( $eyebrow ) : ?>
						<p class="section-label"><?php echo esc_html( $eyebrow ); ?></p>
					<?php endif; ?>
					<?php if ( $heading ) : ?>
						<h2><?php echo esc_html( $heading ); ?></h2>
					<?php endif; ?>
					<?php if ( $body ) : ?>
						<div class="section-copy"><?php echo wp_kses_post( $body ); ?></div>
					<?php endif; ?>
					<?php if ( $items ) : ?>
						<div class="hub-intro__features">
							<?php foreach ( $items as $item ) :
								$icon  = trim( (string) ( $item['icon'] ?? 'check' ) );
								$title = trim( (string) ( $item['title'] ?? '' ) );
								$desc  = trim( (string) ( $item['body'] ?? '' ) );
								if ( ! $title ) { continue; }
							?>
								<div class="hub-intro__feature">
									<span class="hub-intro__feature-icon" aria-hidden="true"><?php echo massitpro_svg_icon( $icon ); ?></span>
									<div class="hub-intro__feature-content">
										<h3><?php echo esc_html( $title ); ?></h3>
										<?php if ( $desc ) : ?>
											<p><?php echo esc_html( $desc ); ?></p>
										<?php endif; ?>
									</div>
								</div>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>
				</div>
				<div class="hub-intro__media" data-reveal>
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
 * Render expanding city cards for the locations hub.
 * One card expanded by default, others collapsed showing just the city name.
 *
 * @param string              $field_name Section field name.
 * @param int                 $post_id    Post ID.
 * @param array<string,mixed> $args       Render arguments.
 */
function massitpro_render_expanding_cities_section( $field_name, $post_id, $args = [] ) {
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
	$items   = massitpro_filter_rows( (array) ( $group['items'] ?? [] ), [ 'title' ] );

	if ( ! massitpro_has_any_content( $eyebrow, $heading, $body, $items ) ) {
		return;
	}
	?>
	<section class="section-padding section-spacing <?php echo esc_attr( trim( (string) $args['surface_class'] . ' ' . (string) $args['section_class'] ) ); ?>">
		<div class="site-shell">
			<?php massitpro_render_section_heading( [ 'label' => $eyebrow, 'title' => $heading, 'copy' => $body ] ); ?>
			<?php if ( $items ) : ?>
				<div class="expanding-cities" data-expanding-cities>
					<?php foreach ( $items as $index => $item ) :
						$title    = trim( (string) ( $item['title'] ?? '' ) );
						$body_txt = trim( (string) ( $item['body'] ?? '' ) );
						$link_url = trim( (string) ( $item['link_url'] ?? '' ) );
						$img_id   = absint( $item['image'] ?? 0 );
						$img_src  = $img_id ? wp_get_attachment_image_url( $img_id, 'massitpro-card' ) : '';
						if ( ! $title ) { continue; }
						$is_first = 0 === $index;
					?>
						<div class="city-card<?php echo $is_first ? ' is-expanded' : ''; ?>" data-city-card data-reveal style="transition-delay: <?php echo esc_attr( number_format( $index * 0.04, 2, '.', '' ) ); ?>s;">
							<?php if ( $link_url ) : ?>
								<a class="city-card__link" href="<?php echo esc_url( $link_url ); ?>">
							<?php endif; ?>
								<div class="city-card__overlay">
									<h3 class="city-card__title"><?php echo esc_html( $title ); ?></h3>
								</div>
								<?php if ( $img_src ) : ?>
									<img class="city-card__img" src="<?php echo esc_url( $img_src ); ?>" alt="<?php echo esc_attr( $title ); ?>" loading="lazy">
								<?php else : ?>
									<div class="city-card__img city-card__placeholder" aria-hidden="true"></div>
								<?php endif; ?>
								<div class="city-card__body">
									<h3><?php echo esc_html( $title ); ?></h3>
									<?php if ( $body_txt ) : ?>
										<p><?php echo esc_html( $body_txt ); ?></p>
									<?php endif; ?>
								</div>
							<?php if ( $link_url ) : ?>
								</a>
							<?php endif; ?>
						</div>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>
	</section>
	<?php
}

/**
 * Render a locations hub page body.
 *
 * @param int $post_id Post ID.
 */
function massitpro_render_locations_page_body($post_id = 0) {
	$post_id = massitpro_get_render_post_id($post_id);
	massitpro_render_location_hub_intro_section('intro_section', $post_id);
	massitpro_render_expanding_cities_section('featured_locations_section', $post_id, ['surface_class' => 'surface-sand']);
	massitpro_render_services_carousel_section('service_highlights_section', $post_id, ['surface_class' => 'surface-stone-alt', 'carousel_key' => 'hub-services']);
	massitpro_render_related_links_split_section('related_industry_section', $post_id, ['surface_class' => 'surface-sand-warm']);
	massitpro_render_cta_block($post_id, ['section_class' => 'cta-shell--center locations-hub-cta-section']);
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

	massitpro_render_projects_filter_grid($post_id);
	massitpro_render_projects_process_steps('process_section', $post_id);
	massitpro_render_industries_flipcard_section('industries_section', $post_id, [
		'surface_class' => 'surface-stone-alt',
		'carousel_key'  => 'proj-industries',
	]);
	massitpro_render_cta_block($post_id, ['section_class' => 'cta-shell--center projects-cta-section']);
}

/**
 * Render the projects filter bar and alternating feature rows.
 *
 * @param int $post_id Projects page ID.
 */
function massitpro_render_projects_filter_grid($post_id) {
	$post_id = massitpro_get_render_post_id($post_id);
	$group   = (array) massitpro_get_section_meta('projects_grid_section', $post_id, []);
	$eyebrow = trim((string) ($group['eyebrow'] ?? ''));
	$heading = trim((string) ($group['heading'] ?? ''));
	$body    = (string) ($group['body'] ?? '');

	$items = massitpro_query_posts([
		'post_type'      => 'project',
		'post_status'    => 'publish',
		'posts_per_page' => -1,
		'orderby'        => ['menu_order' => 'ASC', 'date' => 'DESC'],
	]);

	if (! $items) {
		return;
	}

	$terms = get_terms([
		'taxonomy'   => 'project_category',
		'hide_empty' => true,
	]);

	$has_terms = ! is_wp_error($terms) && ! empty($terms);
	$row_index = 0;
	?>
	<section class="section-padding section-spacing projects-grid-section" data-mitp-projects-section>
		<div class="site-shell projects-grid-shell">
			<?php if ($heading || $body) : ?>
				<?php massitpro_render_section_heading(['label' => $eyebrow, 'title' => $heading, 'copy' => $body, 'align' => 'center']); ?>
			<?php endif; ?>
			<?php if ($has_terms) : ?>
				<nav class="faq-topic-nav projects-topic-nav" data-mitp-projects-topic-nav>
					<button class="faq-topic-nav__btn is-active" data-mitp-projects-filter-button="all" type="button"><?php esc_html_e('All', 'massitpro'); ?></button>
					<?php foreach ($terms as $term) : ?>
						<button class="faq-topic-nav__btn" data-mitp-projects-filter-button="<?php echo esc_attr($term->slug); ?>" type="button"><?php echo esc_html($term->name); ?></button>
					<?php endforeach; ?>
				</nav>
			<?php endif; ?>
			<div class="project-feature-rows">
				<?php foreach ($items as $item) :
					$data      = massitpro_get_project_data($item);
					$title     = trim((string) $data['title']);
					$image     = massitpro_resolve_image_value($data['image'] ?? null);
					$gallery   = (array) ($data['gallery'] ?? []);
					$client    = trim((string) $data['client_name']);
					$industry  = trim((string) $data['industry_label']);
					$challenge = trim((string) $data['challenge']);
					$solution  = trim((string) $data['solution']);
					$results   = (array) $data['results'];
					$category  = trim((string) $data['category']);
					$link      = trim((string) $data['link']);
					$label     = $industry ?: $category ?: trim((string) $data['subtitle']);
					$reversed  = $row_index % 2 !== 0;

					$p_terms = get_the_terms($item->ID, 'project_category');
					$cat_slug = '';
					if (! is_wp_error($p_terms) && ! empty($p_terms)) {
						$cat_slug = $p_terms[0]->slug;
					}

					if (! $title && ! $image && ! $challenge && ! $solution) {
						continue;
					}

					$all_images = [];
					if ($image) {
						$all_images[] = $image;
					}
					foreach ($gallery as $gid) {
						if ($gid && $gid !== $image) {
							$all_images[] = $gid;
						}
					}
					$has_slideshow = count($all_images) > 1;
				?>
					<article class="project-feature-row<?php echo $reversed ? ' project-feature-row--reversed' : ''; ?>" data-mitp-projects-card data-mitp-projects-category="<?php echo esc_attr($cat_slug); ?>" data-reveal>
						<?php if ($all_images) : ?>
							<div class="project-feature-row__media<?php echo $has_slideshow ? ' project-slideshow' : ''; ?>"<?php echo $has_slideshow ? ' data-mitp-slideshow' : ''; ?>>
								<?php foreach ($all_images as $slide_i => $slide_id) : ?>
									<div class="project-slideshow__slide<?php echo 0 === $slide_i ? ' is-active' : ''; ?>">
										<?php massitpro_render_media(['image' => $slide_id, 'aspect' => 'video']); ?>
									</div>
								<?php endforeach; ?>
								<?php if ($has_slideshow) : ?>
									<button class="project-slideshow__arrow project-slideshow__arrow--prev" data-mitp-slide-prev type="button" aria-label="<?php esc_attr_e('Previous image', 'massitpro'); ?>">&#8249;</button>
									<button class="project-slideshow__arrow project-slideshow__arrow--next" data-mitp-slide-next type="button" aria-label="<?php esc_attr_e('Next image', 'massitpro'); ?>">&#8250;</button>
								<?php endif; ?>
							</div>
						<?php endif; ?>
						<div class="project-feature-row__content">
							<div class="meta-row">
								<?php if ($label) : ?>
									<span class="chip chip--teal"><?php echo esc_html($label); ?></span>
								<?php endif; ?>
								<?php if ($client) : ?>
									<span class="meta-date"><?php echo esc_html($client); ?></span>
								<?php endif; ?>
							</div>
							<?php if ($title) : ?>
								<h3>
									<?php if ($link) : ?>
										<a href="<?php echo esc_url($link); ?>"><?php echo esc_html($title); ?></a>
									<?php else : ?>
										<?php echo esc_html($title); ?>
									<?php endif; ?>
								</h3>
							<?php endif; ?>
							<?php if ($challenge) : ?>
								<div class="project-feature-row__detail">
									<strong><?php esc_html_e('CHALLENGE', 'massitpro'); ?></strong>
									<p><?php echo esc_html($challenge); ?></p>
								</div>
							<?php endif; ?>
							<?php if ($solution) : ?>
								<div class="project-feature-row__detail">
									<strong><?php esc_html_e('SOLUTION', 'massitpro'); ?></strong>
									<p><?php echo esc_html($solution); ?></p>
								</div>
							<?php endif; ?>
							<?php if ($results) : ?>
								<div class="project-feature-row__results">
									<strong><?php esc_html_e('RESULTS', 'massitpro'); ?></strong>
									<ul>
										<?php foreach ($results as $result) : ?>
											<li><?php echo esc_html($result); ?></li>
										<?php endforeach; ?>
									</ul>
								</div>
							<?php endif; ?>
						</div>
					</article>
				<?php
					$row_index++;
				endforeach; ?>
			</div>
			<nav class="testimonials-pagination" data-mitp-projects-pagination aria-label="<?php esc_attr_e('Projects pagination', 'massitpro'); ?>"></nav>
		</div>
	</section>
	<?php
}

/**
 * Render a single project as an alternating feature row.
 *
 * @param WP_Post $post  Project post.
 * @param int     $index Row index.
 */
function massitpro_render_project_feature_row($post, $index = 0) {
	$data      = massitpro_get_project_data($post);
	$title     = trim((string) $data['title']);
	$image     = massitpro_resolve_image_value($data['image'] ?? null);
	$client    = trim((string) $data['client_name']);
	$industry  = trim((string) $data['industry_label']);
	$challenge = trim((string) $data['challenge']);
	$solution  = trim((string) $data['solution']);
	$results   = (array) $data['results'];
	$category  = trim((string) $data['category']);
	$link      = trim((string) $data['link']);
	$label     = $industry ?: $category ?: trim((string) $data['subtitle']);
	$reversed  = $index % 2 !== 0;

	$terms = get_the_terms($post->ID, 'project_category');
	$term_slugs = [];

	if (! is_wp_error($terms) && ! empty($terms)) {
		foreach ($terms as $term) {
			$term_slugs[] = $term->slug;
		}
	}

	if (! $title && ! $image && ! $challenge && ! $solution) {
		return;
	}
	?>
	<article class="project-feature-row<?php echo $reversed ? ' project-feature-row--reversed' : ''; ?>" data-reveal data-categories="<?php echo esc_attr(implode(',', $term_slugs)); ?>" style="transition-delay: <?php echo esc_attr(number_format($index * 0.05, 2, '.', '')); ?>s;">
		<?php if ($image) : ?>
			<div class="project-feature-row__media">
				<?php if ($link) : ?>
					<a href="<?php echo esc_url($link); ?>">
						<?php massitpro_render_media(['image' => $image, 'aspect' => 'video']); ?>
					</a>
				<?php else : ?>
					<?php massitpro_render_media(['image' => $image, 'aspect' => 'video']); ?>
				<?php endif; ?>
			</div>
		<?php endif; ?>
		<div class="project-feature-row__content">
			<div class="meta-row">
				<?php if ($label) : ?>
					<span class="chip chip--teal"><?php echo esc_html($label); ?></span>
				<?php endif; ?>
				<?php if ($client) : ?>
					<span class="meta-date"><?php echo esc_html($client); ?></span>
				<?php endif; ?>
			</div>
			<?php if ($title) : ?>
				<h3>
					<?php if ($link) : ?>
						<a href="<?php echo esc_url($link); ?>"><?php echo esc_html($title); ?></a>
					<?php else : ?>
						<?php echo esc_html($title); ?>
					<?php endif; ?>
				</h3>
			<?php endif; ?>
			<?php if ($challenge) : ?>
				<div class="project-feature-row__detail">
					<strong><?php esc_html_e('Challenge:', 'massitpro'); ?></strong>
					<p><?php echo esc_html($challenge); ?></p>
				</div>
			<?php endif; ?>
			<?php if ($solution) : ?>
				<div class="project-feature-row__detail">
					<strong><?php esc_html_e('Solution:', 'massitpro'); ?></strong>
					<p><?php echo esc_html($solution); ?></p>
				</div>
			<?php endif; ?>
			<?php if ($results) : ?>
				<div class="project-feature-row__results">
					<strong><?php esc_html_e('Results:', 'massitpro'); ?></strong>
					<ul>
						<?php foreach ($results as $result) : ?>
							<li><?php echo esc_html($result); ?></li>
						<?php endforeach; ?>
					</ul>
				</div>
			<?php endif; ?>
		</div>
	</article>
	<?php
}

/**
 * Render the Testimonials page location coverage section.
 *
 * @param int $post_id Post ID.
 */
function massitpro_render_testimonials_location_section($post_id = 0) {
	massitpro_render_related_links_split_section('testimonials_location_section', $post_id, [
		'section_class' => 'testimonials-location-related-links-section',
	]);
}

/**
 * Render the testimonials page body.
 *
 * @param int $post_id Post ID.
 */
function massitpro_render_testimonials_page_body($post_id = 0) {
	$post_id = massitpro_get_render_post_id($post_id);
	massitpro_render_cpt_testimonials_featured($post_id);
	massitpro_render_cpt_testimonials_section($post_id, ['page_context' => 'testimonials']);
	massitpro_render_stats_band_section('stats_section', $post_id, ['surface_class' => 'surface-sand']);
	massitpro_render_testimonials_location_section($post_id);
	massitpro_render_cta_block($post_id, ['section_class' => 'cta-shell--center testimonials-cta-section']);
}

/**
 * Render the FAQ Location section with location pills.
 *
 * @param int $post_id Post ID.
 */
function massitpro_render_faq_location_section($post_id = 0) {
	massitpro_render_related_links_split_section('faq_location_section', $post_id, [
		'section_class' => 'faq-location-related-links-section',
	]);
}

/**
 * Render the FAQ page body.
 *
 * @param int $post_id Post ID.
 */
function massitpro_render_faq_page_body($post_id = 0) {
	$post_id = massitpro_get_render_post_id($post_id);
	massitpro_render_faq_quick_answers('quick_answers_section', $post_id);
	massitpro_render_faq_accordion('faq_accordion_section', $post_id);
	massitpro_render_faq_still_have_questions('still_have_questions_section', $post_id);
	massitpro_render_faq_location_section($post_id);
	massitpro_render_faq_related_resources($post_id);
	massitpro_render_cta_block($post_id, ['section_class' => 'cta-shell--center faq-cta-section']);
}

/**
 * Render the Contact page form & info cards section.
 *
 * @param int $post_id Post ID.
 */
function massitpro_render_contact_form_section($post_id) {
	$post_id = massitpro_get_render_post_id($post_id);
	$group   = (array) massitpro_get_section_meta('contact_form_section', $post_id, []);
	$eyebrow = trim((string) ($group['eyebrow'] ?? ''));
	$heading = trim((string) ($group['heading'] ?? ''));
	$body    = (string) ($group['body'] ?? '');
	$cards   = (array) ($group['cards'] ?? []);

	$form_mode      = (string) ($group['form_mode'] ?? 'native');
	$form_shortcode = trim((string) ($group['form_shortcode'] ?? ''));
	$form_heading   = trim((string) ($group['form_heading'] ?? ''));
	$form_body      = (string) ($group['form_body'] ?? '');
	$submit_label   = trim((string) ($group['submit_label'] ?? ''));
	$privacy_url    = trim((string) ($group['privacy_url'] ?? ''));

	$non_empty_cards = [];
	foreach ($cards as $card) {
		$card = (array) $card;
		if (! empty($card['body']) || ! empty($card['heading'])) {
			$non_empty_cards[] = $card;
		}
	}
	$non_empty_cards = array_slice($non_empty_cards, 0, 5);

	$has_form = ('shortcode' === $form_mode && $form_shortcode) || 'native' === $form_mode;

	if (! massitpro_has_any_content($eyebrow, $heading, $body, $non_empty_cards) && ! $has_form) {
		return;
	}
	?>
	<section class="contact-info-section section-padding section-spacing">
		<div class="site-shell">
			<?php if ($eyebrow || $heading || $body) : ?>
				<?php massitpro_render_section_heading(['label' => $eyebrow, 'title' => $heading, 'copy' => $body, 'align' => 'center']); ?>
			<?php endif; ?>
			<div class="contact-info-section__grid">
				<div class="contact-form-panel content-card" data-reveal>
					<?php if ($form_heading) : ?>
						<h3 class="contact-form-panel__heading"><?php echo esc_html($form_heading); ?></h3>
					<?php endif; ?>
					<?php if ($form_body) : ?>
						<div class="contact-form-panel__intro"><?php echo wp_kses_post($form_body); ?></div>
					<?php endif; ?>
					<?php if ('shortcode' === $form_mode && $form_shortcode) : ?>
						<div class="contact-form-panel__embed"><?php echo do_shortcode($form_shortcode); ?></div>
					<?php else : ?>
						<?php massitpro_render_native_contact_form($post_id, $submit_label, $privacy_url); ?>
					<?php endif; ?>
				</div>
				<?php if ($non_empty_cards) : ?>
					<div class="contact-methods" data-reveal>
						<?php foreach ($non_empty_cards as $index => $card) : ?>
							<?php
								$icon     = (string) ($card['icon'] ?? 'check');
								$c_body   = trim((string) ($card['body'] ?? ''));
								$c_head   = trim((string) ($card['heading'] ?? ''));
								$link_url = trim((string) ($card['link_url'] ?? ''));
							?>
							<div class="contact-method-card content-card" data-reveal style="transition-delay: <?php echo esc_attr(number_format($index * 0.05, 2, '.', '')); ?>s;">
								<div class="contact-method-card__icon">
									<div class="soft-icon" aria-hidden="true"><?php echo massitpro_svg_icon($icon); ?></div>
								</div>
								<div class="contact-method-card__content">
									<?php if ($c_body) : ?>
										<?php if ($link_url) : ?>
											<a href="<?php echo esc_url($link_url); ?>" class="contact-method-card__body"><?php echo esc_html($c_body); ?></a>
										<?php else : ?>
											<p class="contact-method-card__body"><?php echo esc_html($c_body); ?></p>
										<?php endif; ?>
									<?php endif; ?>
									<?php if ($c_head) : ?>
										<h3 class="contact-method-card__heading"><?php echo esc_html($c_head); ?></h3>
									<?php endif; ?>
								</div>
							</div>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</section>
	<?php
}

/**
 * Render the native theme contact form.
 *
 * @param int    $post_id      Post ID.
 * @param string $submit_label Submit button label.
 * @param string $privacy_url  Privacy policy URL.
 */
function massitpro_render_native_contact_form($post_id, $submit_label = '', $privacy_url = '') {
	$submit_label = $submit_label ?: __('Send Message', 'massitpro');
	?>
	<form class="massitpro-contact-form" method="post" novalidate>
		<?php wp_nonce_field('massitpro_contact_submit', '_massitpro_contact_nonce'); ?>
		<input type="hidden" name="action" value="massitpro_contact_submit">
		<input type="hidden" name="massitpro_contact_page_id" value="<?php echo esc_attr((string) $post_id); ?>">
		<div class="massitpro-contact-hp" aria-hidden="true" tabindex="-1">
			<input type="text" name="massitpro_hp_field" value="" autocomplete="off" tabindex="-1">
		</div>
		<div class="massitpro-contact-form__row massitpro-contact-form__row--2col">
			<div class="massitpro-contact-field-group">
				<label for="mcf_firstname"><?php esc_html_e('First Name', 'massitpro'); ?> <span class="required">*</span></label>
				<input type="text" id="mcf_firstname" name="massitpro_contact[firstname]" class="massitpro-contact-field" required>
			</div>
			<div class="massitpro-contact-field-group">
				<label for="mcf_lastname"><?php esc_html_e('Last Name', 'massitpro'); ?> <span class="required">*</span></label>
				<input type="text" id="mcf_lastname" name="massitpro_contact[lastname]" class="massitpro-contact-field" required>
			</div>
		</div>
		<div class="massitpro-contact-form__row massitpro-contact-form__row--2col">
			<div class="massitpro-contact-field-group">
				<label for="mcf_email"><?php esc_html_e('Email', 'massitpro'); ?> <span class="required">*</span></label>
				<input type="email" id="mcf_email" name="massitpro_contact[email]" class="massitpro-contact-field" required>
			</div>
			<div class="massitpro-contact-field-group">
				<label for="mcf_phone"><?php esc_html_e('Phone', 'massitpro'); ?> <span class="required">*</span></label>
				<input type="tel" id="mcf_phone" name="massitpro_contact[phone]" class="massitpro-contact-field" required>
			</div>
		</div>
		<div class="massitpro-contact-form__row">
			<div class="massitpro-contact-field-group">
				<label for="mcf_zip"><?php esc_html_e('ZIP Code', 'massitpro'); ?> <span class="required">*</span></label>
				<input type="text" id="mcf_zip" name="massitpro_contact[zip]" class="massitpro-contact-field" required>
			</div>
		</div>
		<div class="massitpro-contact-form__row">
			<div class="massitpro-contact-field-group massitpro-contact-service-type">
				<p class="massitpro-contact-field-group__label"><?php esc_html_e('Service Type', 'massitpro'); ?> <span class="required">*</span></p>
				<div class="massitpro-contact-pills">
					<label class="massitpro-contact-pill">
						<input type="radio" name="massitpro_contact[servicetype]" value="Home">
						<span><?php esc_html_e('Home', 'massitpro'); ?></span>
					</label>
					<label class="massitpro-contact-pill">
						<input type="radio" name="massitpro_contact[servicetype]" value="Business">
						<span><?php esc_html_e('Business', 'massitpro'); ?></span>
					</label>
				</div>
			</div>
		</div>
		<div class="massitpro-contact-form__row massitpro-contact-business-fields" style="display:none;">
			<div class="massitpro-contact-form__row massitpro-contact-form__row--2col">
				<div class="massitpro-contact-field-group">
					<label for="mcf_company"><?php esc_html_e('Company Name', 'massitpro'); ?> <span class="required">*</span></label>
					<input type="text" id="mcf_company" name="massitpro_contact[company]" class="massitpro-contact-field">
				</div>
				<div class="massitpro-contact-field-group">
					<label for="mcf_employees"><?php esc_html_e('Number of Employees', 'massitpro'); ?> <span class="required">*</span></label>
					<select id="mcf_employees" name="massitpro_contact[employees]" class="massitpro-contact-field">
						<option value=""><?php esc_html_e('Select...', 'massitpro'); ?></option>
						<option value="1-10">1-10</option>
						<option value="11-50">11-50</option>
						<option value="51-200">51-200</option>
						<option value="201-500">201-500</option>
						<option value="500+">500+</option>
					</select>
				</div>
			</div>
		</div>
		<div class="massitpro-contact-form__row massitpro-contact-business-services" style="display:none;">
			<div class="massitpro-contact-field-group">
				<p class="massitpro-contact-field-group__label"><?php esc_html_e('Business Services', 'massitpro'); ?> <span class="required">*</span></p>
				<div class="massitpro-contact-pills">
					<?php
					$biz_services = [
						'Managed IT Services',
						'Cybersecurity',
						'IT Support & Help Desk',
						'Cloud Solutions',
						'Network Solutions',
						'Backup & Recovery',
						'Compliance',
						'Web Design',
						'Remote Support',
					];
					foreach ($biz_services as $svc) : ?>
						<label class="massitpro-contact-pill">
							<input type="checkbox" name="massitpro_contact[service_business][]" value="<?php echo esc_attr($svc); ?>">
							<span><?php echo esc_html($svc); ?></span>
						</label>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
		<div class="massitpro-contact-form__row massitpro-contact-home-services" style="display:none;">
			<div class="massitpro-contact-field-group">
				<p class="massitpro-contact-field-group__label"><?php esc_html_e('Home Services', 'massitpro'); ?> <span class="required">*</span></p>
				<div class="massitpro-contact-pills">
					<?php
					$home_services = [
						'PC & Mac Repair',
						'Virus Removal',
						'OS Upgrades',
						'Smart Home',
						'WiFi & Network',
						'Data Recovery',
						'Remote Support',
					];
					foreach ($home_services as $svc) : ?>
						<label class="massitpro-contact-pill">
							<input type="checkbox" name="massitpro_contact[service_home][]" value="<?php echo esc_attr($svc); ?>">
							<span><?php echo esc_html($svc); ?></span>
						</label>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
		<div class="massitpro-contact-form__row">
			<div class="massitpro-contact-field-group">
				<label for="mcf_message"><?php esc_html_e('Message', 'massitpro'); ?> <span class="required">*</span></label>
				<textarea id="mcf_message" name="massitpro_contact[message]" class="massitpro-contact-field" rows="4" required></textarea>
			</div>
		</div>
		<div class="massitpro-contact-form__row">
			<div class="massitpro-contact-field-group massitpro-contact-field-group--accept">
				<label class="massitpro-contact-accept-label">
					<input type="checkbox" name="massitpro_contact[agreetoterms]" value="1" required>
					<span>
						<?php
						if ($privacy_url) {
							printf(
								/* translators: %s: privacy policy link */
								esc_html__('I agree to the %s and consent to being contacted.', 'massitpro'),
								'<a href="' . esc_url($privacy_url) . '" target="_blank" rel="noopener noreferrer" class="massitpro-contact-pp-link">' . esc_html__('Privacy Policy', 'massitpro') . '</a>'
							);
						} else {
							esc_html_e('I agree to the Privacy Policy and consent to being contacted.', 'massitpro');
						}
						?>
					</span>
				</label>
			</div>
		</div>
		<div class="massitpro-contact-form__row">
			<button type="submit" class="theme-button theme-button--action theme-button--lg massitpro-contact-form__submit">
				<span><?php echo esc_html($submit_label); ?></span>
			</button>
		</div>
		<div class="massitpro-contact-form__status" role="alert" aria-live="polite"></div>
	</form>
	<?php
}

/**
 * Render the Contact page industries section using flip-card carousel.
 *
 * @param int $post_id Post ID.
 */
function massitpro_render_contact_industries_section($post_id) {
	massitpro_render_industries_flipcard_section('contact_industries_section', $post_id, [
		'carousel_key' => 'contact-industries',
	]);
}

/**
 * Render the Contact page location section.
 *
 * @param int $post_id Post ID.
 */
function massitpro_render_contact_location_section($post_id) {
	$post_id = massitpro_get_render_post_id($post_id);
	$group   = (array) massitpro_get_section_meta('contact_location_section', $post_id, []);
	$eyebrow = trim((string) ($group['eyebrow'] ?? ''));
	$heading = trim((string) ($group['heading'] ?? ''));
	$body    = (string) ($group['body'] ?? '');
	$image   = massitpro_resolve_image_value($group['image'] ?? null);
	$btn_label = trim((string) ($group['button_label'] ?? ''));
	$btn_url   = trim((string) ($group['button_url'] ?? ''));
	$links     = (array) ($group['links'] ?? []);

	$non_empty_links = [];
	foreach ($links as $link) {
		$link = (array) $link;
		if (! empty($link['label'])) {
			$non_empty_links[] = $link;
		}
	}

	if (! massitpro_has_any_content($eyebrow, $heading, $body, $image, $non_empty_links)) {
		return;
	}
	?>
	<section class="contact-location section-padding section-spacing surface-sand">
		<div class="site-shell">
			<div class="split-feature">
				<div class="split-feature__copy contact-location__copy" data-reveal>
					<?php if ($eyebrow) : ?>
						<p class="section-label"><?php echo esc_html($eyebrow); ?></p>
					<?php endif; ?>
					<?php if ($heading) : ?>
						<h2><?php echo esc_html($heading); ?></h2>
					<?php endif; ?>
					<?php if ($body) : ?>
						<div class="section-copy"><?php echo wp_kses_post($body); ?></div>
					<?php endif; ?>
					<?php if ($non_empty_links) : ?>
						<div class="contact-location__links">
							<?php foreach ($non_empty_links as $link) :
								$link_label = trim((string) ($link['label'] ?? ''));
								$link_url   = trim((string) ($link['url'] ?? ''));
							?>
								<?php if ($link_url) : ?>
									<a href="<?php echo esc_url($link_url); ?>" class="contact-location__link">
										<span class="contact-location__link-icon"><?php echo massitpro_svg_icon('map-pin'); ?></span>
										<span><?php echo esc_html($link_label); ?></span>
									</a>
								<?php else : ?>
									<span class="contact-location__link">
										<span class="contact-location__link-icon"><?php echo massitpro_svg_icon('map-pin'); ?></span>
										<span><?php echo esc_html($link_label); ?></span>
									</span>
								<?php endif; ?>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>
					<?php if ($btn_label && $btn_url) : ?>
						<div class="button-row" style="margin-top:28px;">
							<?php massitpro_render_button(['label' => $btn_label, 'url' => $btn_url, 'variant' => 'primary']); ?>
						</div>
					<?php endif; ?>
				</div>
				<div class="split-feature__media" data-reveal>
					<?php if ($image) : ?>
						<?php massitpro_render_media(['image' => $image, 'aspect' => 'square']); ?>
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
 * Render the Contact page CTA block with centered layout.
 *
 * @param int $post_id Post ID.
 */
function massitpro_render_contact_cta_block($post_id) {
	$group   = (array) massitpro_get_section_meta('cta_block', massitpro_get_render_post_id($post_id), []);
	$eyebrow = trim((string) ($group['eyebrow'] ?? ''));
	$heading = trim((string) ($group['heading'] ?? ''));
	$body    = (string) ($group['body'] ?? '');
	$buttons = massitpro_get_buttons($group['buttons'] ?? []);

	if (! massitpro_has_any_content($eyebrow, $heading, $body, $buttons)) {
		return;
	}
	?>
	<section class="contact-cta section-padding section-spacing surface-sand">
		<div class="site-shell">
			<div class="contact-cta__inner" data-reveal>
				<?php if ($eyebrow) : ?>
					<p class="section-label"><?php echo esc_html($eyebrow); ?></p>
				<?php endif; ?>
				<?php if ($heading) : ?>
					<h2><?php echo esc_html($heading); ?></h2>
				<?php endif; ?>
				<?php if ($body) : ?>
					<div class="section-copy"><?php echo wp_kses_post($body); ?></div>
				<?php endif; ?>
				<?php massitpro_render_button_row($buttons, 'button-row'); ?>
			</div>
		</div>
	</section>
	<?php
}

/**
 * Render the contact page body.
 *
 * @param int $post_id Post ID.
 */
function massitpro_render_contact_page_body($post_id = 0) {
	$post_id = massitpro_get_render_post_id($post_id);
	massitpro_render_contact_form_section($post_id);
	massitpro_render_process_section('contact_process_section', $post_id);
	massitpro_render_contact_industries_section($post_id);
	massitpro_render_contact_location_section($post_id);
	massitpro_render_contact_cta_block($post_id);
}

/**
 * Render the blog page body.
 *
 * @param int $post_id Post ID.
 */
function massitpro_render_blog_page_body($post_id = 0) {
	$post_id = massitpro_get_render_post_id($post_id);

	massitpro_render_blog_featured_article($post_id);
	massitpro_render_blog_posts_grid($post_id);
	massitpro_render_blog_topics_section($post_id);
	massitpro_render_spotlight_section('newsletter_section', $post_id);
	massitpro_render_cta_block($post_id);
}

/**
 * Render the blog featured article section.
 *
 * @param int $post_id Blog page ID.
 */
function massitpro_render_blog_featured_article($post_id) {
	$group    = (array) massitpro_get_section_meta('featured_article_section', $post_id, []);
	$selected = array_map('intval', array_filter((array) ($group['posts'] ?? [])));
	$featured = null;

	if ($selected) {
		$featured_query = massitpro_query_posts([
			'post_type'      => 'post',
			'post_status'    => 'publish',
			'post__in'       => $selected,
			'posts_per_page' => 1,
			'orderby'        => 'post__in',
		]);

		if ($featured_query) {
			$featured = $featured_query[0];
		}
	}

	if (! $featured) {
		$latest = massitpro_query_posts([
			'post_type'           => 'post',
			'post_status'         => 'publish',
			'posts_per_page'      => 1,
			'ignore_sticky_posts' => true,
		]);

		if ($latest) {
			$featured = $latest[0];
		}
	}

	if (! $featured) {
		return;
	}

	$image    = massitpro_get_post_display_image($featured->ID);
	$excerpt  = massitpro_get_post_summary_text($featured->ID);
	$category = get_the_category($featured->ID);
	?>
	<section class="section-padding section-spacing">
		<div class="site-shell">
			<div class="blog-featured-article" data-reveal>
				<?php if ($image) : ?>
					<div class="blog-featured-article__media">
						<a href="<?php echo esc_url(get_permalink($featured)); ?>">
							<?php massitpro_render_media(['image' => $image, 'aspect' => 'video']); ?>
						</a>
					</div>
				<?php endif; ?>
				<div class="blog-featured-article__content">
					<div class="meta-row">
						<?php if (! empty($category[0])) : ?>
							<span class="chip chip--teal"><?php echo esc_html($category[0]->name); ?></span>
						<?php endif; ?>
						<span class="meta-date"><?php echo esc_html(get_the_date('', $featured)); ?></span>
					</div>
					<h2><a href="<?php echo esc_url(get_permalink($featured)); ?>"><?php echo esc_html(get_the_title($featured)); ?></a></h2>
					<?php if ($excerpt) : ?>
						<p><?php echo esc_html($excerpt); ?></p>
					<?php endif; ?>
					<div class="button-row">
						<?php massitpro_render_button(['label' => __('Read Article', 'massitpro'), 'url' => get_permalink($featured), 'variant' => 'action', 'size' => 'default']); ?>
					</div>
				</div>
			</div>
		</div>
	</section>
	<?php
}

/**
 * Render the blog posts grid with category filters.
 *
 * @param int $post_id Blog page ID.
 */
function massitpro_render_blog_posts_grid($post_id) {
	$paged = max(1, (int) get_query_var('paged'), (int) get_query_var('page'));
	$query = new WP_Query([
		'post_type'           => 'post',
		'post_status'         => 'publish',
		'posts_per_page'      => 9,
		'paged'               => $paged,
		'ignore_sticky_posts' => true,
	]);

	if (! $query->have_posts()) {
		wp_reset_postdata();
		return;
	}

	$categories = get_categories(['hide_empty' => true]);
	?>
	<section class="section-padding section-spacing">
		<div class="site-shell">
			<?php if ($categories) : ?>
				<div class="blog-category-filters" data-reveal>
					<span class="chip chip--teal blog-filter-chip blog-filter-chip--active"><?php esc_html_e('All', 'massitpro'); ?></span>
					<?php foreach ($categories as $cat) : ?>
						<span class="chip blog-filter-chip" data-category="<?php echo esc_attr($cat->slug); ?>"><?php echo esc_html($cat->name); ?></span>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
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
						paginate_links([
							'total'   => $query->max_num_pages,
							'current' => $paged,
							'type'    => 'list',
						])
					);
					?>
				</div>
			<?php endif; ?>
		</div>
	</section>
	<?php
	wp_reset_postdata();
}

/**
 * Render the blog browse-by-topic section from native meta.
 *
 * @param int $post_id Blog page ID.
 */
function massitpro_render_blog_topics_section($post_id) {
	massitpro_render_link_cards_section('topics_section', $post_id, ['surface_class' => 'surface-sand']);
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
			massitpro_render_page_hero(massitpro_prepare_page_hero($post_id));
			massitpro_render_blog_page_body($post_id);
			break;

		default:
			massitpro_render_page_hero(massitpro_prepare_page_hero($post_id));
			massitpro_render_default_page_body($post_id);
			break;
	}
}
