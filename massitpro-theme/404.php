<?php
/**
 * 404 template.
 *
 * @package MassITPro
 */

get_header();
?>
<section class="not-found-screen">
	<div class="section-padding site-shell">
		<div class="not-found-screen__inner" data-reveal>
			<h1>404</h1>
			<p>Oops! Page not found</p>
			<a href="<?php echo esc_url(home_url('/')); ?>">Return to Home</a>
		</div>
	</div>
</section>
<?php
get_footer();
