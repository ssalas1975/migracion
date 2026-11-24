<?php

add_action('admin_menu', 'myprefix_adjust_the_wp_menu', 999);
function myprefix_adjust_the_wp_menu()
{
    // $page = remove_submenu_page('edit.php', 'post-new.php');
    //or for custom post type 'myposttype'.
    $page = remove_submenu_page('edit.php?post_type=candidato', 'post-new.php?post_type=candidato');
}


add_action('admin_menu', 'candidato_register_ref_page');
function candidato_register_ref_page()
{
    // add_submenu_page(
    //     'options-general.php',
    //     __('Configuración', 'textdomain'),
    //     __('Configuración', 'textdomain'),
    //     'manage_options',
    //     'candidatos-configuracion',
    //     'candidatos_configuracion'
    // );
    add_submenu_page(
        'options-general.php',
        __('Detalle Candidato 2', 'textdomain'),
        __('Detalle Candidato 2', 'textdomain'),
        'manage_options',
        'candidatos-detalle',
        'candidatos_detalle'
    );
    add_submenu_page(
        'edit.php?post_type=candidato',
        __('Candidatos Top 100', 'textdomain'),
        __('Candidatos Top 100', 'textdomain'),
        'manage_options',
        'candidatos',
        'candidatos'
    );
    add_submenu_page(
        'edit.php?post_type=candidato',
        __('Candidatos Pymes Familias', 'textdomain'),
        __('Candidatos Pymes Familias', 'textdomain'),
        'manage_options',
        'candidatospymesfamilias',
        'candidatospymesfamilias'
    );
    add_submenu_page(
        'edit.php?post_type=candidato',
        __('Top 100', 'textdomain'),
        '<span id="primeraRonda" style="color: #c4c4c4; padding-left: 0px; font-size: 11px;"> · Top 100</span>',
        'manage_options',
        'descartadossecretaria',
        'descartados_secretaria'
    );
    add_submenu_page(
        'edit.php?post_type=candidato',
        __('Top 100 Pymes Familiares', 'textdomain'),
        '<span id="primeraRonda" style="color: #c4c4c4; padding-left: 0px; font-size: 11px;"> · Top 100 Pymes Familiares</span>',
        'manage_options',
        'descartadossecretariafamiliares',
        'descartados_secretaria_familiares'
    );
    add_submenu_page(
        'edit.php?post_type=candidato',
        __('Nominadores', 'textdomain'),
        __('Nominadores', 'textdomain'),
        'manage_options',
        'nominadores',
        'nominadores'
    );
    add_submenu_page(
        'edit.php?post_type=candidato',
        __('Nominadores Pymes Familiares', 'textdomain'),
        __('Nominadores Pymes Familiares', 'textdomain'),
        'manage_options',
        'nominadorespymesfamiliares',
        'nominadores_pymes_familiares'
    );
    // add_submenu_page(
    //     'edit.php?post_type=candidato',
    //     __('Descartados prejurado', 'textdomain'),
    //     '<span id="primeraRonda" style="color: #c4c4c4; padding-left: 0px; font-size: 11px;"> · Descartados prejurado</span>',
    //     'manage_options',
    //     'descartados-prejurado',
    //     'descartados_prejurado'
    // );
    // add_submenu_page(
    //     'edit.php?post_type=candidato',
    //     __('Ronda finalistas', 'textdomain'),
    //     __('Ronda finalistas', 'textdomain'),
    //     'manage_options',
    //     'ronda-finalistas',
    //     'ronda_finalistas'
    // );
}

function candidatos_configuracion()
{
    if (isset($_POST['submit'])) {
        $idPage_td = $_POST['slug_page_candidatos_td'];
        $idPage_cc = $_POST['slug_page_candidatos_cc'];
        $idPage_ps = $_POST['slug_page_candidatos_ps'];
        update_option('slugCandidatePage_td', $idPage_td);
        update_option('slugCandidatePage_cc', $idPage_cc);
        update_option('slugCandidatePage_ps', $idPage_ps);
        $idPageDetalleCandidato = $_POST['slug_page_detalle_candidatos'];
        update_option('slugDetallCandidato', $idPageDetalleCandidato);
    }
    $pages = get_pages(); ?>
    <form method="post">
        <h2 class="title">Seleccionar Páginas para los listados y detalles de candidatos</h2>
        <table class="form-table" role="presentation">
            <tbody>
                <tr>
                    <th scope="row"><label for="mailserver_url">Página listado Trayectoria Destacada</label></th>
                    <td>
                        <select name="slug_page_candidatos_td" id="slug_page_candidatos" class="postform">
                            <?php
                            foreach ($pages as $page) {
                                echo (get_option('slugCandidatePage_td') == $page->post_name) ? '<option class="level-0" value="' . $page->post_name . '" selected=selected >' . $page->post_title . '</option>' : '<option class="level-0" value="' . $page->post_name . '">' . $page->post_title . '</option>';
                            } ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="mailserver_url">Página listado Compromiso Comunitario</label></th>
                    <td>
                        <select name="slug_page_candidatos_cc" id="slug_page_candidatos" class="postform">
                            <?php
                            foreach ($pages as $page) {
                                echo (get_option('slugCandidatePage_cc') == $page->post_name) ? '<option class="level-0" value="' . $page->post_name . '" selected=selected >' . $page->post_title . '</option>' : '<option class="level-0" value="' . $page->post_name . '">' . $page->post_title . '</option>';
                            } ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="mailserver_url">Página listado Proyecto Singular</label></th>
                    <td>
                        <select name="slug_page_candidatos_ps" id="slug_page_candidatos" class="postform">
                            <?php
                            foreach ($pages as $page) {
                                echo (get_option('slugCandidatePage_ps') == $page->post_name) ? '<option class="level-0" value="' . $page->post_name . '" selected=selected >' . $page->post_title . '</option>' : '<option class="level-0" value="' . $page->post_name . '">' . $page->post_title . '</option>';
                            } ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="mailserver_url">Página detalle candidatos</label></th>
                    <td>
                        <select name="slug_page_detalle_candidatos" id="slug_page_detalle_candidatos" class="postform">
                            <?php
                            foreach ($pages as $page) {
                                echo (get_option('slugDetallCandidato') == $page->post_name) ? '<option class="level-0" value="' . $page->post_name . '" selected=selected >' . $page->post_title . '</option>' : '<option class="level-0" value="' . $page->post_name . '">' . $page->post_title . '</option>';
                            } ?>
                        </select>
                    </td>
                </tr>
            </tbody>
        </table>
        <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Guardar"></p>
    </form>
    <?php
}


