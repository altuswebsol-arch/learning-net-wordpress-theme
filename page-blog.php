<?php
/**
 * Template Name: Blog Index
 * Blog article index — matches the finalized Learning Net preview.
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
				<span><?php the_title(); ?></span>
			</div>
			<p class="eyebrow"><?php esc_html_e( 'Learning Net articles', 'learning-net' ); ?></p>
			<h1><?php esc_html_e( 'Study guides and exam advice', 'learning-net' ); ?></h1>
			<p class="lead"><?php esc_html_e( 'Practical, student-friendly guidance for IGCSE revision, A-Level Maths, past papers and one-to-one learning.', 'learning-net' ); ?></p>
		</div>
	</div>
</section>

<section class="page-body">
	<div class="wrap">
		<?php
		$blog = new WP_Query(
			array(
				'post_type'      => 'post',
				'posts_per_page' => 12,
				'paged'          => max( 1, get_query_var( 'paged' ) ),
			)
		);
		if ( $blog->have_posts() ) :
			$imgs = array( 'teen-studying.webp', 'maths-study.jpg', 'school-study.webp' );
			$i = 0;
			?>
			<section>
				<h2><?php esc_html_e( 'All articles', 'learning-net' ); ?></h2>
				<div class="grid">
				<?php
				while ( $blog->have_posts() ) :
					$blog->the_post();
					$img = $imgs[ $i % count( $imgs ) ];
					$i++;
					?>
					<article class="story-card">
						<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/' . $img ); ?>" alt="<?php esc_attr_e( 'Student studying for exams and building stronger revision habits', 'learning-net' ); ?>">
						<div>
							<h3><?php the_title(); ?></h3>
							<p><?php esc_html_e( 'Read the full Learning Net guide and turn the key ideas into a practical study plan.', 'learning-net' ); ?></p>
							<div class="btn-row">
								<a class="btn secondary" href="<?php the_permalink(); ?>"><?php esc_html_e( 'Read article', 'learning-net' ); ?></a>
							</div>
						</div>
					</article>
				<?php endwhile; ?>
				</div>
			</section>
			<?php
			the_posts_pagination(
				array(
					'total'   => $blog->max_num_pages,
					'current' => max( 1, get_query_var( 'paged' ) ),
				)
			);
			wp_reset_postdata();
			?>
		<?php else : ?>
			<p><?php esc_html_e( 'No articles yet.', 'learning-net' ); ?></p>
		<?php endif; ?>
	</div>
</section>
<?php
get_footer();
