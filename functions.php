<?php
/**
 * Learning Net theme — functions and setup.
 *
 * @package Learning_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'LN_THEME_VERSION', '1.0.2' );
define( 'LN_THEME_DIR', get_template_directory() );
define( 'LN_THEME_URI', get_template_directory_uri() );

// Finalized content data + one-time content sync tool.
require_once LN_THEME_DIR . '/inc/final-content.php';
require_once LN_THEME_DIR . '/inc/content-sync.php';

/**
 * Theme setup: title tag, thumbnails, HTML5, logo, nav menus.
 */
function ln_theme_setup() {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support(
		'html5',
		array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' )
	);
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 64,
			'width'       => 240,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);

	register_nav_menus(
		array(
			'primary' => __( 'Primary Menu', 'learning-net' ),
			'footer'  => __( 'Footer Menu', 'learning-net' ),
		)
	);
}
add_action( 'after_setup_theme', 'ln_theme_setup' );

/**
 * Enqueue styles and scripts.
 */
function ln_theme_assets() {
	wp_enqueue_style(
		'learning-net-fonts',
		'https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@600;700;800&display=swap',
		array(),
		null
	);

	wp_enqueue_style(
		'learning-net-style',
		get_stylesheet_uri(),
		array( 'learning-net-fonts' ),
		LN_THEME_VERSION
	);

	wp_enqueue_script(
		'learning-net-main',
		LN_THEME_URI . '/assets/js/main.js',
		array(),
		LN_THEME_VERSION,
		true
	);
}
add_action( 'wp_enqueue_scripts', 'ln_theme_assets' );

/**
 * Nav walker that outputs the finalized preview's exact nav markup:
 * bare <a class="nav-link"> anchors (no <ul>/<li>), crash-link passthrough,
 * and aria-current="page" on the current item.
 */
class LN_Preview_Nav_Walker extends Walker_Nav_Menu {
	public function start_lvl( &$output, $depth = 0, $args = null ) {}
	public function end_lvl( &$output, $depth = 0, $args = null ) {}
	public function end_el( &$output, $item, $depth = 0, $args = null ) {}

	public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
		$classes = array( 'nav-link' );
		if ( in_array( 'crash-link', (array) $item->classes, true ) ) {
			$classes[] = 'crash-link';
		}
		$aria = '';
		if ( ! empty( $item->current ) ) {
			$aria = ' aria-current="page"';
		}
		$output .= sprintf(
			'<a class="%s" href="%s"%s>%s</a>',
			esc_attr( implode( ' ', $classes ) ),
			esc_url( $item->url ),
			$aria,
			esc_html( $item->title )
		);
	}
}

/**
 * Fallback primary menu (used before a menu is assigned): the 8 preview links
 * in the finalized order, with the crash-course standout class.
 */
function learning_net_primary_fallback_links() {
	$links = array(
		array( '/', 'Home', false ),
		array( '/programmes/', 'Programmes', false ),
		array( '/resources/', 'Resources', false ),
		array( '/crash-course/', '12-Week Crash Course', true ),
		array( '/how-it-works/', 'How it works', false ),
		array( '/about/', 'About us', false ),
		array( '/blog/', 'Blogs', false ),
		array( '/contact/', 'Contact', false ),
	);
	foreach ( $links as $link ) {
		printf(
			'<a class="nav-link%s" href="%s">%s</a>',
			$link[2] ? ' crash-link' : '',
			esc_url( home_url( $link[0] ) ),
			esc_html( $link[1] )
		);
	}
}

/**
 * -------------------------------------------------------------------------
 * Theme forms: registration, student account help, safeguarding concern,
 * 12-week crash course booking, unsubscribe.
 * Rendered with [ln_form id="..."] and matched exactly to the finalized
 * Learning Net preview (field order, labels, options, buttons, notes).
 * The Contact page uses Contact Form 7 (form ID 14) instead.
 * Submissions are emailed to info@thelearningnet.org. Protected by a nonce
 * and a honeypot field; all values are sanitised before use.
 * -------------------------------------------------------------------------
 */