function candidaturas_activas()
{
    $args = array(
        'post_type' => 'candidato',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'orderby' => 'title',
        'order' => 'ASC',
        'meta_query' => array(
            'relation' => 'OR',
            array(
                'key' => 'ronda',
                'value' => 0,
            ),
        ),
    );
    $loop = new WP_Query($args);

    require_once(__DIR__ . '/views/candidaturasactivas.php');
}

function candidatos()
{
    $args = array(
        'post_type' => 'candidato',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'orderby' => 'title',
        'order' => 'ASC',
        'meta_query' => array(
            'relation' => 'AND',
            array(
                'key' => 'tipo_solicitud',
                'value' => 0,
            ),
            array(
                'key' => 'ronda',
                'value' => 0,
            ),
            array(
                 'key' => 'tipo_concurso',
                 'compare' => 'NOT EXISTS' // doesn't work
                ),
        ),
    );
    $loop = new WP_Query($args);

    require_once(__DIR__ . '/views/candidaturasactivas.php');
}

function candidatospymesfamilias()
{
    $args = array(
        'post_type' => 'candidato',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'orderby' => 'title',
        'order' => 'ASC',
        'meta_query' => array(
            'relation' => 'AND',
            array(
                'key' => 'tipo_solicitud',
                'value' => 0,
            ),
            array(
                'key' => 'ronda',
                'value' => 0,
            ),
            array(
              'key' => 'tipo_concurso',
              'value' => 'pymesfamiliares',
            )
        ),
    );
    $loop = new WP_Query($args);

    require_once(__DIR__ . '/views/candidaturasactivas.php');
}


function nominadores()
{
    $args = array(
        'post_type' => 'candidato',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'orderby' => 'title',
        'order' => 'ASC',
        'meta_query' => array(
            'relation' => 'AND',
            array(
                'key' => 'tipo_solicitud',
                'value' => 1,
            ),
            array(
                'key' => 'ronda',
                'value' => 0,
            ),
            array(
                 'key' => 'tipo_concurso',
                 'compare' => 'NOT EXISTS' // doesn't work
                ),
        ),
    );
    $loop = new WP_Query($args);

    require_once(__DIR__ . '/views/candidaturasactivas.php');
}

function nominadores_pymes_familiares()
{
    $args = array(
        'post_type' => 'candidato',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'orderby' => 'title',
        'order' => 'ASC',
        'meta_query' => array(
            'relation' => 'AND',
            array(
                'key' => 'tipo_solicitud',
                'value' => 1,
            ),
            array(
                'key' => 'ronda',
                'value' => 0,
            ),
            array(
              'key' => 'tipo_concurso',
              'value' => 'pymesfamiliares',
            )
        ),
    );
    $loop = new WP_Query($args);

    require_once(__DIR__ . '/views/candidaturasactivas.php');
}


function descartados_secretaria()
{
    $args = array(
        'post_type' => 'candidato',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'orderby' => 'title',
        'order' => 'ASC',
        'meta_query' => array(
            'relation' => 'AND',
            array(
                'key' => 'tipo_solicitud',
                'value' => 0,
            ),
            array(
                'key' => 'ronda',
                'value' => 1,
            ),
            array(
                 'key' => 'tipo_concurso',
                 'compare' => 'NOT EXISTS' // doesn't work
                ),
        ),
    );
    $loop = new WP_Query($args);
    require_once(__DIR__ . '/views/descartadossecretaria.php');
}

function descartados_secretaria_familiares()
{
    $args = array(
        'post_type' => 'candidato',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'orderby' => 'title',
        'order' => 'ASC',
        'meta_query' => array(
            'relation' => 'AND',
            array(
                'key' => 'tipo_solicitud',
                'value' => 0,
            ),
            array(
                'key' => 'ronda',
                'value' => 1,
            ),
            array(
              'key' => 'tipo_concurso',
              'value' => 'pymesfamiliares',
            )
        ),
    );
    $loop = new WP_Query($args);
    require_once(__DIR__ . '/views/descartadossecretaria.php');
}


function ronda_prejurado()
{
    $args = array(
        'post_type' => 'candidato',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'orderby' => 'title',
        'order' => 'ASC',
        'meta_query' => array(
            'relation' => 'OR',
            array(
                'key' => 'ronda',
                'value' => 2,
            ),
        ),
    );
    $loop = new WP_Query($args);
    require_once(__DIR__ . '/views/rondaprejurado.php');
}


function descartados_prejurado()
{
    $args = array(
        'post_type' => 'candidato',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'orderby' => 'title',
        'order' => 'ASC',
        'meta_query' => array(
            'relation' => 'OR',
            array(
                'key' => 'ronda',
                'value' => 3,
            ),
        ),
    );
    $loop = new WP_Query($args);
    require_once(__DIR__ . '/views/descartadosprejurados.php');
}


function ronda_finalistas()
{
    $args = array(
        'post_type' => 'candidato',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'orderby' => 'title',
        'order' => 'ASC',
        'meta_query' => array(
            'relation' => 'OR',
            array(
                'key' => 'ronda',
                'value' => 4,
            ),
        ),
    );
    $loop = new WP_Query($args);
    require_once(__DIR__ . '/views/rondafinalistas.php');
}


