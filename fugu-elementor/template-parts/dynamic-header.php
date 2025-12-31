<?php
/**
 * The template for displaying header.
 *
 * @package FuguElementor
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! fugu_get_header_display() ) {
	return;
}

$is_editor = isset( $_GET['elementor-preview'] );
$site_name = get_bloginfo( 'name' );
$tagline   = get_bloginfo( 'description', 'display' );
$header_class = did_action( 'elementor/loaded' ) ? fugu_get_header_layout_class() : '';
$menu_args = [
	'theme_location' => 'menu-1',
	'fallback_cb' => false,
	'container' => false,
	'echo' => false,
];
$header_nav_menu = wp_nav_menu( $menu_args );
$header_mobile_nav_menu = wp_nav_menu( $menu_args ); // The same menu but separate call to avoid duplicate ID attributes.
?>
<header id="site-header" class="site-header dynamic-header <?php echo esc_attr( $header_class ); ?>">
	<div class="header-inner">
		<div class="site-branding show-<?php echo esc_attr( fugu_elementor_get_setting( 'fugu_header_logo_type' ) ); ?>">
			<?php if ( has_custom_logo() && ( 'title' !== fugu_elementor_get_setting( 'fugu_header_logo_type' ) || $is_editor ) ) : ?>
				<div class="site-logo <?php echo esc_attr( fugu_show_or_hide( 'fugu_header_logo_display' ) ); ?>">
					<?php the_custom_logo(); ?>
				</div>
			<?php endif;

			if ( $site_name && ( 'logo' !== fugu_elementor_get_setting( 'fugu_header_logo_type' ) || $is_editor ) ) : ?>
				<div class="site-title <?php echo esc_attr( fugu_show_or_hide( 'fugu_header_logo_display' ) ); ?>">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr__( 'Home', 'fugu-elementor' ); ?>" rel="home">
						<?php echo esc_html( $site_name ); ?>
					</a>
				</div>
			<?php endif;

			if ( $tagline && ( fugu_elementor_get_setting( 'fugu_header_tagline_display' ) || $is_editor ) ) : ?>
				<p class="site-description <?php echo esc_attr( fugu_show_or_hide( 'fugu_header_tagline_display' ) ); ?>">
					<?php echo esc_html( $tagline ); ?>
				</p>
			<?php endif; ?>
		</div>

		<?php if ( $header_nav_menu ) : ?>
			<nav class="site-navigation <?php echo esc_attr( fugu_show_or_hide( 'fugu_header_menu_display' ) ); ?>" aria-label="<?php echo esc_attr__( 'Main menu', 'fugu-elementor' ); ?>">
				<?php
				// PHPCS - escaped by WordPress with "wp_nav_menu"
				echo $header_nav_menu; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				?>
			</nav>
		<?php endif; ?>
		<?php if ( $header_mobile_nav_menu ) : ?>
			<div class="site-navigation-toggle-holder <?php echo esc_attr( fugu_show_or_hide( 'fugu_header_menu_display' ) ); ?>">
				<button type="button" class="site-navigation-toggle" aria-label="<?php echo esc_attr( 'Menu', 'fugu-elementor' ); ?>">
					<span class="site-navigation-toggle-icon" aria-hidden="true"></span>
				</button>
			</div>
			<nav class="site-navigation-dropdown <?php echo esc_attr( fugu_show_or_hide( 'fugu_header_menu_display' ) ); ?>" aria-label="<?php echo esc_attr__( 'Mobile menu', 'fugu-elementor' ); ?>" aria-hidden="true" inert>
				<?php
				// PHPCS - escaped by WordPress with "wp_nav_menu"
				echo $header_mobile_nav_menu; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				?>
			</nav>
		<?php endif; ?>
	</div>
</header>
