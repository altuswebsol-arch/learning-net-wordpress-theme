<?php
/**
 * 404 template.
 *
 * @package Learning_Net
 */
get_header();
?>
<div class="section">
	<div class="container" style="text-align:center;max-width:640px">
		<h1><?php esc_html_e( 'Page not found', 'learning-net' ); ?></h1>
		<p class="lead" style="margin:0 auto 1.6rem"><?php esc_html_e( 'The page you are looking for may have moved. Try the homepage or get in touch and we will point you the right way.', 'learning-net' ); ?></p>
		<div class="cta-row" style="justify-content:center">
			<a class="btn btn-gold" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Back to home', 'learning-net' ); ?></a>
			<a class="btn btn-outline" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"><?php esc_html_e( 'Contact us', 'learning-net' ); ?></a>
		</div>
	</div>
</div>
<?php get_footer(); ?>
