<?php
/**
 * Site footer — matches the finalized Learning Net preview exactly.
 *
 * @package Learning_Net
 */
?>
</main>

<footer>
	<div class="wrap">
		<div class="footer-top">
			<div>
				<a class="footer-brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php esc_attr_e( 'The Learning Net home', 'learning-net' ); ?>">
					<img class="logo-light" src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/logo-color.png' ); ?>" alt="<?php esc_attr_e( 'The Learning Net', 'learning-net' ); ?>">
					<img class="logo-dark" src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/logo-white.png' ); ?>" alt="">
				</a>
				<h3><?php esc_html_e( 'Online learning support', 'learning-net' ); ?></h3>
				<p><?php esc_html_e( 'Personalised tuition following Cambridge International, AQA and Edexcel syllabi, with support from Key Stage 2 through IGCSE and A-Level.', 'learning-net' ); ?></p>
			</div>
			<div>
				<h3><?php esc_html_e( 'Programmes', 'learning-net' ); ?></h3>
				<a href="<?php echo esc_url( home_url( '/homeschooling/' ) ); ?>"><?php esc_html_e( 'Homeschooling', 'learning-net' ); ?></a>
				<a href="<?php echo esc_url( home_url( '/one-to-one/' ) ); ?>"><?php esc_html_e( '1-to-1 tuition', 'learning-net' ); ?></a>
				<a href="<?php echo esc_url( home_url( '/gcse-igcse/' ) ); ?>"><?php esc_html_e( 'GCSE & IGCSE', 'learning-net' ); ?></a>
				<a href="<?php echo esc_url( home_url( '/exam-prep/' ) ); ?>"><?php esc_html_e( 'Exam preparation', 'learning-net' ); ?></a>
			</div>
			<div>
				<h3><?php esc_html_e( 'Useful links & policies', 'learning-net' ); ?></h3>
				<a href="<?php echo esc_url( home_url( '/copyright/' ) ); ?>"><?php esc_html_e( 'Copyright Information', 'learning-net' ); ?></a>
				<a href="<?php echo esc_url( home_url( '/disclaimer/' ) ); ?>"><?php esc_html_e( 'Disclaimer', 'learning-net' ); ?></a>
				<a href="<?php echo esc_url( home_url( '/terms-of-service/' ) ); ?>"><?php esc_html_e( 'Terms of Services', 'learning-net' ); ?></a>
				<a href="<?php echo esc_url( home_url( '/data-protection/' ) ); ?>"><?php esc_html_e( 'Data Protection Policy', 'learning-net' ); ?></a>
				<a href="<?php echo esc_url( home_url( '/safeguarding/' ) ); ?>"><?php esc_html_e( 'Safeguarding', 'learning-net' ); ?></a>
			</div>
			<div>
				<h3><?php esc_html_e( 'Contact', 'learning-net' ); ?></h3>
				<p><?php esc_html_e( 'Covering : UK, UAE, Pakistan', 'learning-net' ); ?></p>
				<a href="mailto:info@thelearningnet.org"><?php esc_html_e( 'Email : info@thelearningnet.org', 'learning-net' ); ?></a>
				<a href="tel:+442034886786"><?php esc_html_e( 'Phone: (+44) 203 4886 786', 'learning-net' ); ?></a>
				<a href="https://www.instagram.com/thelearningnet/" target="_blank" rel="noopener"><?php esc_html_e( 'Instagram', 'learning-net' ); ?></a>
				<a href="https://web.facebook.com/thelearningnetonline" target="_blank" rel="noopener"><?php esc_html_e( 'Facebook', 'learning-net' ); ?></a>
			</div>
		</div>
		<div class="footer-row">
			<span>&copy; 2026 <?php esc_html_e( 'The Learning Net', 'learning-net' ); ?></span>
		</div>
	</div>
</footer>

<div class="mobile-bar" aria-hidden="true"></div>
<div class="mobile-cta"><a class="btn" href="<?php echo esc_url( home_url( '/registration/' ) ); ?>"><?php esc_html_e( 'Book your first paid lesson', 'learning-net' ); ?></a></div>

<div class="cookie-banner" id="cookie-banner" role="dialog" aria-live="polite" aria-label="<?php esc_attr_e( 'Cookie choices', 'learning-net' ); ?>">
	<p><?php esc_html_e( 'We use cookies to improve your experience. Choose whether to allow non-essential cookies.', 'learning-net' ); ?> <a href="<?php echo esc_url( home_url( '/cookies/' ) ); ?>"><?php esc_html_e( 'Cookie Policy', 'learning-net' ); ?></a></p>
	<div class="cookie-btns">
		<button type="button" class="cookie-reject" data-cookie-choice="reject"><?php esc_html_e( 'Reject non-essential', 'learning-net' ); ?></button>
		<button type="button" class="cookie-accept" data-cookie-choice="accept"><?php esc_html_e( 'Accept all', 'learning-net' ); ?></button>
	</div>
</div>

<?php wp_footer(); ?>
</body>
</html>
