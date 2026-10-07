<?php
/**
 * One-time content sync — updates all Learning Net pages and posts
 * with the finalized preview content.
 *
 * Available under Tools > Learning Net Content Sync (administrators only).
 *
 * @package Learning_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once __DIR__ . '/final-content.php';

/**
 * Register the sync admin page.
 */
function ln_content_sync_menu() {
	add_management_page(
		__( 'Learning Net Content Sync', 'learning-net' ),
		__( 'LN Content Sync', 'learning-net' ),
		'manage_options',
		'ln-content-sync',
		'ln_content_sync_page'
	);
}
add_action( 'admin_menu', 'ln_content_sync_menu' );

/**
 * Render the sync admin page.
 */
function ln_content_sync_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'You do not have permission to access this page.', 'learning-net' ) );
	}

	$data = ln_final_content_data();
	$done = false;
	$results = array();

	if ( isset( $_POST['ln_sync_nonce'] ) && wp_verify_nonce( $_POST['ln_sync_nonce'], 'ln_content_sync' ) ) {
		$results = ln_run_content_sync( $data );
		$done = true;
	}

	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Learning Net Content Sync', 'learning-net' ); ?></h1>
		<p><?php esc_html_e( 'Updates all pages and blog posts with the finalized preview content (titles, body copy and publish dates). Existing pages are updated in place; missing pages are created.', 'learning-net' ); ?></p>
		<p><strong><?php echo esc_html( count( $data['pages'] ) ); ?></strong> <?php esc_html_e( 'pages', 'learning-net' ); ?> ·
		<strong><?php echo esc_html( count( $data['posts'] ) ); ?></strong> <?php esc_html_e( 'posts', 'learning-net' ); ?></p>

		<?php if ( $done ) : ?>
			<div class="notice notice-success"><p><strong><?php esc_html_e( 'Sync complete.', 'learning-net' ); ?></strong></p></div>
			<?php if ( ! empty( $results ) ) : ?>
				<ul>
				<?php foreach ( $results as $r ) : ?>
					<li><?php echo esc_html( $r ); ?></li>
				<?php endforeach; ?>
				</ul>
			<?php endif; ?>
		<?php else : ?>
			<form method="post">
				<?php wp_nonce_field( 'ln_content_sync', 'ln_sync_nonce' ); ?>
				<p><button type="submit" class="button button-primary button-large"><?php esc_html_e( 'Sync finalized content now', 'learning-net' ); ?></button></p>
			</form>
		<?php endif; ?>
	</div>
	<?php
}

/**
 * Find the Contact Form 7 form ID (the first published wpcf7_contact_form).
 *
 * @return int|false
 */
function ln_find_cf7_id() {
	$forms = get_posts(
		array(
			'post_type'      => 'wpcf7_contact_form',
			'post_status'    => 'publish',
			'posts_per_page' => 1,
			'orderby'        => 'ID',
			'order'          => 'ASC',
			'fields'         => 'ids',
		)
	);
	return ! empty( $forms ) ? (int) $forms[0] : false;
}

/**
 * Run the content sync. Returns an array of result messages.
 *
 * @param array $data Content data from ln_final_content_data().
 * @return array
 */
function ln_run_content_sync( $data ) {
	$results = array();

	// Resolve the Contact Form 7 form ID dynamically.
	$cf7_id = ln_find_cf7_id();
	if ( $cf7_id ) {
		$results[] = sprintf( __( 'Contact form resolved: ID %d', 'learning-net' ), $cf7_id );
	} else {
		$results[] = __( 'WARNING: no Contact Form 7 form found; contact page form shortcode left as placeholder', 'learning-net' );
	}

	// Pages.
	foreach ( $data['pages'] as $slug => $page ) {
		$content = $page['content'];
		if ( $cf7_id ) {
			$content = str_replace( '[contact-form-7 id="CONTACT_FORM_ID"]', '[contact-form-7 id="' . (int) $cf7_id . '"]', $content );
		}
		$existing = get_page_by_path( $slug, OBJECT, 'page' );
		$postarr = array(
			'post_title'   => wp_strip_all_tags( $page['title'] ),
			'post_content' => $content,
			'post_excerpt' => isset( $page['lead'] ) ? $page['lead'] : '',
			'post_status'  => 'publish',
			'post_type'    => 'page',
		);
		if ( $existing ) {
			$postarr['ID'] = $existing->ID;
			$pid = wp_update_post( $postarr, true );
			if ( is_wp_error( $pid ) ) {
				$results[] = sprintf( __( 'Page %s: ERROR %s', 'learning-net' ), $slug, $pid->get_error_message() );
			} else {
				$results[] = sprintf( __( 'Page %s: updated', 'learning-net' ), $slug );
			}
		} else {
			$postarr['post_name'] = $slug;
			$pid = wp_insert_post( $postarr, true );
			if ( is_wp_error( $pid ) ) {
				$results[] = sprintf( __( 'Page %s: ERROR %s', 'learning-net' ), $slug, $pid->get_error_message() );
			} else {
				$results[] = sprintf( __( 'Page %s: created', 'learning-net' ), $slug );
			}
		}
	}

	// Posts.
	foreach ( $data['posts'] as $slug => $post ) {
		$existing = get_page_by_path( $slug, OBJECT, 'post' );
		if ( ! $existing ) {
			$results[] = sprintf( __( 'Post %s: not found, skipped', 'learning-net' ), $slug );
			continue;
		}
		// Parse "12 November 2025" into Y-m-d H:i:s.
		$date_sql = '';
		$ts = strtotime( $post['date'] );
		if ( $ts ) {
			$date_sql = date( 'Y-m-d H:i:s', $ts );
		}
		$postarr = array(
			'ID'           => $existing->ID,
			'post_title'   => wp_strip_all_tags( $post['title'] ),
			'post_content' => $post['content'],
			'post_excerpt' => isset( $post['lead'] ) ? $post['lead'] : '',
		);
		if ( $date_sql ) {
			$postarr['post_date'] = $date_sql;
			$postarr['post_date_gmt'] = get_gmt_from_date( $date_sql );
		}
		$pid = wp_update_post( $postarr, true );
		if ( is_wp_error( $pid ) ) {
			$results[] = sprintf( __( 'Post %s: ERROR %s', 'learning-net' ), $slug, $pid->get_error_message() );
		} else {
			$results[] = sprintf( __( 'Post %s: updated', 'learning-net' ), $slug );
		}
	}

	return $results;
}
