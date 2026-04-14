<?php
/**
 * Singular template.
 *
 * @package MassITPro
 */

get_header();

if (have_posts()) {
	while (have_posts()) {
		the_post();
		massitpro_render_page_hero(
			[
				'label'    => get_post_type_object(get_post_type())->labels->singular_name ?? 'Article',
				'title'    => get_the_title(),
				'subtitle' => get_the_date(),
			]
		);
		?>
		<section class="section-padding section-spacing">
			<div class="site-shell site-shell--content">
				<?php if (has_post_thumbnail()) : ?>
					<div class="entry-media" data-reveal><?php the_post_thumbnail('massitpro-wide'); ?></div>
				<?php endif; ?>
				<div class="content-card entry-content" data-reveal>
					<?php the_content(); ?>
				</div>
			</div>
		</section>
		<?php
	}
}

get_footer();
