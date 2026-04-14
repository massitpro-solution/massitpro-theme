<?php
/**
 * Reusable theme logo partial.
 *
 * @package MassITPro
 */

if (! defined('ABSPATH')) {
	exit;
}

$args = wp_parse_args(
	$args ?? [],
	[
		'context' => 'light',
		'mark'    => false,
		'class'   => '',
	]
);

$asset   = massitpro_get_logo_asset($args['context'], (bool) $args['mark']);
$classes = trim('theme-logo ' . $args['class'] . ($args['mark'] ? ' theme-logo--mark-only' : ''));

if ($asset) :
	?>
	<span class="<?php echo esc_attr($classes); ?>">
		<img src="<?php echo esc_url($asset['url']); ?>" alt="<?php echo esc_attr(get_bloginfo('name')); ?>">
	</span>
	<?php
	return;
endif;
?>
<span class="<?php echo esc_attr($classes . ' theme-logo--fallback'); ?>">
	<span class="theme-logo__mark">M</span>
	<?php if (! $args['mark']) : ?>
		<span class="theme-logo__text">
			<strong class="theme-logo__title">Mass IT Pro</strong>
			<small class="theme-logo__subtitle">Solution</small>
		</span>
	<?php endif; ?>
</span>