function ln_form_fields( $id ) {
	switch ( $id ) {
		case 'registration':
			return array(
				array( 'name' => 'Student name', 'label' => 'Student name', 'type' => 'text', 'required' => true ),
				array( 'name' => 'Parent or guardian name', 'label' => 'Parent or guardian name', 'type' => 'text', 'required' => true ),
				array( 'name' => 'Parent email', 'label' => 'Parent email', 'type' => 'email', 'required' => true ),
				array( 'name' => 'Parent phone', 'label' => 'Parent phone', 'type' => 'tel', 'required' => true ),
				array( 'name' => 'Country', 'label' => 'Country', 'type' => 'select', 'required' => true,
					'options' => array( 'United Kingdom', 'United Arab Emirates', 'Pakistan', 'Other' ) ),
				array( 'name' => 'Study plan', 'label' => 'Programme', 'type' => 'select', 'required' => true,
					'options' => array( 'IGCSE 1-to-1 tuition', 'Homeschooling', 'Key Stage tuition', 'A-Level tuition', 'Crash course' ) ),
				array( 'name' => 'Payment plan', 'label' => 'Prepaid payment plan', 'type' => 'select', 'required' => true,
					'options' => array( 'Weekly — 4 lessons', 'Biweekly — 8 lessons', 'Monthly — 16 lessons' ) ),
				array( 'name' => 'Preferred start date', 'label' => 'Preferred start date', 'type' => 'date', 'required' => true ),
				array( 'name' => 'Preferred lesson times', 'label' => 'Preferred lesson days and times', 'type' => 'text', 'required' => true ),
				array( 'name' => 'Level of study', 'label' => 'Level of study', 'type' => 'radio', 'required' => true, 'full' => true,
					'options' => array( 'Key Stage 2', 'Key Stage 3', 'IGCSE', 'A-Level' ) ),
				array( 'name' => 'Subjects', 'label' => 'Subjects', 'type' => 'checkbox', 'required' => true, 'full' => true,
					'options' => array( 'English', 'Mathematics', 'Science', 'History', 'Geography', 'Business Studies', 'Economics', 'Computer Studies', 'Psychology', 'Sociology' ) ),
				array( 'name' => 'Safeguarding or learning needs', 'label' => 'Is there anything about your child\'s welfare, learning needs or safety we should know about?', 'type' => 'textarea', 'full' => true ),
				array( 'name' => 'Additional information', 'label' => 'Anything else we should know', 'type' => 'textarea', 'full' => true ),
			);
		case 'account-help':
			return array(
				array( 'name' => 'Student name', 'label' => 'Student name', 'type' => 'text', 'required' => true ),
				array( 'name' => 'Parent email', 'label' => 'Parent or guardian email', 'type' => 'email', 'required' => true ),
				array( 'name' => 'Account issue', 'label' => 'What do you need help with?', 'type' => 'select', 'required' => true,
					'options' => array( 'I cannot access my account', 'I forgot which email to use', 'My class is not showing', 'My lesson link is missing', 'Something else' ) ),
				array( 'name' => 'Message', 'label' => 'Tell us what happened', 'type' => 'textarea', 'required' => true, 'full' => true ),
			);
		case 'safeguarding':
			return array(
				array( 'name' => 'Your name', 'label' => 'Your name', 'type' => 'text', 'required' => true ),
				array( 'name' => 'Your email', 'label' => 'Your email', 'type' => 'email', 'required' => true ),
				array( 'name' => 'Your phone', 'label' => 'Phone number', 'type' => 'tel' ),
				array( 'name' => 'Relationship', 'label' => 'Your relationship to the learner', 'type' => 'select', 'required' => true,
					'options' => array( 'Parent or guardian', 'Student', 'Tutor or staff member', 'Other' ) ),
				array( 'name' => 'Student name', 'label' => 'Student name', 'type' => 'text' ),
				array( 'name' => 'Concern', 'label' => 'Describe the concern and any immediate risk', 'type' => 'textarea', 'required' => true, 'full' => true ),
			);
		case 'crash-registration':
			return array(
				array( 'name' => 'Student name', 'label' => 'Student name', 'type' => 'text', 'required' => true ),
				array( 'name' => 'Parent or guardian name', 'label' => 'Parent or guardian name', 'type' => 'text', 'required' => true ),
				array( 'name' => 'Parent email', 'label' => 'Parent email', 'type' => 'email', 'required' => true ),
				array( 'name' => 'Parent phone', 'label' => 'Parent phone', 'type' => 'tel', 'required' => true ),
				array( 'name' => 'Preferred start date', 'label' => 'Preferred start date', 'type' => 'date', 'required' => true ),
				array( 'name' => 'Payment plan', 'label' => 'Prepaid payment plan', 'type' => 'select', 'required' => true,
					'options' => array( 'Weekly — 4 lessons', 'Biweekly — 8 lessons', 'Monthly — 16 lessons' ) ),
				array( 'name' => 'Subjects', 'label' => 'Subjects', 'type' => 'checkbox', 'required' => true, 'full' => true,
					'options' => array( 'Mathematics', 'Science', 'English', 'Geography', 'Other subject' ) ),
				array( 'name' => 'Exam board', 'label' => 'Exam board', 'type' => 'select', 'required' => true,
					'options' => array( 'Cambridge', 'Edexcel', 'Oxford AQA' ) ),
				array( 'name' => 'Exam entry support', 'label' => 'Exam entry support required', 'type' => 'radio', 'required' => true,
					'options' => array( 'Yes', 'No' ) ),
				array( 'name' => 'Safeguarding or learning needs', 'label' => 'Is there anything about your child\'s welfare, learning needs or safety we should know about?', 'type' => 'textarea', 'full' => true ),
			);
		case 'unsubscribe':
			return array(
				array( 'name' => 'Email', 'label' => 'Email address to remove', 'type' => 'email', 'required' => true ),
				array( 'name' => 'Reason', 'label' => 'Reason for unsubscribing', 'type' => 'radio', 'required' => true, 'full' => true,
					'options' => array( 'Too many emails', 'Not relevant', 'Signed up by mistake', 'Other' ) ),
				array( 'name' => 'Phone', 'label' => 'Phone (optional)', 'type' => 'tel' ),
			);
	}
	return array();
}

