<?php
/**
 * Auto Rename Featured Image
 * Automatically rename featured images based on post title
 *
 * @package Fugu_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Rename existing featured image when post title changes
 */
function fugu_theme_rename_featured_on_save( $post_id, $post, $update ) {
	// Don't run on autosave
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	// Don't run on revisions
	if ( wp_is_post_revision( $post_id ) ) {
		return;
	}

	// Check if post has a featured image
	if ( ! has_post_thumbnail( $post_id ) ) {
		return;
	}

	// Get the attachment ID
	$attachment_id = get_post_thumbnail_id( $post_id );
	
	if ( ! $attachment_id ) {
		return;
	}

	// Get post title
	$post_title = $post->post_title;
	
	if ( empty( $post_title ) ) {
		return;
	}

	// Remove accents
	$post_title = remove_accents( $post_title );
	
	// Convert to lowercase
	$post_title = strtolower( $post_title );
	
	// Replace spaces with hyphens
	$post_title = str_replace( ' ', '-', $post_title );
	
	// Remove special characters except hyphens
	$post_title = preg_replace( '/[^a-z0-9\-]/', '', $post_title );
	
	// Remove multiple consecutive hyphens
	$post_title = preg_replace( '/-+/', '-', $post_title );
	
	// Remove leading and trailing hyphens
	$post_title = trim( $post_title, '-' );

	// Get current attachment file
	$file_path = get_attached_file( $attachment_id );
	
	if ( ! $file_path || ! file_exists( $file_path ) ) {
		return;
	}

	// Get file extension
	$file_ext = pathinfo( $file_path, PATHINFO_EXTENSION );
	
	// Get directory
	$file_dir = dirname( $file_path );
	
	// Create new filename
	$new_filename = $post_title . '.' . $file_ext;
	$new_file_path = $file_dir . '/' . $new_filename;

	// Get current filename without path
	$current_filename = basename( $file_path );
	
	// Don't rename if filename is already correct
	if ( $current_filename === $new_filename ) {
		return;
	}

	// Rename the file
	if ( rename( $file_path, $new_file_path ) ) {
		// Update attachment metadata
		update_attached_file( $attachment_id, $new_file_path );
		
		// Get attachment metadata
		$metadata = wp_get_attachment_metadata( $attachment_id );
		
		if ( $metadata && isset( $metadata['file'] ) ) {
			// Update the file path in metadata
			$metadata['file'] = str_replace( $current_filename, $new_filename, $metadata['file'] );
			
			// Rename thumbnail files if they exist
			if ( isset( $metadata['sizes'] ) && is_array( $metadata['sizes'] ) ) {
				foreach ( $metadata['sizes'] as $size => $size_data ) {
					$old_thumb_path = $file_dir . '/' . $size_data['file'];
					
					// Create new thumbnail name based on new filename
					$thumb_ext = pathinfo( $size_data['file'], PATHINFO_EXTENSION );
					$new_thumb_name = $post_title . '-' . $size_data['width'] . 'x' . $size_data['height'] . '.' . $thumb_ext;
					$new_thumb_path = $file_dir . '/' . $new_thumb_name;
					
					if ( file_exists( $old_thumb_path ) ) {
						rename( $old_thumb_path, $new_thumb_path );
						$metadata['sizes'][ $size ]['file'] = $new_thumb_name;
					}
				}
			}
			
			// Update attachment metadata
			wp_update_attachment_metadata( $attachment_id, $metadata );
		}
	}
}
add_action( 'save_post', 'fugu_theme_rename_featured_on_save', 10, 3 );

/**
 * Rename image when it's set as featured image (including from admin column)
 */
function fugu_theme_rename_on_set_thumbnail( $meta_id, $post_id, $meta_key, $meta_value ) {
	// Only process _thumbnail_id meta key
	if ( $meta_key !== '_thumbnail_id' ) {
		return;
	}

	$attachment_id = absint( $meta_value );
	
	if ( ! $attachment_id ) {
		return;
	}

	// Get the post
	$post = get_post( $post_id );
	
	if ( ! $post ) {
		return;
	}

	// Get post title
	$post_title = $post->post_title;
	
	if ( empty( $post_title ) ) {
		return;
	}

	// Remove accents
	$post_title = remove_accents( $post_title );
	
	// Convert to lowercase
	$post_title = strtolower( $post_title );
	
	// Replace spaces with hyphens
	$post_title = str_replace( ' ', '-', $post_title );
	
	// Remove special characters except hyphens
	$post_title = preg_replace( '/[^a-z0-9\-]/', '', $post_title );
	
	// Remove multiple consecutive hyphens
	$post_title = preg_replace( '/-+/', '-', $post_title );
	
	// Remove leading and trailing hyphens
	$post_title = trim( $post_title, '-' );

	// Get current attachment file
	$file_path = get_attached_file( $attachment_id );
	
	if ( ! $file_path || ! file_exists( $file_path ) ) {
		return;
	}

	// Get file extension
	$file_ext = pathinfo( $file_path, PATHINFO_EXTENSION );
	
	// Get directory
	$file_dir = dirname( $file_path );
	
	// Create new filename
	$new_filename = $post_title . '.' . $file_ext;
	$new_file_path = $file_dir . '/' . $new_filename;

	// Get current filename without path
	$current_filename = basename( $file_path );
	
	// Don't rename if filename is already correct
	if ( $current_filename === $new_filename ) {
		return;
	}

	// Check if target file already exists
	if ( file_exists( $new_file_path ) && $new_file_path !== $file_path ) {
		// Add a unique number to avoid conflicts
		$counter = 1;
		$base_name = $post_title;
		while ( file_exists( $new_file_path ) ) {
			$new_filename = $base_name . '-' . $counter . '.' . $file_ext;
			$new_file_path = $file_dir . '/' . $new_filename;
			$counter++;
		}
	}

	// Rename the file
	if ( rename( $file_path, $new_file_path ) ) {
		// Update attachment metadata
		update_attached_file( $attachment_id, $new_file_path );
		
		// Get attachment metadata
		$metadata = wp_get_attachment_metadata( $attachment_id );
		
		if ( $metadata && isset( $metadata['file'] ) ) {
			// Update the file path in metadata
			$metadata['file'] = str_replace( $current_filename, $new_filename, $metadata['file'] );
			
			// Rename thumbnail files if they exist
			if ( isset( $metadata['sizes'] ) && is_array( $metadata['sizes'] ) ) {
				$base_name_for_thumbs = str_replace( '.' . $file_ext, '', $new_filename );
				
				foreach ( $metadata['sizes'] as $size => $size_data ) {
					$old_thumb_path = $file_dir . '/' . $size_data['file'];
					
					// Create new thumbnail name based on new filename
					$thumb_ext = pathinfo( $size_data['file'], PATHINFO_EXTENSION );
					$new_thumb_name = $base_name_for_thumbs . '-' . $size_data['width'] . 'x' . $size_data['height'] . '.' . $thumb_ext;
					$new_thumb_path = $file_dir . '/' . $new_thumb_name;
					
					if ( file_exists( $old_thumb_path ) && $old_thumb_path !== $new_thumb_path ) {
						if ( rename( $old_thumb_path, $new_thumb_path ) ) {
							$metadata['sizes'][ $size ]['file'] = $new_thumb_name;
						}
					}
				}
			}
			
			// Update attachment metadata
			wp_update_attachment_metadata( $attachment_id, $metadata );
		}
	}
}
add_action( 'added_post_meta', 'fugu_theme_rename_on_set_thumbnail', 10, 4 );
add_action( 'updated_post_meta', 'fugu_theme_rename_on_set_thumbnail', 10, 4 );
