<?php
/**
 * Reusable page hero partial.
 *
 * @package MassITPro
 */

if (! defined('ABSPATH')) {
	exit;
}

$args = wp_parse_args(
	$args ?? [],
	[
		'label'    => '',
		'title'    => '',
		'subtitle' => '',
		'buttons'  => [],
		'image'    => null,
	]
);

$has_image = ! empty($args['image']);
?>
<section class="page-hero surface-navy">
	<div class="page-hero__pattern" aria-hidden="true"></div>
	<div class="page-hero__glow" aria-hidden="true"></div>
	<div class="page-hero__tech-grid" aria-hidden="true"></div>
	<div class="page-hero__particles" aria-hidden="true">
		<?php for ( $i = 0; $i < 12; $i++ ) : ?>
			<span class="page-hero__particle" style="--p-x:<?php echo esc_attr( rand( 5, 95 ) ); ?>%;--p-y:<?php echo esc_attr( rand( 5, 95 ) ); ?>%;--p-d:<?php echo esc_attr( rand( 3, 8 ) ); ?>s;--p-s:<?php echo esc_attr( rand( 2, 5 ) ); ?>px;"></span>
		<?php endfor; ?>
	</div>
	<div class="section-padding site-shell">
		<?php if ($has_image) : ?>
			<div class="home-hero__grid">
		<?php endif; ?>
		<div class="page-hero__inner page-hero__inner--center" data-reveal>
			<?php if ($args['label']) : ?>
				<p class="section-label section-label--dark"><?php echo esc_html((string) $args['label']); ?></p>
			<?php endif; ?>
			<h1 class="page-hero__title"><?php echo esc_html((string) $args['title']); ?></h1>
			<?php if ($args['subtitle']) : ?>
				<p class="page-hero__copy"><?php echo esc_html((string) $args['subtitle']); ?></p>
			<?php endif; ?>
			<?php if (! empty($args['buttons'])) : ?>
				<div class="button-row">
					<?php foreach ((array) $args['buttons'] as $button) : ?>
						<?php massitpro_render_button($button); ?>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>
		<?php if ($has_image) : ?>
			<div class="glass-card home-hero__panel" data-reveal>
				<?php massitpro_render_media(['image' => $args['image'], 'aspect' => 'video']); ?>
			</div>
			</div>
		<?php endif; ?>
	</div>
</section>
