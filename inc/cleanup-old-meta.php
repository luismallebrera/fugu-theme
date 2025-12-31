<?php
/**
 * Cleanup Old Meta Fields
 * Remove obsolete custom fields from posts
 *
 * @package Fugu_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * List of old meta keys to remove from Portfolio posts
 */
function fugu_theme_get_old_portfolio_meta_keys() {
	return array(
		'titulo1',
		'_titulo1',
		'titulo2',
		'_titulo2',
		'subtitulo',
		'_subtitulo',
		'texto_left',
		'_texto_left',
		'url_text_left',
		'_url_text_left',
		'texto_right',
		'_texto_right',
		'categoria_title',
		'_categoria_title',
		'icono',
		'_icono',
		'namespace',
		'_namespace',
		'parallax_image',
		'_parallax_image',
		'add_custom_body_class',
		'page_darklight',
		'_page_darklight',
		'title_color', // Old version
		'_title_color', // Old version
		'_portfolio_title_color', // Old version (now uses portfolio_title_color without underscore)
		'color_title',
		'_color_title',
		'portfolio_category',
		'_portfolio_category',
		'title',
		'_title',
		'subtitle',
		'_subtitle',
		'description_title',
		'_description_title',
	);
}

/**
 * Clean old meta fields from a specific post
 */
function fugu_theme_clean_post_meta( $post_id ) {
	$old_meta_keys = fugu_theme_get_old_portfolio_meta_keys();
	
	foreach ( $old_meta_keys as $meta_key ) {
		delete_post_meta( $post_id, $meta_key );
	}
	
	return true;
}

/**
 * Clean old meta fields from all Portfolio posts
 * Run this manually via admin or wp-cli
 */
function fugu_theme_cleanup_all_portfolio_meta() {
	$args = array(
		'post_type'      => 'portfolio',
		'posts_per_page' => -1,
		'post_status'    => 'any',
		'fields'         => 'ids',
	);
	
	$posts = get_posts( $args );
	$cleaned = 0;
	
	foreach ( $posts as $post_id ) {
		fugu_theme_clean_post_meta( $post_id );
		$cleaned++;
	}
	
	return $cleaned;
}

/**
 * Add admin menu item to run cleanup
 */
function fugu_theme_add_cleanup_admin_menu() {
	add_submenu_page(
		'tools.php',
		__( 'Cleanup Old Meta', 'fugu-theme' ),
		__( 'Cleanup Old Meta', 'fugu-theme' ),
		'manage_options',
		'cleanup-old-meta',
		'fugu_theme_cleanup_admin_page'
	);
}
add_action( 'admin_menu', 'fugu_theme_add_cleanup_admin_menu' );

/**
 * Admin page for cleanup tool
 */
function fugu_theme_cleanup_admin_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	
	$cleaned = 0;
	$message = '';
	
	// Handle cleanup action
	if ( isset( $_POST['cleanup_meta'] ) && check_admin_referer( 'cleanup_old_meta_nonce' ) ) {
		$cleaned = fugu_theme_cleanup_all_portfolio_meta();
		$message = sprintf(
			__( 'Successfully cleaned old meta fields from %d portfolio posts.', 'fugu-theme' ),
			$cleaned
		);
	}
	
	?>
	<div class="wrap">
		<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
		
		<?php if ( $message ) : ?>
			<div class="notice notice-success is-dismissible">
				<p><?php echo esc_html( $message ); ?></p>
			</div>
		<?php endif; ?>
		
		<div class="card">
			<h2><?php _e( 'Clean Old Portfolio Meta Fields', 'fugu-theme' ); ?></h2>
			<p><?php _e( 'This tool will remove old, unused custom fields from all Portfolio posts.', 'fugu-theme' ); ?></p>
			<p><strong><?php _e( 'Meta fields that will be removed:', 'fugu-theme' ); ?></strong></p>
			<ul style="list-style: disc; margin-left: 20px;">
				<?php foreach ( fugu_theme_get_old_portfolio_meta_keys() as $key ) : ?>
					<li><code><?php echo esc_html( $key ); ?></code></li>
				<?php endforeach; ?>
			</ul>
			
			<form method="post" onsubmit="return confirm('<?php esc_attr_e( 'Are you sure you want to clean all old meta fields? This action cannot be undone.', 'fugu-theme' ); ?>');">
				<?php wp_nonce_field( 'cleanup_old_meta_nonce' ); ?>
				<p>
					<input type="submit" name="cleanup_meta" class="button button-primary" value="<?php esc_attr_e( 'Clean Old Meta Fields', 'fugu-theme' ); ?>">
				</p>
			</form>
		</div>
		
		<div class="card" style="margin-top: 20px;">
			<h2><?php _e( 'Current Active Meta Fields', 'fugu-theme' ); ?></h2>
			<p><?php _e( 'These are the meta fields currently in use:', 'fugu-theme' ); ?></p>
			<ul style="list-style: disc; margin-left: 20px;">
				<li><code>_portfolio_description</code> - <?php _e( 'Portfolio Description', 'fugu-theme' ); ?></li>
				<li><code>portfolio_title_color</code> - <?php _e( 'Title Color (dark/light)', 'fugu-theme' ); ?></li>
				<li><code>_portfolio_large_image</code> - <?php _e( 'Large Image', 'fugu-theme' ); ?></li>
				<li><code>_portfolio_medium_image</code> - <?php _e( 'Medium Image', 'fugu-theme' ); ?></li>
				<li><code>_portfolio_small_image</code> - <?php _e( 'Small Image', 'fugu-theme' ); ?></li>
				<li><code>_portfolio_button_text</code> - <?php _e( 'Button Text', 'fugu-theme' ); ?></li>
				<li><code>_portfolio_button_link</code> - <?php _e( 'Button Link', 'fugu-theme' ); ?></li>
				<li><code>_portfolio_gallery</code> - <?php _e( 'Gallery', 'fugu-theme' ); ?></li>
			</ul>
		</div>
	</div>
	<?php
}
