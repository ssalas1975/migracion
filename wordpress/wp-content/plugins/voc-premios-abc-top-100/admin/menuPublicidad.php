<?php

add_action('admin_menu', 'voc_top100_publicidad_menu');

function voc_top100_publicidad_menu()
{
    add_menu_page(
        __('Publicidad Top 100', 'textdomain'),
        __('Publicidad Top 100', 'textdomain'),
        'manage_options',
        'publicidad-top100',
        'voc_top100_publicidad_page',
        'dashicons-megaphone',
        30
    );
    add_submenu_page(
        'publicidad-top100',
        __('Configuración', 'textdomain'),
        __('Configuración', 'textdomain'),
        'manage_options',
        'publicidad-top100',
        'voc_top100_publicidad_page'
    );
}

function voc_top100_publicidad_page()
{
    wp_enqueue_media();
    if (isset($_POST['submit'])) {
        for ($i = 1; $i <= 7; $i++) {
            update_option("voc_top100_ad_{$i}_type", sanitize_text_field($_POST["voc_top100_ad_{$i}_type"]));
            if (isset($_POST["voc_top100_ad_{$i}"])) {
                update_option("voc_top100_ad_{$i}", wp_kses_post($_POST["voc_top100_ad_{$i}"]));
            }
            update_option("voc_top100_ad_{$i}_text", sanitize_text_field($_POST["voc_top100_ad_{$i}_text"]));
            update_option("voc_top100_ad_{$i}_logo", esc_url_raw($_POST["voc_top100_ad_{$i}_logo"]));
            if (isset($_POST["voc_top100_ad_{$i}_logo_link"])) {
                update_option("voc_top100_ad_{$i}_logo_link", esc_url_raw($_POST["voc_top100_ad_{$i}_logo_link"]));
            }
            if (isset($_POST["voc_top100_ad_{$i}_link"])) {
                update_option("voc_top100_ad_{$i}_link", esc_url_raw($_POST["voc_top100_ad_{$i}_link"]));
            }
            if (isset($_POST["voc_top100_ad_{$i}_post_id"])) {
                update_option("voc_top100_ad_{$i}_post_id", intval($_POST["voc_top100_ad_{$i}_post_id"]));
            }
        }
        echo '<div class="notice notice-success is-dismissible"><p>Bloques de publicidad guardados correctamente.</p></div>';
    }

    $editor_settings = array(
        'media_buttons' => true,
        'textarea_rows' => 8,
    );
    ?>
    <div class="wrap">
        <h1 class="wp-heading-inline">Publicidad Listado Top 100</h1>
        <p>Configura las 7 posiciones rotativas de publicidad que se intercalarán en el listado visual.</p>

        <form method="post" action="">
            <table class="form-table" role="presentation">
                <tbody>
                    <?php for ($i = 1; $i <= 7; $i++):
                        $tipo = get_option("voc_top100_ad_{$i}_type", 'libre');
                        $ad = get_option("voc_top100_ad_{$i}", '');
                        $txt = esc_attr(get_option("voc_top100_ad_{$i}_text", ''));
                        $logo = esc_attr(get_option("voc_top100_ad_{$i}_logo", ''));
                        $link = esc_attr(get_option("voc_top100_ad_{$i}_link", ''));
                        ?>
                        <tr>
                            <th scope="row" style="vertical-align: top; padding-top: 15px;">Posición <?php echo $i; ?></th>
                            <td style="background: #fff; padding: 15px; border: 1px solid #ccc;">
                                <select name="voc_top100_ad_<?php echo $i; ?>_type" class="ad-type-select"
                                    data-id="<?php echo $i; ?>" style="margin-bottom: 15px;">
                                    <option value="libre" <?php selected($tipo, 'libre'); ?>>Editor WYSIWYG</option>
                                    <option value="wpbakery" <?php selected($tipo, 'wpbakery'); ?>>Bloque WPBakery</option>
                                </select>

                                <div id="ad-libre-<?php echo $i; ?>" class="ad-group-<?php echo $i; ?>"
                                    style="<?php echo $tipo === 'libre' ? '' : 'display:none;'; ?>">
                                    <?php wp_editor($ad, "voc_top100_ad_{$i}", $editor_settings); ?>
                                </div>

                                <div id="ad-wpbakery-<?php echo $i; ?>" class="ad-group-<?php echo $i; ?>"
                                    style="<?php echo $tipo === 'wpbakery' ? '' : 'display:none;'; ?>">
                                    <p>
                                        <label><strong>Seleccionar Anuncio diseñado con WPBakery:</strong></label><br>
                                        <?php
                                        $selected_post = get_option("voc_top100_ad_{$i}_post_id", 0);
                                        $ads_posts = get_posts(array(
                                            'post_type' => 'voc_anuncio',
                                            'post_status' => 'publish',
                                            'numberposts' => -1,
                                            'orderby' => 'title',
                                            'order' => 'ASC'
                                        ));
                                        ?>
                                        <select name="voc_top100_ad_<?php echo $i; ?>_post_id" class="regular-text">
                                            <option value="0">-- Selecciona un anuncio --</option>
                                            <?php foreach ($ads_posts as $ad_post): ?>
                                                <option value="<?php echo $ad_post->ID; ?>" <?php selected($selected_post, $ad_post->ID); ?>>
                                                    <?php echo esc_html($ad_post->post_title); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </p>
                                </div>

                                <p>
                                    <label><strong>Enlace del anuncio (Opcional):</strong></label><br>
                                    <input type="text" name="voc_top100_ad_<?php echo $i; ?>_link" value="<?php echo $link; ?>"
                                        class="large-text" placeholder="https://...">
                                </p>

                            </td>
                        </tr>
                    <?php endfor; ?>
                </tbody>
            </table>
            <?php submit_button('Guardar Publicidad'); ?>
        </form>
    </div>

    <script>
        jQuery(document).ready(function ($) {
            // Cambiar entre vistas
            $('.ad-type-select').on('change', function () {
                var id = $(this).data('id');
                var val = $(this).val();
                $('.ad-group-' + id).hide();
                $('#ad-' + val + '-' + id).show();
            });

            // WP Media Uploader
            var file_frame;
            var current_target_input;
            $('.upload-logo-btn').on('click', function (e) {
                e.preventDefault();
                current_target_input = $($(this).data('target'));

                if (file_frame) {
                    file_frame.open();
                    return;
                }
                file_frame = wp.media.frames.file_frame = wp.media({
                    title: 'Seleccionar Logo de Patrocinador',
                    button: { text: 'Usar este Logo' },
                    multiple: false
                });
                file_frame.on('select', function () {
                    var attachment = file_frame.state().get('selection').first().toJSON();
                    if (current_target_input) {
                        current_target_input.val(attachment.url);
                    }
                });
                file_frame.open();
            });
        });
    </script>
    <?php
}
