<?php
/**
 * Site header — matches the finalized Learning Net preview exactly.
 *
 * @package Learning_Net
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link" href="#main-content"><?php esc_html_e( 'Skip to content', 'learning-net' ); ?></a>

<header class="site-nav" id="site-nav">
	<div class="wrap nav-row">
		<a class="brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php esc_attr_e( 'The Learning Net home', 'learning-net' ); ?>">
			<img class="logo-light" src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/logo-color.png' ); ?>" alt="<?php esc_attr_e( 'The Learning Net', 'learning-net' ); ?>">
			<img class="logo-dark" src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/logo-white.png' ); ?>" alt="">
		</a>
		<nav class="nav-links" aria-label="<?php esc_attr_e( 'Primary', 'learning-net' ); ?>">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'primary',
					'container'      => false,
					'items_wrap'     => '%3$s',
					'depth'          => 1,
					'walker'         => new LN_Preview_Nav_Walker(),
					'fallback_cb'    => 'learning_net_primary_fallback_links',
				)
			);
			?>
		</nav>
		<div class="mobile-menu">
			<button class="menu-toggle" id="menuToggle" type="button" aria-expanded="false" aria-controls="mobilePanel">
				<span class="menu-icon" aria-hidden="true"></span><span><?php esc_html_e( 'Menu', 'learning-net' ); ?></span>
			</button>
			<div class="mobile-panel" id="mobilePanel" hidden>
				<nav id="mobileMenuLinks" aria-label="<?php esc_attr_e( 'Mobile navigation', 'learning-net' ); ?>"></nav>
				<div class="menu-actions">
					<a class="btn secondary" href="<?php echo esc_url( home_url( '/student-login/' ) ); ?>"><?php esc_html_e( 'Student login', 'learning-net' ); ?></a>
					<a class="btn" href="<?php echo esc_url( home_url( '/registration/' ) ); ?>"><?php esc_html_e( 'Enrol now', 'learning-net' ); ?></a>
				</div>
			</div>
		</div>
		<div class="nav-actions">
			<a class="btn secondary" href="<?php echo esc_url( home_url( '/student-login/' ) ); ?>"><?php esc_html_e( 'Student login', 'learning-net' ); ?></a>
			<a class="btn" href="<?php echo esc_url( home_url( '/registration/' ) ); ?>"><?php esc_html_e( 'Enrol now', 'learning-net' ); ?></a>
		</div>
	</div>
</header>

<main id="main-content">
