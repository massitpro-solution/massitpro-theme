<?php
/**
 * Site header.
 *
 * @package MassITPro
 */

if (! defined('ABSPATH')) {
	exit;
}

$header_cta = massitpro_get_header_primary_cta();
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div class="site-frame">
	<header class="site-header" data-site-header>
		<div class="section-padding site-shell site-header__inner">
			<a class="site-brand" href="<?php echo esc_url(home_url('/')); ?>" rel="home" aria-label="<?php echo esc_attr(get_bloginfo('name')); ?>">
				<span class="site-brand__logo site-brand__logo--light">
					<?php get_template_part('template-parts/site/logo', null, ['context' => 'dark']); ?>
				</span>
				<span class="site-brand__logo site-brand__logo--dark">
					<?php get_template_part('template-parts/site/logo', null, ['context' => 'light']); ?>
				</span>
			</a>

			<nav class="site-nav site-nav--desktop" aria-label="<?php esc_attr_e('Primary navigation', 'massitpro'); ?>">
				<?php
				wp_nav_menu(
					[
						'theme_location' => 'primary',
						'container'      => false,
						'menu_class'     => 'site-menu site-menu--desktop',
						'fallback_cb'    => 'massitpro_primary_menu_fallback',
					]
				);
				?>
			</nav>

			<div class="site-header__actions">
				<?php if ($header_cta) : ?>
					<div class="site-header__cta"><?php massitpro_render_button($header_cta); ?></div>
				<?php endif; ?>
				<button class="site-mobile-toggle" type="button" data-mobile-toggle aria-expanded="false" aria-controls="site-mobile-panel">
					<span class="site-mobile-toggle__icon site-mobile-toggle__icon--menu"><?php echo massitpro_svg_icon('menu'); ?></span>
					<span class="site-mobile-toggle__icon site-mobile-toggle__icon--close"><?php echo massitpro_svg_icon('close'); ?></span>
					<span class="screen-reader-text"><?php esc_html_e('Toggle navigation', 'massitpro'); ?></span>
				</button>
			</div>
		</div>

		<div class="site-mobile-panel" id="site-mobile-panel" data-mobile-panel hidden>
			<div class="section-padding site-shell site-mobile-panel__inner">
				<nav class="site-mobile-nav" aria-label="<?php esc_attr_e('Mobile navigation', 'massitpro'); ?>">
					<?php
					wp_nav_menu(
						[
							'theme_location' => 'primary',
							'container'      => false,
							'menu_class'     => 'site-menu site-menu--mobile',
							'fallback_cb'    => 'massitpro_primary_menu_fallback',
						]
						);
					?>
				</nav>
				<?php if ($header_cta) : ?>
					<div class="site-mobile-panel__cta">
						<?php
						massitpro_render_button(
							array_merge(
								$header_cta,
								[
									'variant' => 'action',
									'size'    => 'lg',
									'class'   => 'is-full',
								]
							)
						);
						?>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</header>
	<main class="site-main">
