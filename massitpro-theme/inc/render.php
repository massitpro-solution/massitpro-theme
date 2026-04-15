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
		]
	);
	$group   = (array) massitpro_get_section_meta($field_name, massitpro_get_render_post_id($post_id), []);
	$eyebrow = trim((string) ($group['eyebrow'] ?? ''));
	$heading = trim((string) ($group['heading'] ?? ''));
	$body    = (string) ($group['body'] ?? '');
	$items   = massitpro_filter_rows((array) ($group['items'] ?? []), ['title', 'body', 'image', 'link']);

	if (! massitpro_has_any_content($eyebrow, $heading, $body, $items)) {
		return;
	}
	?>
	<section class="section-padding section-spacing <?php echo esc_attr(trim((string) $args['surface_class'] . ' ' . (string) $args['section_class'])); ?>">
		<div class="site-shell">
			<?php massitpro_render_section_heading(['label' => $eyebrow, 'title' => $heading, 'copy' => $body]); ?>
			<?php if ($items) : ?>
				<div class="<?php echo esc_attr((string) $args['cards_class']); ?>">
					<?php foreach ($items as $index => $item) : ?>
						<?php $link = massitpro_normalize_link($item['link'] ?? []); ?>
						<?php $image = massitpro_resolve_image_value($item['image'] ?? null); ?>
						<article class="content-card media-card feature-grid-card <?php echo esc_attr((string) $args['card_class']); ?><?php echo $image ? '' : ' feature-grid-card--text'; ?>" data-reveal style="transition-delay: <?php echo esc_attr(number_format($index * 0.05, 2, '.', '')); ?>s;">
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
			<?php massitpro_render_section_heading(['label' => $group['eyebrow'] ?? '', 'title' => $group['heading'] ?? '', 'copy' => $group['body'] ?? '']); ?>
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
 * Render the homepage.
 */
function massitpro_render_homepage() {
	$post_id = massitpro_get_render_post_id();
	$hero    = massitpro_prepare_page_hero($post_id);
	$trust   = (array) massitpro_get_section_meta('trust_strip', $post_id, []);
	$stats   = (array) massitpro_get_section_meta('stats_section', $post_id, []);

	massitpro_render_page_hero($hero);

	if (! empty($trust['items'])) :
		?>
		<section class="section-padding section-spacing-small">
			<div class="site-shell">
				<?php massitpro_render_section_heading(['label' => $trust['eyebrow'] ?? '', 'title' => $trust['heading'] ?? '']); ?>
				<div class="chips" data-reveal>
					<?php foreach (massitpro_filter_rows((array) $trust['items'], ['label']) as $item) : ?>
						<span class="chip"><?php echo esc_html((string) $item['label']); ?></span>
					<?php endforeach; ?>
				</div>
			</div>
		</section>
		<?php
	endif;

	if (! empty($stats['items'])) :
		massitpro_render_stats_band_section('stats_section', $post_id, ['surface_class' => 'surface-sand']);
	endif;

	massitpro_render_relationship_cards_section(
		'core_services_section',
		'services',
		static function ($item, $index) {
			massitpro_render_page_card($item, $index, 'compact');
		},
		$post_id,
		[
			'surface_class' => 'surface-sand',
		]
	);
	massitpro_render_relationship_feature_rows_section('services_carousel_section', 'services', $post_id);
	massitpro_render_image_cards_section(
		'why_choose_section',
		$post_id,
		[
			'surface_class' => 'surface-navy',
			'card_class'    => 'feature-grid-card--dark',
		]
	);
	massitpro_render_relationship_cards_section('industries_section', 'industries', 'massitpro_render_page_card', $post_id);
	massitpro_render_relationship_pills_section('locations_section', 'locations', $post_id);
	massitpro_render_relationship_cards_section(
		'projects_section',
		'projects',
		static function ($item, $index) {
			massitpro_render_project_card($item, $index, 'dark');
		},
		$post_id,
		['surface_class' => 'surface-navy']
	);
	massitpro_render_relationship_cards_section('testimonials_section', 'testimonials', 'massitpro_render_testimonial_card', $post_id);
	massitpro_render_image_cards_section('secondary_services_section', $post_id);

	$blog_section = (array) massitpro_get_section_meta('blog_section', $post_id, []);
	$posts        = massitpro_normalize_related_posts($blog_section['posts'] ?? []);

	if (! $posts) {
		$count = max(1, (int) ($blog_section['posts_count'] ?? 3));
		$posts = massitpro_query_posts(
			[
				'post_type'           => 'post',
				'post_status'         => 'publish',
				'posts_per_page'      => min(6, $count),
				'ignore_sticky_posts' => true,
			]
		);
	}

	if (massitpro_has_any_content($blog_section['heading'] ?? '', $blog_section['body'] ?? '', $posts)) :
		?>
		<section class="section-padding section-spacing">
			<div class="site-shell">
				<?php massitpro_render_section_heading(['label' => $blog_section['eyebrow'] ?? '', 'title' => $blog_section['heading'] ?? '', 'copy' => $blog_section['body'] ?? '']); ?>
				<?php if ($posts) : ?>
					<div class="cards-grid cards-grid--3">
						<?php foreach ($posts as $index => $post) : ?>
							<?php massitpro_render_post_card($post, $index); ?>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</div>
		</section>
		<?php
	endif;

	massitpro_render_faq_section('faq_section', 'faqs', $post_id);
	massitpro_render_cta_block($post_id);
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
	massitpro_render_relationship_feature_rows_section('business_services_section', 'services', $post_id);
	massitpro_render_icon_cards_section('why_choose_section', $post_id, ['surface_class' => 'surface-sand']);
	massitpro_render_relationship_cards_section('residential_services_section', 'services', 'massitpro_render_page_card', $post_id, ['surface_class' => 'surface-sand']);
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
	massitpro_render_image_cards_section('related_projects_section', $post_id);
	massitpro_render_manual_testimonials_section('related_testimonials_section', $post_id);
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
 * Render a location detail page body.
 *
 * @param int $post_id Post ID.
 */
function massitpro_render_location_detail_page_body($post_id = 0) {
	$post_id = massitpro_get_render_post_id($post_id);
	massitpro_render_intro_section('overview_section', $post_id);
	massitpro_render_image_cards_section('why_local_section', $post_id);
	massitpro_render_link_cards_section('available_services_section', $post_id);
	massitpro_render_link_cards_section('served_industries_section', $post_id);
	massitpro_render_icon_cards_section('trust_cards_section', $post_id, ['surface_class' => 'surface-sand']);
	massitpro_render_faq_cards_section('faq_section', $post_id);
	massitpro_render_related_links_section('related_links_section', $post_id);
	massitpro_render_cta_block($post_id);
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
	massitpro_render_featured_testimonial_section($post_id);
	massitpro_render_testimonial_filter_grid($post_id);
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
	$posts   = massitpro_query_posts(
		[
			'post_type'      => 'faq_item',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'orderby'        => ['menu_order' => 'ASC', 'title' => 'ASC'],
		]
	);
	$items   = massitpro_get_faq_items($posts);
	$groups  = [];

	foreach ($items as $item) {
		$label             = $item['group_label'] ?: __('General Questions', 'massitpro');
		$groups[$label][]  = $item;
	}

	massitpro_render_default_page_body($post_id);

	if (! $groups) {
		return;
	}
	?>
	<section class="section-padding section-spacing">
		<div class="site-shell site-shell--faq">
			<?php foreach ($groups as $label => $group_items) : ?>
				<div class="faq-group" data-reveal>
					<h2><?php echo esc_html($label); ?></h2>
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