function ln_form_meta( $id ) {
	$meta = array(
		'registration' => array(
			'subject' => 'First lesson booking',
			'button'  => 'Send booking',
		),
		'account-help' => array(
			'subject' => 'Student account help',
			'button'  => 'Send account help request',
		),
		'safeguarding' => array(
			'subject' => 'Confidential safeguarding concern',
			'button'  => 'Send safeguarding concern',
		),
		'crash-registration' => array(
			'subject' => '12-week crash course booking',
			'button'  => 'Send course booking',
		),
		'unsubscribe' => array(
			'subject' => 'Unsubscribe request',
			'button'  => 'Send unsubscribe',
		),
	);
	return isset( $meta[ $id ] ) ? $meta[ $id ] : null;
}

/**
 * [ln_form id="..."] shortcode. Renders the preview-exact form structure:
 * .email-form > .form-grid > .field(+full) with fieldset/legend + .choice-grid
 * for radio/checkbox groups, "Choose an option" placeholder for selects,
 * the info@thelearningnet.org form note, .form-status and a .btn submit.
 */
function ln_form_shortcode( $atts ) {
	$atts = shortcode_atts( array( 'id' => '' ), $atts, 'ln_form' );
	$id   = sanitize_key( $atts['id'] );
	$meta = ln_form_meta( $id );
	if ( ! $meta ) {
		return '';
	}

	$out = '';
	if ( isset( $_GET['ln_sent'] ) && '1' === $_GET['ln_sent'] && isset( $_GET['ln_form'] ) && $id === sanitize_key( $_GET['ln_form'] ) ) {
		$out .= '<div class="form-success" role="status">' . esc_html__( 'Thank you — your message has been sent. We will get back to you as soon as possible.', 'learning-net' ) . '</div>';
	}

	$out .= '<form class="email-form" method="post" action="' . esc_url( admin_url( 'admin-post.php' ) ) . '" data-subject="' . esc_attr( $meta['subject'] ) . '">';
	$out .= '<input type="hidden" name="action" value="ln_form_submit">';
	$out .= '<input type="hidden" name="ln_form_id" value="' . esc_attr( $id ) . '">';
	$out .= wp_nonce_field( 'ln_form_' . $id, 'ln_form_nonce', true, false );
	// Honeypot (visually hidden via CSS).
	$out .= '<div class="hp" aria-hidden="true"><label>' . esc_html__( 'Leave this field empty', 'learning-net' ) . '<input type="text" name="ln_website" value="" tabindex="-1" autocomplete="off"></label></div>';

	$out .= '<div class="form-grid">';
	foreach ( ln_form_fields( $id ) as $field ) {
		$name  = $field['name'];
		$fid   = 'f-' . sanitize_title( $name );
		$label = $field['label'] . ( ! empty( $field['required'] ) ? ' *' : '' );
		$full  = ! empty( $field['full'] ) ? ' full' : '';
		$out  .= '<div class="field' . $full . '">';
		if ( 'radio' === $field['type'] || 'checkbox' === $field['type'] ) {
			$fs_extra = ( 'checkbox' === $field['type'] && ! empty( $field['required'] ) ) ? ' data-require-choice="true"' : '';
			$out .= '<fieldset' . $fs_extra . '><legend>' . esc_html( $label ) . '</legend>';
			$out .= '<div class="choice-grid">';
			$input_name = 'checkbox' === $field['type'] ? $name . '[]' : $name;
			foreach ( $field['options'] as $opt ) {
				$req_attr = ( 'radio' === $field['type'] && ! empty( $field['required'] ) ) ? ' required' : '';
				$out .= '<label><input type="' . esc_attr( $field['type'] ) . '" name="' . esc_attr( $input_name ) . '" value="' . esc_attr( $opt ) . '"' . $req_attr . '> ' . esc_html( $opt ) . '</label>';
			}
			$out .= '</div></fieldset>';
		} else {
			$out .= '<label for="' . esc_attr( $fid ) . '">' . esc_html( $label ) . '</label>';
			$req_attr = ! empty( $field['required'] ) ? ' required' : '';
			if ( 'select' === $field['type'] ) {
				$out .= '<select id="' . esc_attr( $fid ) . '" name="' . esc_attr( $name ) . '"' . $req_attr . '>';
				$out .= '<option value="">' . esc_html__( 'Choose an option', 'learning-net' ) . '</option>';
				foreach ( $field['options'] as $opt ) {
					$out .= '<option>' . esc_html( $opt ) . '</option>';
				}
				$out .= '</select>';
			} elseif ( 'textarea' === $field['type'] ) {
				$out .= '<textarea id="' . esc_attr( $fid ) . '" name="' . esc_attr( $name ) . '"' . $req_attr . '></textarea>';
			} else {
				$out .= '<input id="' . esc_attr( $fid ) . '" name="' . esc_attr( $name ) . '" type="' . esc_attr( $field['type'] ) . '"' . $req_attr . '>';
			}
		}
		$out .= '</div>';
	}
	$out .= '</div>';
	$out .= '<p class="form-note">' . sprintf( __( 'Your details are sent directly to %s through The Learning Net website.', 'learning-net' ), '<strong>info@thelearningnet.org</strong>' ) . '</p>';
	$out .= '<div class="form-status" aria-live="polite"></div>';
	$out .= '<button class="btn" type="submit">' . esc_html( $meta['button'] ) . '</button>';
	$out .= '</form>';

	return $out;
}
add_shortcode( 'ln_form', 'ln_form_shortcode' );

