<?php
/**
 * Site footer.
 *
 * @package MassITPro
 */

if (! defined('ABSPATH')) {
	exit;
}

$footer_groups = massitpro_get_footer_link_groups();
$footer_cta    = massitpro_get_footer_cta_settings();
$footer_buttons = massitpro_normalize_button_rows($footer_cta['buttons'] ?? []);
$legal_links   = massitpro_get_footer_legal_links();
$phone         = massitpro_theme_option('phone');
$email         = massitpro_theme_option('email');
$service_area  = massitpro_theme_option('service_area');
?>
	</main>
	<footer class="site-footer">
		<?php if (! empty($footer_cta['eyebrow']) || ! empty($footer_cta['title']) || ! empty($footer_cta['body']) || $footer_buttons) : ?>
			<section class="footer-cta surface-navy">
				<div class="section-padding site-shell">
					<div class="footer-cta__inner" data-reveal>
						<?php if (! empty($footer_cta['eyebrow'])) : ?>
							<p class="section-label section-label--dark"><?php echo esc_html((string) $footer_cta['eyebrow']); ?></p>
						<?php endif; ?>
						<?php if (! empty($footer_cta['title'])) : ?>
							<h2 class="footer-cta__title"><?php echo esc_html((string) $footer_cta['title']); ?></h2>
						<?php endif; ?>
						<?php if (! empty($footer_cta['body'])) : ?>
							<div class="footer-cta__copy"><?php echo wp_kses_post((string) $footer_cta['body']); ?></div>
						<?php endif; ?>
						<?php if ($footer_buttons) : ?>
							<div class="button-row button-row--center">
								<?php foreach ($footer_buttons as $button) : ?>
									<?php massitpro_render_button($button); ?>
								<?php endforeach; ?>
							</div>
						<?php endif; ?>
					</div>
				</div>
			</section>
		<?php endif; ?>

		<section class="footer-main surface-navy">
			<div class="section-padding site-shell">
				<div class="footer-grid">
					<?php foreach ($footer_groups as $group_label => $links) : ?>
						<div class="footer-group" data-reveal>
							<h3><?php echo esc_html($group_label); ?></h3>
							<ul>
								<?php foreach ($links as $link) : ?>
									<li><a href="<?php echo esc_url(massitpro_entry_url($link)); ?>"><?php echo esc_html($link['label']); ?></a></li>
								<?php endforeach; ?>
							</ul>
						</div>
					<?php endforeach; ?>
				</div>

				<div class="footer-contact" data-reveal>
					<div class="footer-contact__brand">
						<div class="footer-contact__mark">
							<?php get_template_part('template-parts/site/logo', null, ['context' => 'dark', 'mark' => true]); ?>
						</div>
						<div>
							<p class="footer-contact__title"><?php bloginfo('name'); ?></p>
							<?php if (get_bloginfo('description')) : ?>
								<p class="footer-contact__subtitle"><?php bloginfo('description'); ?></p>
							<?php endif; ?>
						</div>
					</div>

					<div class="footer-contact__items">
						<?php if ($email) : ?>
							<a href="<?php echo esc_url('mailto:' . $email); ?>">
								<span aria-hidden="true"><?php echo massitpro_svg_icon('mail'); ?></span>
								<span><?php echo esc_html($email); ?></span>
							</a>
						<?php endif; ?>
						<?php if ($phone) : ?>
							<a href="<?php echo esc_url(massitpro_tel_href($phone)); ?>">
								<span aria-hidden="true"><?php echo massitpro_svg_icon('phone'); ?></span>
								<span><?php echo esc_html($phone); ?></span>
							</a>
						<?php endif; ?>
						<?php if ($service_area) : ?>
							<span>
								<span aria-hidden="true"><?php echo massitpro_svg_icon('map-pin'); ?></span>
								<span><?php echo esc_html($service_area); ?></span>
							</span>
						<?php endif; ?>
					</div>
				</div>

				<div class="footer-meta" data-reveal>
					<p>&copy; <?php echo esc_html(date_i18n('Y')); ?> <?php bloginfo('name'); ?>. <?php esc_html_e('All rights reserved.', 'massitpro'); ?></p>
					<?php if ($legal_links) : ?>
						<div class="footer-meta__links">
							<?php foreach ($legal_links as $item) : ?>
								<?php $link = massitpro_normalize_link($item['link'] ?? []); ?>
								<?php if (! $link['url'] || ! $link['label']) : ?>
									<?php continue; ?>
								<?php endif; ?>
								<a href="<?php echo esc_url($link['url']); ?>"<?php echo $link['target'] ? ' target="' . esc_attr($link['target']) . '" rel="noopener noreferrer"' : ''; ?>><?php echo esc_html($link['label']); ?></a>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</section>
	</footer>
</div>
<?php wp_footer(); ?>
</body>
</html>
