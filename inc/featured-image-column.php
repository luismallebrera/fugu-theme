<?php
/**
 * Featured Image Admin Column
 * Add featured image thumbnail column in post list with quick edit
 *
 * @package Fugu_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Add featured image column to post types
 */
function fugu_theme_add_featured_image_column( $columns ) {
	// Insert featured image column after checkbox
	$new_columns = array();
	
	foreach ( $columns as $key => $value ) {
		$new_columns[ $key ] = $value;
		
		if ( $key === 'cb' ) {
			$new_columns['featured_image'] = __( 'Featured Image', 'fugu-theme' );
		}
	}
	
	return $new_columns;
}

// Add column to different post types
add_filter( 'manage_posts_columns', 'fugu_theme_add_featured_image_column' );
add_filter( 'manage_pages_columns', 'fugu_theme_add_featured_image_column' );
add_filter( 'manage_portfolio_posts_columns', 'fugu_theme_add_featured_image_column' );
add_filter( 'manage_proyectos_posts_columns', 'fugu_theme_add_featured_image_column' );
add_filter( 'manage_noticias_posts_columns', 'fugu_theme_add_featured_image_column' );
add_filter( 'manage_noticias_slider_posts_columns', 'fugu_theme_add_featured_image_column' );
add_filter( 'manage_gdrs_posts_columns', 'fugu_theme_add_featured_image_column' );

/**
 * Display featured image thumbnail in column
 */
function fugu_theme_display_featured_image_column( $column_name, $post_id ) {
	if ( $column_name === 'featured_image' ) {
		$thumbnail_id = get_post_thumbnail_id( $post_id );
		
		if ( $thumbnail_id ) {
			$thumbnail = wp_get_attachment_image( $thumbnail_id, array( 100, 100 ), false, array(
				'style' => 'width: 100px; height: auto; max-height: 100px; cursor: pointer; border-radius: 4px;'
			) );
			
			echo '<div class="featured-image-wrapper" data-post-id="' . esc_attr( $post_id ) . '">';
			echo '<a href="#" class="change-featured-image" data-post-id="' . esc_attr( $post_id ) . '">';
			echo $thumbnail;
			echo '</a>';
			echo '</div>';
		} else {
			echo '<div class="featured-image-wrapper" data-post-id="' . esc_attr( $post_id ) . '">';
			echo '<a href="#" class="set-featured-image" data-post-id="' . esc_attr( $post_id ) . '" style="display: inline-block; padding: 10px 15px; background: #2271b1; color: white; text-decoration: none; border-radius: 3px; font-size: 13px;">';
			echo __( 'Set Image', 'fugu-theme' );
			echo '</a>';
			echo '</div>';
		}
	}
}

// Add column content to different post types
add_action( 'manage_posts_custom_column', 'fugu_theme_display_featured_image_column', 10, 2 );
add_action( 'manage_pages_custom_column', 'fugu_theme_display_featured_image_column', 10, 2 );

/**
 * Set column width
 */
function fugu_theme_featured_image_column_width() {
	echo '<style>
		.column-featured_image {
			width: 120px;
			text-align: center;
		}
		.featured-image-wrapper {
			position: relative;
		}
		.featured-image-wrapper img {
			transition: opacity 0.3s ease;
		}
		.featured-image-wrapper a:hover img {
			opacity: 0.7;
		}
		.set-featured-image:hover {
			background: #135e96 !important;
		}
	</style>';
}
add_action( 'admin_head', 'fugu_theme_featured_image_column_width' );

/**
 * Enqueue media uploader
 */
function fugu_theme_enqueue_media() {
	global $pagenow;
	
	if ( $pagenow === 'edit.php' ) {
		wp_enqueue_media();
	}
}
add_action( 'admin_enqueue_scripts', 'fugu_theme_enqueue_media' );

/**
 * Add JavaScript for media uploader
 */
function fugu_theme_featured_image_column_js() {
	global $pagenow;
	
	// Only load on post list screens
	if ( $pagenow !== 'edit.php' ) {
		return;
	}
	
	?>
	<script>
	jQuery(document).ready(function($) {
		console.log('Featured image column script loaded');
		
		var mediaUploader;
		var currentPostId;
		
		// Handle click on featured image or "Set Image" button
		$(document).on('click', '.change-featured-image, .set-featured-image', function(e) {
			e.preventDefault();
			console.log('Click detected on featured image');
			
			currentPostId = $(this).data('post-id');
			console.log('Post ID:', currentPostId);
			
			// Check if wp.media is available
			if (typeof wp === 'undefined' || typeof wp.media === 'undefined') {
				console.error('wp.media is not available');
				alert('Media uploader not loaded. Please refresh the page.');
				return;
			}
			
			// If the uploader object has already been created, reopen it
			if (mediaUploader) {
				mediaUploader.open();
				return;
			}
			
			// Create the media uploader
			mediaUploader = wp.media({
				title: '<?php echo esc_js( __( 'Choose Featured Image', 'fugu-theme' ) ); ?>',
				button: {
					text: '<?php echo esc_js( __( 'Set Featured Image', 'fugu-theme' ) ); ?>'
				},
				multiple: false
			});
			
			// When an image is selected
			mediaUploader.on('select', function() {
				var attachment = mediaUploader.state().get('selection').first().toJSON();
				
				// Send AJAX request to set featured image
				$.ajax({
					url: ajaxurl,
					type: 'POST',
					data: {
						action: 'set_featured_image',
						post_id: currentPostId,
						thumbnail_id: attachment.id,
						nonce: '<?php echo wp_create_nonce( 'set_featured_image_nonce' ); ?>'
					},
					success: function(response) {
						if (response.success) {
							// Reload the page to show updated thumbnail
							location.reload();
						} else {
							alert('<?php echo esc_js( __( 'Error setting featured image', 'fugu-theme' ) ); ?>');
						}
					}
				});
			});
			
			// Open the uploader
			mediaUploader.open();
		});
	});
	</script>
	<?php
}
add_action( 'admin_footer', 'fugu_theme_featured_image_column_js' );

/**
 * AJAX handler to set featured image
 */
function fugu_theme_set_featured_image_ajax() {
	check_ajax_referer( 'set_featured_image_nonce', 'nonce' );
	
	if ( ! current_user_can( 'edit_posts' ) ) {
		wp_send_json_error( array( 'message' => __( 'Permission denied', 'fugu-theme' ) ) );
	}
	
	$post_id = isset( $_POST['post_id'] ) ? absint( $_POST['post_id'] ) : 0;
	$thumbnail_id = isset( $_POST['thumbnail_id'] ) ? absint( $_POST['thumbnail_id'] ) : 0;
	
	if ( ! $post_id || ! $thumbnail_id ) {
		wp_send_json_error( array( 'message' => __( 'Invalid data', 'fugu-theme' ) ) );
	}
	
	// Set the featured image
	$result = set_post_thumbnail( $post_id, $thumbnail_id );
	
	if ( $result ) {
		wp_send_json_success( array( 'message' => __( 'Featured image updated', 'fugu-theme' ) ) );
	} else {
		wp_send_json_error( array( 'message' => __( 'Error updating featured image', 'fugu-theme' ) ) );
	}
}
add_action( 'wp_ajax_set_featured_image', 'fugu_theme_set_featured_image_ajax' );
