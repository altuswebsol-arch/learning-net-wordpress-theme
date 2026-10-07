<?php
/**
 * Fallback index template.
 *
 * @package Learning_Net
 */
get_header();
?>
<div class="section">
	<div class="container">
		<?php
		if ( have_posts() ) :
			while ( have_posts() ) :
				the_post();
				?>
				<article>
					<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
					<?php the_excerpt(); ?>
				</article>
				<?php
			endwhile;
		else :
			?>
			<p><?php esc_html_e( 'Nothing found.', 'learning-net' ); ?></p>
		<?php endif; ?>
	</div>
</div>
<?php get_footer(); ?>
