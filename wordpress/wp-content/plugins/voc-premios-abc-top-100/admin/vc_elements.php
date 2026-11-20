<?php
if (!defined('ABSPATH')) {
    die('Direct access forbidden.');
}

/**
 * Clase para el elemento de Visual Composer Listado Top 100
 */
if (class_exists('WPBakeryShortCode')) {
    class WPB_voc_listado_top100 extends WPBakeryShortCode
    {

        function __construct()
        {
            add_action('init', array($this, 'vc_mapping'));
            add_shortcode('voc_listado_top100', array($this, 'shortcode_html'));
        }

        public function vc_mapping()
        {
            if (!defined('WPB_VC_VERSION')) {
                return;
            }

            vc_map(array(
                'name' => 'Listado Top 100',
                'base' => 'voc_listado_top100',
                'description' => 'Muestra un listado de candidatos con estilo listado3',
                'category' => 'ABC Top 100',
                'params' => array(
                    array(
                        'type' => 'textfield',
                        'heading' => 'Título del listado',
                        'param_name' => 'titulo',
                        'value' => 'LISTADO TOP 100',
                        'admin_label' => true,
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => 'Tipo de candidato (slug)',
                        'param_name' => 'tipo',
                        'value' => 'top100m',
                        'description' => 'Slug del tipo en metadatos (ej: top100m, trayectoria-destacada)',
                        'admin_label' => true,
                    ),
                    array(
                        'type' => 'dropdown',
                        'heading' => 'Columnas (Grid)',
                        'param_name' => 'columnas',
                        'value' => array(
                            '1 Columna' => '1',
                            '2 Columnas' => '2',
                            '3 Columnas' => '3',
                            '4 Columnas' => '4',
                            '5 Columnas' => '5',
                            '6 Columnas' => '6',
                        ),
                        'std' => '4',
                        'description' => 'Selecciona el número de elementos por fila en desktop.',
                        'admin_label' => true,
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => 'Frecuencia de Publicidad (cada n filas)',
                        'param_name' => 'frecuencia_publi',
                        'value' => '0',
                        'description' => 'Escribe 0 para ocultar, 2 para mostrar cada 2 filas, etc.',
                        'admin_label' => true,
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => 'Ronda',
                        'param_name' => 'ronda',
                        'value' => '1',
                        'description' => 'Ronda del candidato (1 por defecto para Top 100 en esta versión)',
                        'admin_label' => true,
                    ),
                    array(
                        'type' => 'checkbox',
                        'heading' => 'Mostrar buscador',
                        'param_name' => 'mostrar_buscador',
                        'value' => array('Sí' => 'true'),
                        'std' => 'true',
                    ),
                ),
            ));
        }

        public function shortcode_html($atts)
        {
            $atts = shortcode_atts(array(
                'titulo' => 'LISTADO TOP 100',
                'tipo' => 'top100m',
                'columnas' => '4',
                'frecuencia_publi' => '0',
                'ronda' => '1',
                'mostrar_buscador' => 'true',
            ), $atts);

            wp_enqueue_style('voc-listado3-css', GISTAU__PLUGIN_URL . 'assets/css/listado3.css', array(), '1.4.3');
            wp_enqueue_script('voc-listado3-js', GISTAU__PLUGIN_URL . 'assets/js/listado3.js', array(), '1.4.4', true);

            $args = array(
                'post_type' => 'candidato',
                'post_status' => 'publish',
                'posts_per_page' => -1,
                'orderby' => 'title',
                'order' => 'ASC',
                'meta_query' => array(
                    'relation' => 'AND',
                    array(
                        'key' => 'ronda',
                        'value' => intval($atts['ronda']),
                        'compare' => '=',
                    ),
                ),
            );

            $loop = new WP_Query($args);

            // Ordenar por apellido alfabéticamente
            $posts = $loop->posts;
            usort($posts, function ($a, $b) {
                $meta_a = get_post_meta($a->ID, 'candidato_metadata', true);
                $meta_b = get_post_meta($b->ID, 'candidato_metadata', true);

                $apellidos_a = isset($meta_a['apellidos_candidato']) ? $meta_a['apellidos_candidato'] : '';
                $apellidos_b = isset($meta_b['apellidos_candidato']) ? $meta_b['apellidos_candidato'] : '';

                $res = strcasecmp($apellidos_a, $apellidos_b);
                if ($res === 0) {
                    $nombre_a = isset($meta_a['nombre_candidato']) ? $meta_a['nombre_candidato'] : $a->post_title;
                    $nombre_b = isset($meta_b['nombre_candidato']) ? $meta_b['nombre_candidato'] : $b->post_title;
                    return strcasecmp($nombre_a, $nombre_b);
                }
                return $res;
            });
            $loop->posts = $posts;

            ob_start();
            ?>
            <div class="voc-top100-container is-loading">
                <?php if ($atts['mostrar_buscador'] === 'true'): ?>
                    <div class="voc-top100-filters">
                        <div class="voc-top100-filters-left">
                            <div class="voc-top100-search-wrapper">
                                <input type="text" class="voc-top100-buscador" />
                                <span class="voc-top100-btn-clear">Borrar</span>
                            </div>
                            <button class="voc-top100-btn-search" tabindex="-1" aria-hidden="true"><i class="fa fa-search"></i></button>
                        </div>
                        <div class="voc-top100-sort-wrapper">
                            <span class="voc-top100-sort-label">Ordenar:</span>
                            <button class="voc-top100-btn-sort asc active" data-sort="asc">A - Z</button>
                            <button class="voc-top100-btn-sort desc" data-sort="desc">Z - A</button>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="voc-top100-results-info" style="display:none;">
                    Se han encontrado <span class="voc-top100-results-highlight"><span class="voc-top100-results-count">0</span>
                        resultados.</span>
                </div>

                <div class="voc-top100-loader">
                    <i class="fa fa-circle-o-notch fa-spin"></i>
                </div>

                <?php
                $candidates_data = array();
                while ($loop->have_posts()):
                    $loop->the_post();
                    $data = get_post_meta(get_the_ID(), 'candidato_metadata', true);

                    $nombre = isset($data['nombre_candidato']) ? $data['nombre_candidato'] : get_the_title();
                    $apellidos = isset($data['apellidos_candidato']) ? $data['apellidos_candidato'] : '';
                    $cargo = isset($data['cargo_candidato']) ? $data['cargo_candidato'] : '';
                    $empresa = isset($data['empresa_candidato']) ? $data['empresa_candidato'] : '';
                    $foto = isset($data['foto_directivo']) ? $data['foto_directivo'] : '';
                    $linkedin = isset($data['linkedin_candidato']) ? $data['linkedin_candidato'] : '';

                    $candidates_data[] = array(
                        'id' => get_the_ID(),
                        'nombre' => $nombre,
                        'apellidos' => $apellidos,
                        'cargo' => $cargo,
                        'empresa' => $empresa,
                        'foto' => $foto,
                        'linkedin' => $linkedin
                    );
                endwhile;
                ?>

                <div class="voc-top100-grid columns-<?php echo esc_attr($atts['columnas']); ?>"
                    style="--desktop-cols: <?php echo esc_attr(intval($atts['columnas'])); ?>;"
                    data-candidates='<?php echo esc_attr(json_encode($candidates_data)); ?>'
                    data-badge-url='<?php echo esc_url(GISTAU__PLUGIN_URL . 'assets/img/sello.svg'); ?>'>
                    <!-- JS renders cards here -->
                </div>

                <?php if (intval($atts['frecuencia_publi']) > 0): ?>
                    <div class="voc-top100-ads-data" data-frequency="<?php echo esc_attr(intval($atts['frecuencia_publi'])); ?>"
                        style="display:none;">
                        <?php
                        $custom_css_ads = '';
                        for ($i = 1; $i <= 7; $i++):
                            $tipo = get_option("voc_top100_ad_{$i}_type", 'libre');
                            $link = esc_url(get_option("voc_top100_ad_{$i}_link", ''));

                            if ($tipo === 'figma') {
                                $txt = esc_html(get_option("voc_top100_ad_{$i}_text", ''));
                                $logo = esc_url(get_option("voc_top100_ad_{$i}_logo", ''));
                                $logo_link = esc_url(get_option("voc_top100_ad_{$i}_logo_link", ''));

                                $html = '<div class="voc-top100-ad-brand" style="position:relative;">';

                                if ($link) {
                                    $html .= '<a href="' . $link . '" target="_blank" style="position:absolute; top:0; left:0; width:100%; height:100%; z-index:1;"></a>';
                                }

                                $html .= '<span class="ad-text" style="position:relative; z-index:2; pointer-events:none;">' . $txt . '</span>';

                                if ($logo) {
                                    $imgHtml = '<img src="' . $logo . '" class="ad-logo" alt="">';
                                    if ($logo_link) {
                                        $imgHtml = '<a href="' . $logo_link . '" target="_blank" style="position:relative; z-index:3; max-height:100px; display:flex; align-items:center;">' . $imgHtml . '</a>';
                                    } else {
                                        $imgHtml = '<div style="position:relative; z-index:2; pointer-events:none; max-height:100px; display:flex; align-items:center;">' . $imgHtml . '</div>';
                                    }
                                    $html .= $imgHtml;
                                }
                                $html .= '</div>';

                            } elseif ($tipo === 'wpbakery') {
                                $postId = get_option("voc_top100_ad_{$i}_post_id", 0);
                                if ($postId) {
                                    $post = get_post($postId);
                                    if ($post) {
                                        $html = do_shortcode($post->post_content);
                                        // Cargar CSS de WPBakery del anuncio
                                        $shortcode_css = get_post_meta($postId, '_wpb_shortcodes_custom_css', true);
                                        $post_custom_css = get_post_meta($postId, '_wpb_post_custom_css', true);
                                        if (!empty($shortcode_css)) {
                                            $custom_css_ads .= $shortcode_css;
                                        }
                                        if (!empty($post_custom_css)) {
                                            $custom_css_ads .= $post_custom_css;
                                        }
                                        if ($link && trim($html) !== '') {
                                            $html = '<a href="' . $link . '" target="_blank" style="text-decoration:none; display:block;">' . $html . '</a>';
                                        }
                                    }
                                }
                            } else {
                                $html = get_option("voc_top100_ad_{$i}", '');
                                if ($link && trim($html) !== '') {
                                    $html = '<a href="' . $link . '" target="_blank" style="text-decoration:none; display:block;">' . $html . '</a>';
                                }
                            }

                            if (trim($html) !== '') {
                                echo '<div class="voc-top100-ad-content">' . $html . '</div>';
                            }
                        endfor;
                        if (!empty($custom_css_ads)) {
                            echo '<style type="text/css" data-type="voc-ads-custom-css">' . $custom_css_ads . '</style>';
                        }
                        ?>
                    </div>
                <?php endif; ?>

            </div>
            <?php
            wp_reset_postdata();
            return ob_get_clean();
        }
    }

    new WPB_voc_listado_top100();
}