/**
 * Handle [ln_form] submissions: validate, sanitise, email, redirect back.
 */
function ln_handle_form_submit() {
	$id   = isset( $_POST['ln_form_id'] ) ? sanitize_key( $_POST['ln_form_id'] ) : '';
	$meta = ln_form_meta( $id );
	if ( ! $meta ) {
		wp_die( esc_html__( 'Invalid form.', 'learning-net' ), 400 );
	}

	// Honeypot: silently "succeed" for bots.
	if ( ! empty( $_POST['ln_website'] ) ) {
		ln_form_redirect_back( $id );
	}

	if ( ! isset( $_POST['ln_form_nonce'] ) || ! wp_verify_nonce( $_POST['ln_form_nonce'], 'ln_form_' . $id ) ) {
		wp_die( esc_html__( 'Security check failed. Please go back and try again.', 'learning-net' ), 403 );
	}

	$fields = ln_form_fields( $id );
	$lines  = array();
	foreach ( $fields as $field ) {
		$name  = $field['name'];
		$label = $field['label'];
		$raw   = isset( $_POST[ $name ] ) ? $_POST[ $name ] : '';
		if ( 'checkbox' === $field['type'] ) {
			$raw = is_array( $raw ) ? $raw : array();
			if ( ! empty( $field['required'] ) && empty( $raw ) ) {
				wp_die( esc_html__( 'Please complete all required fields.', 'learning-net' ), 400 );
			}
			$values = array();
			foreach ( $raw as $v ) {
				$v = sanitize_text_field( wp_unslash( $v ) );
				if ( in_array( $v, $field['options'], true ) ) {
					$values[] = $v;
				}
			}
			$lines[] = $label . ': ' . implode( ', ', $values );
			continue;
		}
		if ( ! empty( $field['required'] ) && '' === trim( wp_unslash( (string) $raw ) ) ) {
			wp_die( esc_html__( 'Please complete all required fields.', 'learning-net' ), 400 );
		}
		if ( 'email' === $field['type'] && '' !== $raw && ! is_email( $raw ) ) {
			wp_die( esc_html__( 'Please enter a valid email address.', 'learning-net' ), 400 );
		}
		$value = sanitize_textarea_field( wp_unslash( (string) $raw ) );
		if ( ( 'select' === $field['type'] || 'radio' === $field['type'] ) && '' !== $value && ! in_array( $value, $field['options'], true ) ) {
			wp_die( esc_html__( 'Please complete all required fields.', 'learning-net' ), 400 );
		}
		$lines[] = $label . ': ' . $value;
	}

	$subject = '[The Learning Net website] ' . $meta['subject'];
	$body    = $meta['subject'] . "
" . str_repeat( '-', 40 ) . "
" . implode( "
", $lines ) . "
";
	$headers = array( 'Content-Type: text/plain; charset=UTF-8' );

	wp_mail( 'info@thelearningnet.org', $subject, $body, $headers );

	ln_form_redirect_back( $id );
}
add_action( 'admin_post_ln_form_submit', 'ln_handle_form_submit' );
add_action( 'admin_post_nopriv_ln_form_submit', 'ln_handle_form_submit' );

