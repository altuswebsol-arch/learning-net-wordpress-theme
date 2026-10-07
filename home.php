<?php
/**
 * Blog index template — matches the finalized Learning Net preview.
 *
 * @package Learning_Net
 */
get_header();
?>
<section class="page-hero">
	<div class="wrap page-hero-grid">
		<div>
			<div class="breadcrumbs">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'learning-net' ); ?></a>
				<span aria-hidden="true">/</span>
				<span><?php esc_html_e( 'Blogs', 'learning-net' ); ?></span>
			</div>
			<p class="eyebrow"><?php esc_html_e( 'Learning Net articles', 'learning-net' ); ?></p>
			<h1><?php esc_html_e( 'Study guides and exam advice', 'learning-net' ); ?></h1>
			<p class="lead"><?php esc_html_e( 'Practical guidance for revision, subject preparation and choosing the right learning format.', 'learning-net' ); ?></p>
		</div>
	</div>
</section>

<section class="page-body">
	<div class="wrap">
		<?php if ( have_posts() ) : ?>
			<div class="grid">
				<?php
				while ( have_posts() ) :
					the_post();
					?>
					<article class="card">
						<h3><?php the_title(); ?></h3>
						<p><?php esc_html_e( 'Read the Learning Net guide and put the key ideas into practice.', 'learning-net' ); ?></p>
						<div class="btn-row">
							<a class="btn secondary" href="<?php the_permalink(); ?>"><?php esc_html_e( 'Read article', 'learning-net' ); ?></a>
						</div>
					</article>
				<?php endwhile; ?>
			</div>
			<?php the_posts_pagination(); ?>
		<?php else : ?>
			<p><?php esc_html_e( 'No articles yet.', 'learning-net' ); ?></p>
		<?php endif; ?>
	</div>
</section>
<?php
get_footer();
