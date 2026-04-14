<?php
/**
 * Archive template.
 *
 * @package MassITPro
 */

get_header();

massitpro_render_page_hero(
	[
		'label'    => 'Archive',
		'title'    => get_the_archive_title(),
		'subtitle' => wp_strip_all_tags(get_the_archive_description()),
	]
);
?>
<section class="section-padding section-spacing">
	<div class="site-shell cards-grid cards-grid--3">
		<?php if (have_posts()) : ?>
			<?php $index = 0; ?>
			<?php while (have_posts()) : the_post(); ?>
				<article class="content-card media-card" data-reveal style="transition-delay: <?php echo esc_attr((string) ($index * 0.06)); ?>s;">
					<?php if (has_post_thumbnail()) : ?>
						<div class="media-block media-block--video"><?php the_post_thumbnail('massitpro-card'); ?></div>
					<?php endif; ?>
					<div class="media-card__body">
						<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
						<p><?php echo esc_html(get_the_excerpt()); ?></p>
					</div>
				</article>
				<?php $index++; ?>
			<?php endwhile; ?>
		<?php endif; ?>
	</div>
	<div class="site-shell pagination-wrap"><?php the_posts_pagination(['type' => 'list']); ?></div>
</section>
<?php
get_footer();
