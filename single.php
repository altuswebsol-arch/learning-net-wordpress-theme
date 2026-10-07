<?php
/**
 * Single post template — matches the finalized Learning Net preview articlePage:
 * page-hero (Study guide kicker, Published date), article + related-pages side nav,
 * subject CTA band.
 *
 * @package Learning_Net
 */
get_header();
while ( have_posts() ) :
	the_post();
	?>
	<section class="page-hero">
		<div class="wrap page-hero-grid">
			<div>
				<div class="breadcrumbs">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'learning-net' ); ?></a>
					<span aria-hidden="true">/</span>
					<span><?php esc_html_e( 'Study guide', 'learning-net' ); ?></span>
				</div>
				<p class="eyebrow"><?php esc_html_e( 'Study guide', 'learning-net' ); ?></p>
				<h1><?php the_title(); ?></h1>
				<?php if ( has_excerpt() ) : ?>
					<p class="lead"><?php echo esc_html( get_the_excerpt() ); ?></p>
				<?php endif; ?>
				<p class="article-meta"><?php echo esc_html( sprintf( __( 'Published %s', 'learning-net' ), get_the_date( 'j F Y' ) ) ); ?></p>
			</div>
		</div>
	</section>

	<section class="page-body reveal">
		<div class="wrap content-grid">
			<article class="article">
				<?php the_content(); ?>
			</article>
			<aside class="side-nav" aria-label="<?php esc_attr_e( 'Related pages', 'learning-net' ); ?>">
				<h3><?php esc_html_e( 'Related pages', 'learning-net' ); ?></h3>
				<a href="<?php echo esc_url( home_url( '/registration/' ) ); ?>"><?php esc_html_e( 'Registration', 'learning-net' ); ?></a>
				<a href="<?php echo esc_url( home_url( '/crash-course/' ) ); ?>"><?php esc_html_e( '12-week crash course', 'learning-net' ); ?></a>
				<a href="<?php echo esc_url( home_url( '/safeguarding/' ) ); ?>"><?php esc_html_e( 'Safeguarding', 'learning-net' ); ?></a>
				<a href="<?php echo esc_url( home_url( '/how-it-works/' ) ); ?>"><?php esc_html_e( 'How it works', 'learning-net' ); ?></a>
				<a href="<?php echo esc_url( home_url( '/faq/' ) ); ?>"><?php esc_html_e( 'FAQs', 'learning-net' ); ?></a>
				<a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"><?php esc_html_e( 'Contact us', 'learning-net' ); ?></a>
			</aside>
		</div>
	</section>

	<section class="cta">
		<div class="wrap cta-box">
			<div class="cta-copy">
				<p class="eyebrow"><?php esc_html_e( 'Next step', 'learning-net' ); ?></p>
				<h2><?php esc_html_e( 'Need support with this subject?', 'learning-net' ); ?></h2>
				<p><?php esc_html_e( 'A tutor can help turn these principles into a focused plan for the learner.', 'learning-net' ); ?></p>
			</div>
			<div class="contact-row">
				<a class="contact-link" href="mailto:info@thelearningnet.org">
					<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="m4 7 8 6 8-6"/></svg>
					<span>info@thelearningnet.org</span>
				</a>
				<a class="contact-link" href="tel:+442034886786">
					<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M7.2 3.5H4.8c-.8 0-1.5.7-1.4 1.5.8 8.3 7.3 14.8 15.6 15.6.8.1 1.5-.6 1.5-1.4v-2.4c0-.6-.4-1.2-1-1.4l-3-1c-.5-.2-1.1 0-1.4.4l-1.2 1.5a15.2 15.2 0 0 1-6.2-6.2l1.5-1.2c-.4-.3-.6-.9-.4-1.4l-1-3c-.2-.6-.8-1-1.4-1z"/></svg>
					<span>(+44) 203 4886 786</span>
				</a>
			</div>
		</div>
	</section>
	<?php
endwhile;
get_footer();
