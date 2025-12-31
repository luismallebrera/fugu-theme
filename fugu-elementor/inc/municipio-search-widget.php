<?php
/**
 * Municipio Search via Shortcode
 * Two-step search: Province → Municipality
 */

if (!defined('ABSPATH')) {
    exit;
}

function fugu_elementor_municipio_search_shortcode($atts) {
    $atts = shortcode_atts(['popup_id' => '7468'], $atts);
    $popup_id = intval($atts['popup_id']);
    
    $provincias = get_terms([
        'taxonomy' => 'provincia',
        'hide_empty' => false,
    ]);

    $municipios = get_posts([
        'post_type'      => 'municipio',
        'posts_per_page' => -1,
        'orderby'        => 'title',
        'order'          => 'ASC',
    ]);
    
    ob_start();
    ?>
    <div class="municipio-search-widget">
        <div class="search-step search-provincia">
            <label for="provincia-select"><?php _e('Selecciona Provincia:', 'fugu-elementor'); ?></label>
            <select id="provincia-select" class="provincia-select">
                <option value=""><?php _e('-- Selecciona una provincia --', 'fugu-elementor'); ?></option>
                <?php foreach ($provincias as $provincia): ?>
                    <option value="<?php echo esc_attr($provincia->term_id); ?>">
                        <?php echo esc_html($provincia->name); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="search-step search-municipio" style="margin-top: 20px;">
            <label for="municipio-select"><?php _e('Selecciona Municipio:', 'fugu-elementor'); ?></label>
            <select id="municipio-select" class="municipio-select">
                <option value=""><?php _e('-- Selecciona un municipio --', 'fugu-elementor'); ?></option>
                <?php foreach ($municipios as $municipio):
                    $provincia_id = get_post_meta($municipio->ID, '_municipio_provincia', true);
                    ?>
                    <option value="<?php echo esc_attr($municipio->ID); ?>" data-provincia="<?php echo esc_attr($provincia_id); ?>">
                        <?php echo esc_html($municipio->post_title); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <script>
    jQuery(document).ready(function($) {
        var popupId = <?php echo $popup_id; ?>;
        var $provinciaSelect = $('#provincia-select');
        var $municipioSelect = $('#municipio-select');

        var defaultMunicipioOption = $municipioSelect.find('option').first().clone();
        var municipioOptions = $municipioSelect.find('option').not(':first').clone();

        $provinciaSelect.select2({
            placeholder: '<?php _e('-- Selecciona una provincia --', 'fugu-elementor'); ?>',
            allowClear: true,
            width: '100%',
            dropdownCssClass: 'lenis-prevent-dropdown'
        });
        
        $municipioSelect.select2({
            placeholder: '<?php _e('-- Selecciona un municipio --', 'fugu-elementor'); ?>',
            allowClear: true,
            width: '100%',
            dropdownCssClass: 'lenis-prevent-dropdown'
        });
        
        $provinciaSelect.add($municipioSelect).on('select2:open', function() {
            setTimeout(function() {
                $('.select2-results__options').attr('data-lenis-prevent', '');
            }, 0);
        });
        
        function renderMunicipios(provinciaId) {
            $municipioSelect.empty().append(defaultMunicipioOption.clone());

            municipioOptions.each(function() {
                var $option = $(this);
                var optionProvincia = String($option.data('provincia') || '');

                if (!provinciaId || optionProvincia === provinciaId) {
                    $municipioSelect.append($option.clone());
                }
            });

            $municipioSelect.val('').trigger('change');
        }

        renderMunicipios('');

        $provinciaSelect.on('change', function() {
            renderMunicipios($(this).val());
        });
        
        $municipioSelect.on('change', function() {
            var municipioId = $(this).val();
            
            if (municipioId) {
                window.location.href = window.location.pathname + '?municipio_id=' + municipioId;
            }
        });
    });
    </script>
    
    <style>
    .municipio-search-widget {
        padding: 20px;
    }
    .search-step {
        margin-bottom: 15px;
    }
    .search-step label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
    }
    .search-step select {
        width: 100%;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 16px;
        height: 44px;
    }
    .select2-container .select2-selection--single {
        height: 44px;
    }
    .select2-container .select2-selection--single .select2-selection__rendered {
        line-height: 44px;
    }
    .select2-container .select2-selection--single .select2-selection__arrow {
        height: 42px;
    }
    </style>
    <?php
    return ob_get_clean();
}
add_shortcode('municipio_search', 'fugu_elementor_municipio_search_shortcode');

function fugu_elementor_get_municipios_by_provincia() {
    $provincia_id = isset($_GET['provincia_id']) ? absint($_GET['provincia_id']) : 0;
    
    if (!$provincia_id) {
        wp_send_json_error('No provincia ID provided');
    }
    
    $municipios = get_posts([
        'post_type' => 'municipio',
        'posts_per_page' => -1,
        'meta_query' => [
            [
                'key' => '_municipio_provincia',
                'value' => $provincia_id,
                'compare' => '='
            ]
        ],
        'orderby' => 'title',
        'order' => 'ASC'
    ]);
    
    $data = [];
    foreach ($municipios as $municipio) {
        $data[] = [
            'id' => $municipio->ID,
            'title' => $municipio->post_title
        ];
    }
    
    wp_send_json_success($data);
}
add_action('wp_ajax_get_municipios_by_provincia', 'fugu_elementor_get_municipios_by_provincia');
add_action('wp_ajax_nopriv_get_municipios_by_provincia', 'fugu_elementor_get_municipios_by_provincia');
