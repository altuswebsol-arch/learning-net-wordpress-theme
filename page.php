<?php
/**
 * Default page template — matches the finalized Learning Net preview:
 * page-hero, article + related-pages side nav, Next-step CTA band.
 *
 * @package Learning_Net
 */

/**
 * Eyebrow kicker per page slug, from the finalized preview.
 */
function ln_page_kicker( $slug ) {
	$kickers = array(
		'programmes'         => 'Programmes',
		'resources'          => 'Resources',
		'homeschooling'      => 'Homeschooling support',
		'one-to-one'         => 'Personalised tutoring',
		'crash-course'       => 'IGCSE & A-Level crash course',
		'crash-registration' => 'IGCSE & A-Level registration',
		'how-it-works'       => 'How it works',
		'about'              => 'About The Learning Net',
		'gcse-igcse'         => 'GCSE & IGCSE tuition',
		'exam-prep'          => 'Exam preparation',
		'registration'       => 'Registration',
		'contact'            => 'Contact The Learning Net',
		'faq'                => 'Parent information',
		'classroom-guide'    => 'Student guide',
		'useful-links'       => 'Useful links',
		'industry-links'     => 'Industry links',
		'enquiry-map'        => 'Enquiry mind-map',
		'unsubscribe'        => 'Unsubscribe',
		'ks2'                => 'Ages 7–11 · Years 3–6',
		'ks3'                => 'Ages 11–14 · Years 7–9',
		'safeguarding'       => 'Safety first',
		'student-login'      => 'Student account',
		'blog'               => 'Blogs',
		'cookies'            => 'Legal information',
		'privacy'            => 'Legal information',
		'data-protection'    => 'Legal information',
		'terms'              => 'Legal information',
		'terms-of-service'   => 'Legal information',
		'disclaimer'         => 'Legal information',
		'copyright'          => 'Legal information',
	);
	return isset( $kickers[ $slug ] ) ? $kickers[ $slug ] : '';
}

get_header();
while ( have_posts() ) :
	the_post();
	$slug   = get_post_field( 'post_name', get_the_ID() );
	$kicker = ln_page_kicker( $slug );
	?>
	<section class="page-hero">
		<div class="wrap page-hero-grid">
			<div>
				<div class="breadcrumbs">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'learning-net' ); ?></a>
					<span aria-hidden="true">/</span>
					<span><?php echo $kicker ? esc_html( $kicker ) : the_title( '', '', false ); ?></span>
				</div>
				<?php if ( $kicker ) : ?>
					<p class="eyebrow"><?php echo esc_html( $kicker ); ?></p>
				<?php endif; ?>
				<h1><?php the_title(); ?></h1>
				<?php if ( has_excerpt() ) : ?>
					<p class="lead"><?php echo esc_html( get_the_excerpt() ); ?></p>
				<?php endif; ?>
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

	<?php
	// Page-specific CTA copy from the finalized preview. Contact has no CTA.
	$cta_map = array(
		'about'          => array( 'Start a conversation with our team.', 'Tell us about the learner, the subjects and the outcome you are working towards.' ),
		'crash-course'   => array( 'Reserve a place on the next intake.', 'Tell us the subjects, exam board and examination date so we can confirm the right starting point.' ),
		'enquiry-map'    => array( 'Start your enquiry.', 'The team can help you choose the most suitable route.' ),
		'exam-prep'      => array( 'Discuss private-candidate exam entry.', 'Send the qualification, board, subjects and preferred exam series.' ),
		'faq'            => array( 'Ask the team directly.', 'We can answer questions about subjects, tutor availability, safeguarding and enrolment.' ),
		'gcse-igcse'     => array( 'Build an exam-focused tuition plan.', 'Send the subjects, exam board, current working level and target examination series.' ),
		'homeschooling'  => array( 'Plan a personalised homeschooling programme.', 'Share your child’s age, subjects, curriculum and preferred timetable.' ),
		'how-it-works'   => array( 'Ask how to get started.', 'Send the learner’s level, subjects and location.' ),
		'ks2'            => array( 'Give your child stronger foundations.', 'Tell us the year group, subjects and areas where support is needed.' ),
		'ks3'            => array( 'Plan the next stage.', 'Share the learner’s year group, subjects and current challenges.' ),
		'one-to-one'     => array( 'Find the right tutor match.', 'Tell us the learner’s stage, subjects, curriculum and goals.' ),
		'programmes'     => array( 'Not sure which programme fits?', 'Tell us the learner’s year group, subjects and goals, and the team can guide you.' ),
		'resources'      => array( 'Need help finding the right material?', 'Share the qualification, subject and exam board so the team can point you in the right direction.' ),
		'useful-links'   => array( 'Need help choosing resources?', 'Tell the tutor the exact board and qualification code so the right materials can be selected.' ),
	);
	$cta = isset( $cta_map[ $slug ] ) ? $cta_map[ $slug ] : array( 'Ready to discuss the next step?', 'Ask about online tutoring, subjects, safeguarding or enrolment.' );
	if ( 'contact' !== $slug ) :
		?>
	<section class="cta">
		<div class="wrap cta-box">
			<div class="cta-copy">
				<p class="eyebrow"><?php esc_html_e( 'Next step', 'learning-net' ); ?></p>
				<h2><?php echo esc_html( $cta[0] ); ?></h2>
				<p><?php echo esc_html( $cta[1] ); ?></p>
			</div>
			<div class="contact-row">
				<a class="contact-link" href="mailto:info@thelearningnet.org">
					<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="m4 7 8 6 8-6"/></svg>
					<span>info@thelearningnet.org</span>
				</a>
				<a class="contact-link" href="tel:+442034886786">
					<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M7.2 3.5H4.8c-.8 0-1.5.7-1.4 1.5.8 8.3 7.3 14.8 15.6 15.6.8.1 1.5-.6 1.5-1.4v-2.4c0-.6-.4-1.2-1-1.4l-3-1c-.5-.2-1.1 0-1.4.4l-1.2 1.5a15.2 15.2 0 0 1-6.2-6.2l1.5-1.2c.4-.3.6-.9.4-1.4l-1-3c-.2-.6-.8-1-1.4-1z"/></svg>
					<span>(+44) 203 4886 786</span>
				</a>
			</div>
		</div>
	</section>
	<?php endif; ?>
	<?php
endwhile;
get_footer();