function ln_form_redirect_back( $id ) {
	$referer = wp_get_referer() ? wp_get_referer() : home_url( '/' );
	$target  = add_query_arg(
		array(
			'ln_sent' => '1',
			'ln_form' => $id,
		),
		remove_query_arg( array( 'ln_sent', 'ln_form' ), $referer )
	);
	wp_safe_redirect( $target );
	exit;
}

/**
 * Footer widget areas (optional extras beside the footer menu).
 */
function ln_theme_widgets_init() {
	for ( $i = 1; $i <= 3; $i++ ) {
		register_sidebar(
			array(
				'name'          => sprintf( __( 'Footer Column %d', 'learning-net' ), $i ),
				'id'            => 'footer-' . $i,
				'description'   => __( 'Appears in the footer.', 'learning-net' ),
				'before_widget' => '<div class="ln-footer-widget">',
				'after_widget'  => '</div>',
				'before_title'  => '<h3 class="ln-footer-widget-title">',
				'after_title'   => '</h3>',
			)
		);
	}
}
add_action( 'widgets_init', 'ln_theme_widgets_init' );

/**
 * Allow page content to reference theme-bundled images with {{THEME_URL}}.
 * Example in page content: <img src="{{THEME_URL}}/assets/images/hero.jpg" alt="...">
 */
function ln_theme_content_urls( $content ) {
	if ( is_string( $content ) && strpos( $content, '{{THEME_URL}}' ) !== false ) {
		$content = str_replace( '{{THEME_URL}}', esc_url( LN_THEME_URI ), $content );
	}
	return $content;
}
add_filter( 'the_content', 'ln_theme_content_urls', 1 );

/**
 * -------------------------------------------------------------------------
 * One Click Demo Import (free plugin) — one-click demo content.
 * Install the theme, install the "One Click Demo Import" plugin, then
 * Appearance > Import Demo Data > Import. Everything below runs automatically.
 * -------------------------------------------------------------------------
 */
function ln_ocdi_import_files() {
	$base = trailingslashit( LN_THEME_DIR ) . 'demo-import/';
	return array(
		array(
			'import_file_name'             => __( 'Learning Net Demo Content', 'learning-net' ),
			'local_import_file'            => $base . 'learning-net-demo-content.xml',
			'local_import_customizer_file' => $base . 'learning-net-customizer.dat',
			'import_preview_image_url'     => trailingslashit( LN_THEME_URI ) . 'screenshot.png',
			'import_notice'                => __( 'Imports 27 pages, 8 blog articles, and the Primary + Footer menus. Sets the Home page as the front page.', 'learning-net' ),
		),
	);
}
add_filter( 'ocdi/import_files', 'ln_ocdi_import_files' );

/**
 * After the demo import: set homepage, assign menus to locations.
 */
function ln_ocdi_after_import() {
	// Set the "Home" page as the static front page.
	$front_page = get_page_by_path( 'home' );
	if ( $front_page ) {
		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $front_page->ID );
	}

	// Assign imported menus to theme locations.
	$locations   = get_theme_mod( 'nav_menu_locations', array() );
	$primaryMenu = get_term_by( 'name', 'Primary Menu', 'nav_menu' );
	$footerMenu  = get_term_by( 'name', 'Footer Menu', 'nav_menu' );

	if ( $primaryMenu && ! is_wp_error( $primaryMenu ) ) {
		$locations['primary'] = $primaryMenu->term_id;
	}
	if ( $footerMenu && ! is_wp_error( $footerMenu ) ) {
		$locations['footer'] = $footerMenu->term_id;
	}
	set_theme_mod( 'nav_menu_locations', $locations );
}
add_action( 'ocdi/after_import', 'ln_ocdi_after_import' );

/**
 * Keep the login/admin clean — no extra clutter.
 */
function ln_theme_login_logo_url() {
	return home_url( '/' );
}
add_filter( 'login_headerurl', 'ln_theme_login_logo_url' );
