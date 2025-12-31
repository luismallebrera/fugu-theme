<?php
/**
 * Import GDR and Municipios from CSV
 * Run this file once from WordPress admin or via WP-CLI
 */

if (!defined('ABSPATH')) {
    exit;
}

function fugu_elementor_import_galgdr_municipios() {
    $csv_file = get_template_directory() . '/inc/GALGDR-Export-2025-December-06-1240.csv';
    
    if (!file_exists($csv_file)) {
        return new WP_Error('file_not_found', 'CSV file not found');
    }
    
    $handle = fopen($csv_file, 'r');
    if (!$handle) {
        return new WP_Error('file_error', 'Cannot open CSV file');
    }
    
    fgetcsv($handle, 0, ';');
    
    $results = array(
        'galgdr_created' => 0,
        'municipios_created' => 0,
        'provincias_created' => array(),
        'errors' => array()
    );
    
    while (($data = fgetcsv($handle, 0, ';')) !== false) {
        if (count($data) < 4) {
            continue;
        }
        
        $galgdr_id = $data[0];
        $galgdr_title = $data[1];
        $provincia_name = $data[2];
        $municipios_string = $data[3];
        
        $municipios = explode('|', $municipios_string);
        
        $provincia_term = term_exists($provincia_name, 'provincia');
        if (!$provincia_term) {
            $provincia_term = wp_insert_term($provincia_name, 'provincia');
            if (is_wp_error($provincia_term)) {
                $results['errors'][] = 'Error creating provincia: ' . $provincia_name;
                continue;
            }
            $results['provincias_created'][] = $provincia_name;
        }
        $provincia_term_id = is_array($provincia_term) ? $provincia_term['term_id'] : $provincia_term;
        
        $existing_galgdr = get_page_by_title($galgdr_title, OBJECT, 'galgdr');
        
        if (!$existing_galgdr) {
            $galgdr_post_id = wp_insert_post(array(
                'post_title'    => $galgdr_title,
                'post_type'     => 'galgdr',
                'post_status'   => 'publish',
                'post_content'  => '',
            ));
            
            if (is_wp_error($galgdr_post_id)) {
                $results['errors'][] = 'Error creating GDR: ' . $galgdr_title;
                continue;
            }
            
            $results['galgdr_created']++;
        } else {
            $galgdr_post_id = $existing_galgdr->ID;
        }
        
        wp_set_post_terms($galgdr_post_id, array($provincia_term_id), 'provincia');
        
        foreach ($municipios as $municipio_name) {
            $municipio_name = trim($municipio_name);
            if (empty($municipio_name)) {
                continue;
            }
            
            $existing_municipio = get_page_by_title($municipio_name, OBJECT, 'municipio');
            
            if (!$existing_municipio) {
                $municipio_post_id = wp_insert_post(array(
                    'post_title'    => $municipio_name,
                    'post_type'     => 'municipio',
                    'post_status'   => 'publish',
                    'post_content'  => '',
                ));
                
                if (is_wp_error($municipio_post_id)) {
                    $results['errors'][] = 'Error creating municipio: ' . $municipio_name;
                    continue;
                }
                
                update_post_meta($municipio_post_id, '_municipio_galgdr_asociado', $galgdr_post_id);
                update_post_meta($municipio_post_id, '_municipio_provincia', $provincia_term_id);
                
                $results['municipios_created']++;
            } else {
                update_post_meta($existing_municipio->ID, '_municipio_galgdr_asociado', $galgdr_post_id);
                update_post_meta($existing_municipio->ID, '_municipio_provincia', $provincia_term_id);
            }
        }
    }
    
    fclose($handle);
    
    return $results;
}

add_action('admin_menu', 'fugu_elementor_import_menu');
function fugu_elementor_import_menu() {
    add_management_page(
        'Import GDR & Municipios',
        'Import GDR',
        'manage_options',
        'import-galgdr-municipios',
        'fugu_elementor_import_page'
    );
}

function fugu_elementor_import_page() {
    ?>
    <div class="wrap">
        <h1>Import GDR & Municipios</h1>
        
        <?php
        if (isset($_POST['run_import']) && check_admin_referer('import_galgdr_nonce')) {
            $results = fugu_elementor_import_galgdr_municipios();
            
            if (is_wp_error($results)) {
                echo '<div class="error"><p>' . $results->get_error_message() . '</p></div>';
            } else {
                echo '<div class="updated"><p><strong>Import completed!</strong></p>';
                echo '<ul>';
                echo '<li>GDR created: ' . $results['galgdr_created'] . '</li>';
                echo '<li>Municipios created: ' . $results['municipios_created'] . '</li>';
                echo '<li>Provincias created: ' . count($results['provincias_created']) . '</li>';
                if (!empty($results['errors'])) {
                    echo '<li style="color: red;">Errors: ' . count($results['errors']) . '</li>';
                }
                echo '</ul></div>';
                
                if (!empty($results['errors'])) {
                    echo '<div class="error"><p><strong>Errors:</strong></p><ul>';
                    foreach ($results['errors'] as $error) {
                        echo '<li>' . esc_html($error) . '</li>';
                    }
                    echo '</ul></div>';
                }
            }
        }
        ?>
        
        <form method="post">
            <?php wp_nonce_field('import_galgdr_nonce'); ?>
            <p>This will import all GDR and Municipios from the CSV file.</p>
            <p><strong>Warning:</strong> Make sure to enable the "Enable GDR & Municipios" option in the Customizer first!</p>
            <p>
                <input type="submit" name="run_import" class="button button-primary" value="Run Import">
            </p>
        </form>
    </div>
    <?php
}