function candidatos_detalle()
{
    $idCandidato = $_GET['candidatoid'];
    $data = get_post_meta($idCandidato, 'candidato_metadata', true);

    $tipo_concurso = isset($data['tipo_concurso']) ? $data['tipo_concurso'] : get_post_meta($idCandidato, 'tipo_concurso', true);
    $tipo_concurso = sanitize_key($tipo_concurso);
    $es_empresa_familiar = ($tipo_concurso === 'pymesfamiliares');
    $foto_familia = isset($data['foto_familia']) ? $data['foto_familia'] : '';
    $nombre_comercial_empresa = isset($data['nombre_comercial_empresa']) ? $data['nombre_comercial_empresa'] : (isset($data['empresa_candidato']) ? $data['empresa_candidato'] : '');
    $family_fields = array(
        'nombre_comercial_empresa', 'cif', 'sector_actividad', 'provincia_principal',
        'anio_fundacion', 'generacion_familiar_actual', 'linkedin_empresa', 'web_empresa',
        'nombre_persona_contacto', 'cargo_persona_contacto', 'email_empresa_nominada',
        'telefono_empresa_nominada', 'relacion_con_nominado', 'motivo_nominacion',
        'sector_actividad_nominador', 'provincia_principal_nominador',
        'porcentaje_ventas', 'dato_cerrado_ventas_2025', 'porcentaje_ebita',
        'dato_cerrado_ebita_2025', 'porcentaje_empleo_neto',
        'dato_cerrado_empleo_neto_2025', 'meritos_endeudamiento', 'logo_empresa',
        'nombre_nominador', 'apellidos_nominador', 'email_nominador',
        'telefono_nominador'
    );
    foreach ($family_fields as $field) {
        ${$field} = isset($data[$field]) ? $data[$field] : '';
    }
    $nombre_comercial_empresa = !empty($data['nombre_comercial_empresa'])
        ? $data['nombre_comercial_empresa']
        : (isset($data['empresa_candidato']) ? $data['empresa_candidato'] : '');
    if (is_array($nombre_comercial_empresa)) {
        $nombre_comercial_empresa = array_values(array_filter($nombre_comercial_empresa, 'is_scalar'));
        $nombre_comercial_empresa = reset($nombre_comercial_empresa);
    }

    $tipo_solicitud = isset($data["tipo_solicitud"]) ? $data["tipo_solicitud"] : '0';
    $nombre_candidato = $data["nombre_candidato"];
    $apellidos_candidato = $data["apellidos_candidato"];
    $cargo_candidato = $data["cargo_candidato"];
    $empresa_candidato = $data["empresa_candidato"];
    $email_candidato = $data["email_candidato"];
    $telefono_candidato = $data["telefono_candidato"];
    $linkedin_candidato = $data["linkedin_candidato"];
    $web_empresa_candidato = $data["web_empresa_candidato"];
    $meritos_vision = $data["meritos_vision"];
    $meritos_liderazgo = $data["meritos_liderazgo"];
    $meritos_impacto = $data["meritos_impacto"];
    $meritos_endeudamiento = isset($data["meritos_endeudamiento"]) ? $data["meritos_endeudamiento"] : '';
    $inc_facturacion = $data["inc_facturacion"];
    $inc_resultados = $data["inc_resultados"];
    $inc_plantilla = $data["inc_plantilla"];
    $nombre_nominador = $data["nombre_nominador"];
    $apellidos_nominador = $data["apellidos_nominador"];
    $cargo_nominador = $data["cargo_nominador"];
    $empresa_nominador = $data["empresa_nominador"];
    $email_nominador = $data["email_nominador"];
    $telefono_nominador = $data["telefono_nominador"];
    $linkedin_nominador = $data["linkedin_nominador"];
    $web_empresa_nominador = $data["web_empresa_nominador"];

    $foto_directivo = $data['foto_directivo'];
    $logo_empresa = $data['logo_empresa'];


    if (isset($_POST['actualizarcandidato'])) {

        if ($es_empresa_familiar) {
            foreach ($family_fields as $field) {
                if (isset($_POST[$field])) {
                    $data[$field] = sanitize_textarea_field(wp_unslash($_POST[$field]));
                }
            }
            update_post_meta($idCandidato, 'candidato_metadata', $data);
            header("Refresh:0");
            exit;
        }

        if (isset($_POST['tipo_solicitud'])) {
            $data["tipo_solicitud"] = $_POST['tipo_solicitud'];
        }
        if (isset($_POST['nombre_candidato'])) {
            $data["nombre_candidato"] = $_POST['nombre_candidato'];
        }
        if (isset($_POST['apellidos_candidato'])) {
            $data["apellidos_candidato"] = $_POST['apellidos_candidato'];
        }
        if (isset($_POST['cargo_candidato'])) {
            $data["cargo_candidato"] = $_POST['cargo_candidato'];
        }
        if (isset($_POST['empresa_candidato'])) {
            $data["empresa_candidato"] = $_POST['empresa_candidato'];
        }
        if (isset($_POST['email_candidato'])) {
            $data["email_candidato"] = $_POST['email_candidato'];
        }
        if (isset($_POST['telefono_candidato'])) {
            $data["telefono_candidato"] = $_POST['telefono_candidato'];
        }
        if (isset($_POST['linkedin_candidato'])) {
            $data["linkedin_candidato"] = $_POST['linkedin_candidato'];
        }
        if (isset($_POST['web_empresa_candidato'])) {
            $data["web_empresa_candidato"] = $_POST['web_empresa_candidato'];
        }
        if (isset($_POST['meritos_vision'])) {
            $data["meritos_vision"] = $_POST['meritos_vision'];
        }
        if (isset($_POST['meritos_liderazgo'])) {
            $data["meritos_liderazgo"] = $_POST['meritos_liderazgo'];
        }
        if (isset($_POST['meritos_impacto'])) {
            $data["meritos_impacto"] = $_POST['meritos_impacto'];
        }
        if (isset($_POST['meritos_endeudamiento'])) {
            $data["meritos_endeudamiento"] = $_POST['meritos_endeudamiento'];
        }
        if (isset($_POST['inc_facturacion'])) {
            $data["inc_facturacion"] = $_POST['inc_facturacion'];
        }
        if (isset($_POST['inc_resultados'])) {
            $data["inc_resultados"] = $_POST['inc_resultados'];
        }
        if (isset($_POST['inc_plantilla'])) {
            $data["inc_plantilla"] = $_POST['inc_plantilla'];
        }
        if (isset($_POST['nombre_nominador'])) {
            $data["nombre_nominador"] = $_POST['nombre_nominador'];
        }
        if (isset($_POST['apellidos_nominador'])) {
            $data["apellidos_nominador"] = $_POST['apellidos_nominador'];
        }
        if (isset($_POST['cargo_nominador'])) {
            $data["cargo_nominador"] = $_POST['cargo_nominador'];
        }
        if (isset($_POST['empresa_nominador'])) {
            $data["empresa_nominador"] = $_POST['empresa_nominador'];
        }
        if (isset($_POST['email_nominador'])) {
            $data["email_nominador"] = $_POST['email_nominador'];
        }
        if (isset($_POST['telefono_nominador'])) {
            $data["telefono_nominador"] = $_POST['telefono_nominador'];
        }
        if (isset($_POST['linkedin_nominador'])) {
            $data["linkedin_nominador"] = $_POST['linkedin_nominador'];
        }
        if (isset($_POST['web_empresa_nominador'])) {
            $data["web_empresa_nominador"] = $_POST['web_empresa_nominador'];
        }
        if(isset($_POST['subirficheros'])) {
            $data['foto_directivo'] = $_POST['foto_directivo'];
        }

        update_post_meta($idCandidato, 'candidato_metadata', $data);
        header("Refresh:0");

    }



    if ($es_empresa_familiar && isset($_POST['subirficheros'])) {
        require_once(ABSPATH . 'wp-admin/includes/file.php');

        foreach (array('foto_familia', 'logo_empresa') as $file_field) {
            if (empty($_FILES[$file_field]['name'])) {
                continue;
            }

            $upload = wp_handle_upload($_FILES[$file_field], array('test_form' => false));
            if (empty($upload['error']) && !empty($upload['url'])) {
                $data[$file_field] = esc_url_raw($upload['url']);
            }
        }

        if (!empty($data['foto_familia']) || !empty($data['logo_empresa'])) {
            update_post_meta($idCandidato, 'candidato_metadata', $data);
        }

        header('Refresh:0');
        exit;
    }

    if (!$es_empresa_familiar && isset($_POST['subirficheros'])) {

        $newsUrls = [];

        if (!empty($_FILES['upload_attachment']['name'][0])) {

            require_once(ABSPATH . 'wp-admin/includes/image.php');
            require_once(ABSPATH . 'wp-admin/includes/file.php');
            require_once(ABSPATH . 'wp-admin/includes/media.php');


            $files = $_FILES['upload_attachment'];
            $count = 0;
            $galleryImages = array();


            foreach ($files['name'] as $count => $value) {

                if ($files['name'][$count]) {

                    $file = array(
                        'name' => $files['name'][$count],
                        'type' => $files['type'][$count],
                        'tmp_name' => $files['tmp_name'][$count],
                        'error' => $files['error'][$count],
                        'size' => $files['size'][$count]
                    );

                    $upload_overrides = array('test_form' => false);
                    $upload = wp_handle_upload($file, $upload_overrides);


                    // $filename should be the path to a file in the upload directory.
                    $filename = $upload['file'];

                    // The ID of the post this attachment is for.
                    $parent_post_id = $post_id;

                    // Check the type of tile. We'll use this as the 'post_mime_type'.
                    $filetype = wp_check_filetype(basename($filename), null);

                    // Get the path to the upload directory.
                    $wp_upload_dir = wp_upload_dir();

                    // Prepare an array of post data for the attachment.
                    $attachment = array(
                        'guid' => $wp_upload_dir['url'] . '/' . basename($filename),
                        'post_mime_type' => $filetype['type'],
                        'post_title' => preg_replace('/\.[^.]+$/', '', basename($filename)),
                        'post_content' => '',
                        'post_status' => 'inherit'
                    );

                    array_push($newsUrls, $attachment['guid']);

                    // Insert the attachment.
                    $attach_id = wp_insert_attachment($attachment, $filename, $parent_post_id);
                    $upload_file_cat1 = array_merge($upload_file_cat1, $newsUrls);
                    $url = wp_get_attachment_image_url($attach_id, 'full');
                    $data['foto_directivo'] = $url;
                    update_post_meta($idCandidato, 'candidato_metadata', $data);

                    header("Refresh:0");

                }

                $count++;

            }


        }


    }




    ?>
    <style>
        img.sh-standard-logo {
            max-width: 300px;
        }

        .sh-section-14e6bc0776f1c94bef259dfed42dec4d {
            padding: 50px 0px 50px 0px;
        }

        .sh-section-overlay-14e6bc0776f1c94bef259dfed42dec4d {
            z-index: -1000;
            background-position: absolute;
            top: 0;
            bottom: 0;
            left: 0;
            right: 0;
        }

        .sh-section-overlay-front {
            z-index: 0;
        }

        .sh-column-9dae4ec81245eb9e327ba31ecc60f696 {
            padding: 0px 30px 0px 15px;
            background-position: center center;
        }

        @media (max-width: 800px) {
            .sh-column-9dae4ec81245eb9e327ba31ecc60f696 .sh-column-wrapper {
                margin-left: auto;
                margin-right: auto;
            }
        }

        #text-block-752304fecac30efb79218b85205ca41d {
            margin: 0px 0px 15px 0px;
        }

        #text-block-752304fecac30efb79218b85205ca41d .drop-cap {
            font-weight: bold;
            font-size: 50px;
            display: block;
            float: left;
            margin: 8px 10px 0 0;
        }

        .sh-column-60871d11e9728c8259314d72a2264041 {
            padding: 30px 30px 30px 30px;
            margin: 0px px 0px px;
            background-color: #f7f7f7;
            border-left: 1px solid #d8d6ce;
            border-right: 1px solid #d8d6ce;
            border-top: 1px solid #d8d6ce;
            border-bottom: 1px solid #d8d6ce;
            background-position: center center;
        }

        @media (max-width: 800px) {
            .sh-column-60871d11e9728c8259314d72a2264041 {
                padding: 20px 20px 20px 20px;
            }
        }

        @media (max-width: 800px) {
            .sh-column-60871d11e9728c8259314d72a2264041 .sh-column-wrapper {
                margin-left: auto;
                margin-right: auto;
            }
        }

        @media (max-width: 800px) {
            .sh-column-60871d11e9728c8259314d72a2264041 {}
        }

        #heading-724795aea974582be083b6f12ec9f949 {
            margin: 0px 0px 15px 0px;
        }

        #heading-724795aea974582be083b6f12ec9f949 .sh-heading-content {
            font-weight: 700 !important;
            color: #bf1717;
        }

        #heading-724795aea974582be083b6f12ec9f949 .sh-heading-content,
        #heading-724795aea974582be083b6f12ec9f949 .sh-heading-additional-text {}

        #divider-21007d757d9a460cd741f85ff224756f {
            margin: 25px 0px 25px 0px !important;
        }

        #divider-21007d757d9a460cd741f85ff224756f.sh-divider-content-none .sh-divider-line {
            border-top-width: 1px;
            border-top-color: #d8d6ce;
            border-top-style: solid;
        }

        #empty-space-154b59fb5104f19e7acb2f401c93f8ff {
            height: 20px;
        }

        #button-a0e4a49cd2a8af9de60d98ff60b3c9b5 .sh-button {
            background-color: #bf1717;
            color: #ffffff;
        }

        #button-a0e4a49cd2a8af9de60d98ff60b3c9b5 {
            margin: 0px 0px 0px 0px;
        }
        #button-a0e4a49cd2a8af9de60d98ff60b3c9b5:not(.sh-button-style-2) .sh-button:hover {}

        #button-a0e4a49cd2a8af9de60d98ff60b3c9b5.sh-button-style-2 .sh-button:after {}

        #button-a0e4a49cd2a8af9de60d98ff60b3c9b5.sh-button-style-2 .sh-button:hover {}

        #divider-aced65c20151be9648400223d7b26874 {
            margin: 25px 0px 25px 0px !important;
        }

        #divider-aced65c20151be9648400223d7b26874.sh-divider-content-none .sh-divider-line {
            border-top-width: 1px;
            border-top-color: #d8d6ce;
            border-top-style: solid;
        }

        #heading-a9871d588a8ef29f3b30b78f07a90e2e {
            margin: 0px 0px 15px 0px;
        }

        #heading-a9871d588a8ef29f3b30b78f07a90e2e .sh-heading-content {
            font-weight: 700 !important;
            color: #bf1717;
        }

        #heading-a9871d588a8ef29f3b30b78f07a90e2e .sh-heading-content,
        #heading-a9871d588a8ef29f3b30b78f07a90e2e .sh-heading-additional-text {}

        #divider-461a1c6f6bd592cdc88fa2b6b6f12251 {
            margin: 25px 0px 25px 0px !important;
        }

        #divider-461a1c6f6bd592cdc88fa2b6b6f12251.sh-divider-content-none .sh-divider-line {
            border-top-width: 1px;
            border-top-color: #d8d6ce;
            border-top-style: solid;
        }

        #empty-space-1faaa15572ffe2865ac8ccc3f2e4681b {
            height: 30px;
        }

        #heading-a7650996df19bf4a6a531ae1afe95e9f {
            margin: 0px 0px 15px 0px;
        }

        #heading-a7650996df19bf4a6a531ae1afe95e9f .sh-heading-content {
            font-weight: 700 !important;
            color: #bf1717;
        }

        #heading-a7650996df19bf4a6a531ae1afe95e9f .sh-heading-content,
        #heading-a7650996df19bf4a6a531ae1afe95e9f .sh-heading-additional-text {}

        #divider-76767fe041a00c23970936184986d47f {
            margin: 25px 0px 25px 0px !important;
        }

        #divider-76767fe041a00c23970936184986d47f.sh-divider-content-none .sh-divider-line {
            border-top-width: 1px;
            border-top-color: #d8d6ce;
            border-top-style: solid;
        }

        .sh-section-49239f9fc68e5937068fb0bc0847eec5 {
            padding: 0px 0px 0px 0px;
        }

        .sh-section-overlay-49239f9fc68e5937068fb0bc0847eec5 {
            z-index: -1000;
            background: #ffffff;
            position: absolute;
            top: 0;
            bottom: 0;
            left: 0;
            right: 0;
        }

        .sh-section-overlay-front {
            z-index: 0;
        }

        .sh-column-68912b23ce7b437eb73344b462c5cab8 {
            padding: 0px 0px 0px 0px;
            background-position: center center;
        }

        @media (max-width: 800px) {
            .sh-column-68912b23ce7b437eb73344b462c5cab8 .sh-column-wrapper {
                margin-left: auto;
                margin-right: auto;
            }
        }

        #divider-a51fc6bcf1be62fef802a0f6cff67e79 {
            margin: 0px 0px 0px 0px !important;
        }

        #divider-a51fc6bcf1be62fef802a0f6cff67e79.sh-divider-content-none .sh-divider-line {
            border-top-width: 1px;
            border-top-color: #888269;
            border-top-style: solid;
        }

        .sh-section-4efd962705615b8b1c3e1a2d59af1439 {
            padding: 40px 0px 20px 0px;
        }

        .sh-section-overlay-4efd962705615b8b1c3e1a2d59af1439 {
            z-index: -1000;
            background: #ffffff;
            position: absolute;
            top: 0;
            bottom: 0;
            left: 0;
            right: 0;
        }

        .sh-section-overlay-front {
            z-index: 0;
        }

        .sh-column-3a63afc1d9865b6d638e02696529eaac {
            padding: 0px 15px 15px 15px;
            background-position: center center;
        }

        @media (max-width: 800px) {
            .sh-column-3a63afc1d9865b6d638e02696529eaac .sh-column-wrapper {
                margin-left: auto;
                margin-right: auto;
            }
        }

        #heading-56aa8962ef7d54f55e0d9ec06cd6b1b1 {
            margin: 0px 0px 15px 0px;
        }

        #heading-56aa8962ef7d54f55e0d9ec06cd6b1b1 .sh-heading-content {
            font-weight: 400 !important;
            color: #888269;
        }

        #heading-56aa8962ef7d54f55e0d9ec06cd6b1b1 .sh-heading-content,
        #heading-56aa8962ef7d54f55e0d9ec06cd6b1b1 .sh-heading-additional-text {}

        .sh-column-1b2fb072a23d8bf6406f4b020f282ce0 {}

        @media (max-width: 800px) {
            .sh-column-1b2fb072a23d8bf6406f4b020f282ce0 .sh-column-wrapper {
                margin-left: auto;
                margin-right: auto;
            }
        }

        .sh-column-99eeb9ebbba5da4a5a75a9c9eb4a3daf {
            padding: 0px 50px 0px 50px;
            background-position: center center;
        }

        @media (max-width: 800px) {
            .sh-column-99eeb9ebbba5da4a5a75a9c9eb4a3daf .sh-column-wrapper {
                margin-left: auto;
                margin-right: auto;
            }
        }

        #single-image-7697333153aa5bfe5b4941c72b78ea82 {
            text-align: center;
        }

        .sh-column-f9359beae0c3daa0f61acbdd3c23fbaf {
            padding: 0px 50px 0px 50px;
            background-position: center center;
        }

        @media (max-width: 800px) {
            .sh-column-f9359beae0c3daa0f61acbdd3c23fbaf .sh-column-wrapper {
                margin-left: auto;
                margin-right: auto;
            }
        }

        #single-image-38e949acc16fed552fe68f3feac131f5 {
            text-align: center;
        }

        .sh-column-07ffafb379b494afcad446e0c8ad05ec {}

        @media (max-width: 800px) {
            .sh-column-07ffafb379b494afcad446e0c8ad05ec .sh-column-wrapper {
                margin-left: auto;
                margin-right: auto;
            }
        }

        .sh-column-cb6eb4f8d91b006323bb7ad27c4295fe {}

        @media (max-width: 800px) {
            .sh-column-cb6eb4f8d91b006323bb7ad27c4295fe .sh-column-wrapper {
                margin-left: auto;
                margin-right: auto;
            }
        }

        #empty-space-db88dfbd592c0dc4aa537dda486e3d9d {
            height: 30px;
        }

        #heading-04700e8e7ef2e565be04c050da4afcc9 {
            margin: 0px 0px 15px 0px;
        }

        #heading-04700e8e7ef2e565be04c050da4afcc9 .sh-heading-content {
            font-weight: 400 !important;
            color: #888269;
        }

        #heading-04700e8e7ef2e565be04c050da4afcc9 .sh-heading-content,
        #heading-04700e8e7ef2e565be04c050da4afcc9 .sh-heading-additional-text {}

        .sh-column-3bfd853b1de6b8a362ffffa19814ca03 {
            padding: 0px 35px 0px 35px;
            background-position: center center;
        }

        @media (max-width: 800px) {
            .sh-column-3bfd853b1de6b8a362ffffa19814ca03 .sh-column-wrapper {
                margin-left: auto;
                margin-right: auto;
            }
        }

        #single-image-7d66c912e7a5eabdefa66dc2ba25c711 {
            text-align: center;
        }

        .sh-column-19ac2c60fda39cbdb2c96888afe7ae2f {
            padding: 0px 35px 0px 35px;
            background-position: center center;
        }

        @media (max-width: 800px) {
            .sh-column-19ac2c60fda39cbdb2c96888afe7ae2f .sh-column-wrapper {
                margin-left: auto;
                margin-right: auto;
            }
        }

        #single-image-92d4f683075b45878568c69f4a900eb5 {
            text-align: center;
        }

        .sh-column-d71ca7af0525b399af1584dd8119d7a2 {
            padding: 0px 35px 0px 35px;
            background-position: center center;
        }

        @media (max-width: 800px) {
            .sh-column-d71ca7af0525b399af1584dd8119d7a2 .sh-column-wrapper {
                margin-left: auto;
                margin-right: auto;
            }
        }

        #single-image-d0940ac49cacbb9ac6046704175e1b53 {
            text-align: center;
        }

        .sh-column-1da24ad46dae09bb0ac1f86e5f10a664 {
            padding: 0px 35px 0px 35px;
            background-position: center center;
        }

        @media (max-width: 800px) {
            .sh-column-1da24ad46dae09bb0ac1f86e5f10a664 .sh-column-wrapper {
                margin-left: auto;
                margin-right: auto;
            }
        }

        #single-image-997b7417f9ac8f9a63bf035087b1fb19 {
            text-align: center;
        }
    </style>

    <div id="content" class="page-content <?php if (isset($layout_sidebar) && $layout_sidebar): ?>content-with-<?php echo esc_attr($layout_sidebar);
    endif; ?>">
        <div class="fw-row">

            <div class="sh-output-html">
                <?php if ($es_empresa_familiar): ?>
                <form action="" method="POST">
                    <?php if ($tipo_solicitud !== '1'): ?>
                    <h2>DATOS DE LA EMPRESA</h2>
                    <p>Nombre comercial: <input type="text" name="nombre_comercial_empresa" value="<?php echo esc_attr($nombre_comercial_empresa); ?>"></p>
                    <p>CIF: <input type="text" name="cif" value="<?php echo esc_attr($cif); ?>"></p>
                    <p>Sector de actividad: <input type="text" name="sector_actividad" value="<?php echo esc_attr($sector_actividad); ?>"></p>
                    <p>Provincia de la sede principal: <input type="text" name="provincia_principal" value="<?php echo esc_attr($provincia_principal); ?>"></p>
                    <p>Año de fundación: <input type="text" name="anio_fundacion" value="<?php echo esc_attr($anio_fundacion); ?>"></p>
                    <p>Generación familiar actual: <input type="text" name="generacion_familiar_actual" value="<?php echo esc_attr($generacion_familiar_actual); ?>"></p>
                    <p>Nombre del grupo y sociedades o filiales incluidas (Si se presenta como grupo empresarial): <textarea name="linkedin_empresa" rows="4"><?php echo esc_textarea($linkedin_empresa); ?></textarea></p>
                    <p>Web empresa: <input type="text" name="web_empresa" value="<?php echo esc_attr($web_empresa); ?>"></p>

                    <h2>DATOS DEL PORTAVOZ / RESPONSABLE FAMILIAR</h2>
                    <p>Nombre: <input type="text" name="nombre_candidato" value="<?php echo esc_attr($nombre_candidato); ?>"></p>
                    <p>Apellidos: <input type="text" name="apellidos_candidato" value="<?php echo esc_attr($apellidos_candidato); ?>"></p>
                    <p>Cargo: <input type="text" name="cargo_candidato" value="<?php echo esc_attr($cargo_candidato); ?>"></p>
                    <p>LinkedIn: <input type="text" name="linkedin_candidato" value="<?php echo esc_attr($linkedin_candidato); ?>"></p>
                    <p>Email: <input type="text" name="email_candidato" value="<?php echo esc_attr($email_candidato); ?>"></p>
                    <p>Teléfono: <input type="text" name="telefono_candidato" value="<?php echo esc_attr($telefono_candidato); ?>"></p>
                    <?php endif; ?>

                    <?php if ($tipo_solicitud === '1'): ?>
                    <h2>DATOS DE LA EMPRESA NOMINADA</h2>
                    <p>Nombre comercial de la empresa: <input type="text" name="nombre_comercial_empresa" value="<?php echo esc_attr($nombre_comercial_empresa); ?>"></p>
                    <p>Sector de actividad: <input type="text" name="sector_actividad_nominador" value="<?php echo esc_attr($sector_actividad_nominador); ?>"></p>
                    <p>Provincia de la sede principal: <input type="text" name="provincia_principal_nominador" value="<?php echo esc_attr($provincia_principal_nominador); ?>"></p>
                    <p>Motivo de la nominación:</p>
                    <p><textarea name="motivo_nominacion" rows="6" cols="60"><?php echo esc_textarea($motivo_nominacion); ?></textarea></p>

                    <h2>DATOS DE CONTACTO DE LA EMPRESA (SI SE TIENEN)</h2>
                    <p>Nombre de la persona de contacto: <input type="text" name="nombre_persona_contacto" value="<?php echo esc_attr($nombre_persona_contacto); ?>"></p>
                    <p>Cargo de la persona de contacto: <input type="text" name="cargo_persona_contacto" value="<?php echo esc_attr($cargo_persona_contacto); ?>"></p>
                    <p>Email de la empresa: <input type="text" name="email_empresa_nominada" value="<?php echo esc_attr($email_empresa_nominada); ?>"></p>
                    <p>Teléfono de contacto de la empresa: <input type="text" name="telefono_empresa_nominada" value="<?php echo esc_attr($telefono_empresa_nominada); ?>"></p>

                    <h2>DATOS DEL NOMINADOR</h2>
                    <p>Nombre: <input type="text" name="nombre_nominador" value="<?php echo esc_attr($nombre_nominador); ?>"></p>
                    <p>Apellidos: <input type="text" name="apellidos_nominador" value="<?php echo esc_attr($apellidos_nominador); ?>"></p>
                    <p>Email: <input type="text" name="email_nominador" value="<?php echo esc_attr($email_nominador); ?>"></p>
                    <p>Teléfono móvil: <input type="text" name="telefono_nominador" value="<?php echo esc_attr($telefono_nominador); ?>"></p>
                    <p>Relación con la empresa nominada: <input type="text" name="relacion_con_nominado" value="<?php echo esc_attr($relacion_con_nominado); ?>"></p>

                    <h2>DATOS ADICIONALES DE LA EMPRESA</h2>
                    <p>CIF: <input type="text" name="cif" value="<?php echo esc_attr($cif); ?>"></p>
                    <p>Sector de actividad: <input type="text" name="sector_actividad" value="<?php echo esc_attr($sector_actividad); ?>"></p>
                    <p>Provincia de la sede principal: <input type="text" name="provincia_principal" value="<?php echo esc_attr($provincia_principal); ?>"></p>
                    <p>Año de fundación: <input type="text" name="anio_fundacion" value="<?php echo esc_attr($anio_fundacion); ?>"></p>
                    <p>Generación familiar actual: <input type="text" name="generacion_familiar_actual" value="<?php echo esc_attr($generacion_familiar_actual); ?>"></p>
                    <p>Nombre del grupo y sociedades o filiales incluidas (Si se presenta como grupo empresarial): <textarea name="linkedin_empresa" rows="4"><?php echo esc_textarea($linkedin_empresa); ?></textarea></p>
                    <p>Web empresa: <input type="text" name="web_empresa" value="<?php echo esc_attr($web_empresa); ?>"></p>

                    <h2>DATOS DEL PORTAVOZ / RESPONSABLE FAMILIAR</h2>
                    <p>Nombre: <input type="text" name="nombre_candidato" value="<?php echo esc_attr($nombre_candidato); ?>"></p>
                    <p>Apellidos: <input type="text" name="apellidos_candidato" value="<?php echo esc_attr($apellidos_candidato); ?>"></p>
                    <p>Cargo: <input type="text" name="cargo_candidato" value="<?php echo esc_attr($cargo_candidato); ?>"></p>
                    <p>LinkedIn: <input type="text" name="linkedin_candidato" value="<?php echo esc_attr($linkedin_candidato); ?>"></p>
                    <p>Email: <input type="text" name="email_candidato" value="<?php echo esc_attr($email_candidato); ?>"></p>
                    <p>Teléfono: <input type="text" name="telefono_candidato" value="<?php echo esc_attr($telefono_candidato); ?>"></p>
                    <?php else: ?>
                    <h2>MEMORIA CUALITATIVA</h2>
                    <?php endif; ?>
                    <p><strong>1. GOBERNANZA Y SUCESIÓN</strong> (25% de la valoración)<br>Continuidad y madurez familiar</p>
                    <p><textarea name="meritos_liderazgo" rows="6" cols="60"><?php echo esc_textarea($meritos_liderazgo); ?></textarea></p>

                    <p><strong>2. INNOVACIÓN Y LEGADO</strong> (25% de la valoración)<br>Visión de futuro</p>
                    <p><textarea name="meritos_vision" rows="6" cols="60"><?php echo esc_textarea($meritos_vision); ?></textarea></p>

                    <p><strong>3. ARRAIGO E IMPACTO SOCIAL</strong> (25% de la valoración)<br>Compromiso con el entorno</p>
                    <p><textarea name="meritos_impacto" rows="6" cols="60"><?php echo esc_textarea($meritos_impacto); ?></textarea></p>

                    <p><strong>EVOLUCIÓN DEL ENDEUDAMIENTO Y OPERACIONES RELEVANTES (2023-2025)</strong></p>
                    <p><textarea name="meritos_endeudamiento" rows="6" cols="60"><?php echo esc_textarea($meritos_endeudamiento); ?></textarea></p>

                    <h2>MÉTRICAS DE IMPACTO CUANTITATIVO</h2>
                    <p>Porcentaje de ventas: <input type="text" name="porcentaje_ventas" value="<?php echo esc_attr($porcentaje_ventas); ?>"></p>
                    <p>Dato cerrado de ventas 2025: <input type="text" name="dato_cerrado_ventas_2025" value="<?php echo esc_attr($dato_cerrado_ventas_2025); ?>"></p>
                    <p>Porcentaje de EBITDA: <input type="text" name="porcentaje_ebita" value="<?php echo esc_attr($porcentaje_ebita); ?>"></p>
                    <p>Dato cerrado de EBITDA 2025: <input type="text" name="dato_cerrado_ebita_2025" value="<?php echo esc_attr($dato_cerrado_ebita_2025); ?>"></p>
                    <p>Porcentaje de empleo neto: <input type="text" name="porcentaje_empleo_neto" value="<?php echo esc_attr($porcentaje_empleo_neto); ?>"></p>
                    <p>Dato cerrado de empleo neto 2025: <input type="text" name="dato_cerrado_empleo_neto_2025" value="<?php echo esc_attr($dato_cerrado_empleo_neto_2025); ?>"></p>

                    <input type="submit" name="actualizarcandidato" value="Actualizar" />
                </form>
                <h2>MATERIAL GRÁFICO</h2>
                <?php if (!empty($foto_familia)): ?>
                    <p>Fotografía actual de la familia:</p>
                    <img alt="Foto de la familia" src="<?php echo esc_url($foto_familia); ?>" height="150" width="150" loading="lazy">
                <?php endif; ?>
                <form action="" method="POST" enctype="multipart/form-data">
                    <p>Fotografía de la familia:</p>
                    <p><input type="file" name="foto_familia" accept="image/*"></p>
                <?php if (!empty($logo_empresa)): ?>
                    <p>Logotipo actual de la empresa:</p>
                    <img alt="Logotipo de la empresa" src="<?php echo esc_url($logo_empresa); ?>" height="150" width="150" loading="lazy">
                <?php endif; ?>
                    <p>Logotipo de la empresa:</p>
                    <p><input type="file" name="logo_empresa" accept=".svg,.pdf,.ai,.eps,.png,.jpg,.jpeg"></p>
                    <input type="submit" name="subirficheros" value="Actualizar material gráfico">
                </form>
                <?php else: ?>
                <form action="" method="POST">
                    <h2>DATOS DEL CANDIDATO</h2>
                    <p>Nombre Candidato: <input type="text" name="nombre_candidato"
                            value="<?php echo $nombre_candidato; ?>"></p>
                    <p>Apellidos Candidato: <input type="text" name="apellidos_candidato"
                            value="<?php echo $apellidos_candidato; ?>"></p>
                    <p>Cargo: <input type="text" name="cargo_candidato" value="<?php echo $cargo_candidato; ?>"></p>
                    <p>Empresa: <input type="text" name="empresa_candidato" value="<?php echo $empresa_candidato; ?>"></p>
                    <p>email: <input type="text" name="email_candidato" value="<?php echo $email_candidato; ?>"></p>
                    <p>Teléfono de contacto: <input type="text" name="telefono_candidato"
                            value="<?php echo $telefono_candidato; ?>"></p>
                    <p>Linkedin: <input type="text" name="linkedin_candidato" value="<?php echo $linkedin_candidato; ?>">
                    </p>
                    <p>Web: <strong><?php echo $web_empresa_candidato; ?></strong></p>

                    <p>1. LIDERAZGO HUMANO: <textarea name="meritos_liderazgo" rows="4"
                            cols="50"><?php echo $meritos_liderazgo; ?></textarea></p>
                    <p>2. VISIÓN ESTRATÉGICA: <textarea name="meritos_vision" rows="4"
                            cols="50"><?php echo $meritos_vision; ?></textarea></p>
                    <p>3. IMPACTO REAL: <textarea name="meritos_impacto" rows="4"
                            cols="50"><?php echo $meritos_impacto; ?></textarea></p>
                    <h2>MÉTRICAS DE IMPACTO CUANTITATIVO</h2>
                    <p>Incremento de Facturación: <input type="text" name="inc_facturacion"
                            value="<?php echo $inc_facturacion; ?>"></p>
                    <p>Incremento de Resultados: <input type="text" name="inc_resultados"
                            value="<?php echo $inc_resultados; ?>"></p>
                    <p>Incremento de Plantilla: <input type="text" name="inc_plantilla"
                            value="<?php echo $inc_plantilla; ?>"></p>
                    <input type="submit" name="actualizarcandidato" value="Actualizar" />
                </form>
                <form action="" method="POST" enctype="multipart/form-data">
                    <h2>MATERIAL GRÁFICO</h2>
                    <p>Fotografía del directivo:</p>
                    <img alt="" src="<?php echo $foto_directivo; ?>" srcset="<?php echo $foto_directivo; ?>"
                        class="avatar avatar-96 photo" height="150" width="150" loading="lazy">

                    <div class="sh-heading" id="heading-724795aea974582be083b6f12ec9f949">
                        <h2 class="sh-heading-content size-default text-left">Subir Ficheros</h2>
                            <input type="file" name="upload_attachment[]" class="files" size="50" multiple="multiple" />
                            <?php wp_nonce_field('upload_attachment', 'my_image_upload_nonce'); ?>
                            <input type="submit" name="subirficheros" value="Subir" />
                    </div>

                    <p>Logotipo de la empresa:</p>
                    <img alt="" src="<?php echo $logo_empresa; ?>" srcset="<?php echo $logo_empresa; ?>"
                        class="avatar avatar-96 photo" height="150" width="150" loading="lazy">
                </form>

                <hr>
                <?php
                if ($tipo_solicitud == '1') {
                    ?>
                    <h2>DATOS DEL NOMINADOR</h2>
                    <p>Nombre Nominador: <strong><?php echo $nombre_nominador; ?></strong></p>
                    <p>Apellidos Nominador: <strong><?php echo $apellidos_nominador; ?></strong></p>
                    <p>Cargo Nominador: <strong><?php echo $cargo_nominador; ?></strong></p>
                    <p>Empresa Nominador: <strong><?php echo $empresa_nominador; ?></strong></p>
                    <p>email Nominador: <strong><?php echo $email_nominador; ?></strong></p>
                    <p>Teléfono de contacto Nominador: <strong><?php echo $telefono_nominador; ?></strong></p>
                    <p>Linkedin: <strong><?php echo $linkedin_nominador; ?></strong></p>
                    <p>Web: <strong><?php echo $web_empresa_nominador; ?></strong></p>
                    <?php
                }
                ?>

                <?php endif; ?>
            </div>


        </div>
    </div>

    <?php
}
