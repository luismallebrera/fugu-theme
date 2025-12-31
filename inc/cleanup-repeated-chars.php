<?php
/**
 * Cleanup repeated characters at the end of post titles
 * Run once to clean existing posts
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Clean repeated characters from title
 */
function fugu_theme_clean_repeated_chars($title) {
    // Remove repeated characters (3 or more of the same letter) at the end
    $title = preg_replace('/(.)\1{2,}(\.{3})?$/', '$1$2', $title);
    return $title;
}

/**
 * Admin notice to run cleanup
 */
function fugu_theme_cleanup_admin_notice() {
    if (!current_user_can('manage_options')) {
        return;
    }
    
    if (get_option('fugu_theme_titles_cleaned')) {
        return;
    }
    
    ?>
    <div class="notice notice-warning is-dismissible">
        <p>
            <strong><?php _e('Limpiar títulos con caracteres repetidos', 'fugu-theme'); ?></strong><br>
            <?php _e('Se han detectado posibles títulos con caracteres repetidos. Haz clic para limpiarlos.', 'fugu-theme'); ?>
        </p>
        <p>
            <a href="<?php echo wp_nonce_url(admin_url('admin.php?page=cleanup-titles'), 'cleanup_titles_nonce'); ?>" class="button button-primary">
                <?php _e('Limpiar títulos ahora', 'fugu-theme'); ?>
            </a>
        </p>
    </div>
    <?php
}
add_action('admin_notices', 'fugu_theme_cleanup_admin_notice');

/**
 * Register cleanup page
 */
function fugu_theme_register_cleanup_page() {
    if (isset($_GET['page']) && $_GET['page'] === 'cleanup-titles' && 
        isset($_GET['_wpnonce']) && wp_verify_nonce($_GET['_wpnonce'], 'cleanup_titles_nonce')) {
        
        if (!current_user_can('manage_options')) {
            wp_die(__('No tienes permisos para realizar esta acción.', 'fugu-theme'));
        }
        
        // Get all posts with potential repeated chars
        $post_types = array('post', 'galgdr', 'municipio', 'portfolio', 'proyectos');
        $updated = 0;
        
        foreach ($post_types as $post_type) {
            $posts = get_posts(array(
                'post_type' => $post_type,
                'posts_per_page' => -1,
                'post_status' => 'any'
            ));
            
            foreach ($posts as $post) {
                $old_title = $post->post_title;
                $new_title = fugu_theme_clean_repeated_chars($old_title);
                
                if ($old_title !== $new_title) {
                    wp_update_post(array(
                        'ID' => $post->ID,
                        'post_title' => $new_title
                    ));
                    $updated++;
                }
            }
        }
        
        update_option('fugu_theme_titles_cleaned', true);
        
        wp_redirect(admin_url('admin.php?page=cleanup-titles-done&updated=' . $updated));
        exit;
    }
    
    if (isset($_GET['page']) && $_GET['page'] === 'cleanup-titles-done') {
        $updated = isset($_GET['updated']) ? intval($_GET['updated']) : 0;
        ?>
        <div class="wrap">
            <h1><?php _e('Limpieza completada', 'fugu-theme'); ?></h1>
            <div class="notice notice-success">
                <p>
                    <?php printf(__('Se han limpiado %d títulos con caracteres repetidos.', 'fugu-theme'), $updated); ?>
                </p>
            </div>
            <p>
                <a href="<?php echo admin_url(); ?>" class="button button-primary">
                    <?php _e('Volver al Dashboard', 'fugu-theme'); ?>
                </a>
            </p>
        </div>
        <?php
    }
}
add_action('admin_menu', 'fugu_theme_register_cleanup_page');
