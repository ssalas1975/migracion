<?php
//comprobamos que el plugin del que depende esta activdo
include_once(ABSPATH . 'wp-admin/includes/plugin.php');

if (is_plugin_active('js_composer/js_composer.php')) {
    //incluyendo addons

    require_once(dirname(__FILE__) . '/addons/firmaRRSS.php');
    require_once(dirname(__FILE__) . '/addons/headerTipoUno.php');
    require_once(dirname(__FILE__) . '/addons/headerTipoDos.php');
    require_once(dirname(__FILE__) . '/addons/h2.php');
    require_once(dirname(__FILE__) . '/addons/h3.php');
    require_once(dirname(__FILE__) . '/addons/parrafo.php');
    require_once(dirname(__FILE__) . '/addons/citablockquote.php');
    require_once(dirname(__FILE__) . '/addons/listaHorizontal.php');
    //require_once( dirname(__FILE__) . '/addons/includeJavascript.php');
    //require_once( dirname(__FILE__) . '/addons/includeJavascriptGeneral.php');
    require_once(dirname(__FILE__) . '/addons/swipeSlider.php');
    require_once(dirname(__FILE__) . '/addons/audioPlayer.php');
    require_once(dirname(__FILE__) . '/addons/bloquePie.php');
    require_once(dirname(__FILE__) . '/addons/graficosChart.php');
    require_once(dirname(__FILE__) . '/addons/imagenBlockquote.php');
    require_once(dirname(__FILE__) . '/addons/fullImage.php');
    require_once(dirname(__FILE__) . '/addons/imagenIndividual.php');
    require_once(dirname(__FILE__) . '/addons/markedAnimated.php');
    require_once(dirname(__FILE__) . '/addons/masVistos.php');
    require_once(dirname(__FILE__) . '/addons/masVistosTags.php');
    require_once(dirname(__FILE__) . '/addons/masVistosTagsGeneral.php');
    require_once(dirname(__FILE__) . '/addons/moduloEmergente.php');
    require_once(dirname(__FILE__) . '/addons/videoBlockquote.php');
    require_once(dirname(__FILE__) . '/addons/videoSecundario.php');
    require_once(dirname(__FILE__) . '/addons/listaVerticalComienzo.php');
    require_once(dirname(__FILE__) . '/addons/listaVerticalItem.php');
    require_once(dirname(__FILE__) . '/addons/swipper/swipper-form.php');
    require_once(dirname(__FILE__) . '/addons/gallerymodal.php');
    require_once(dirname(__FILE__) . '/addons/topicmuseoabc.php');
    require_once(dirname(__FILE__) . '/addons/selectorportada.php');
    require_once(dirname(__FILE__) . '/addons/destacado_abc.php');
    require_once(dirname(__FILE__) . '/addons/destacado_a_bc.php');
    require_once(dirname(__FILE__) . '/addons/destacado_ab_c.php');
    require_once(dirname(__FILE__) . '/addons/destacados_adicionales.php');
    require_once(dirname(__FILE__) . '/addons/firmaRRSSwhitDate.php');


    //registrando addons
    vc_map(array(
        "name" => __("Vocento Firma RRSS"),
        'base' => 'vocento_firmaRRSS',
        "icon" => get_template_directory_uri() . "/includes/vocentoIcon.png",
        'category' => __('Vocento', 'js_composer'),
        'description' => __('Firma RRSS.', 'js_composer'),
        'params' => array(
            array(
                'type' => 'textfield',
                'heading' => __('Autor del post', 'js_composer'),
                'param_name' => 'autorfirma',
                'description' => __('Aparecerá por defecto el autor del post en caso de no rellenar', 'js_composer'),

            ),
             array(
                        'type' => 'attach_image',
                        'heading' => __('Imagen Autor', 'js_composer'),
                        'param_name' => 'image_url',
                        'value' => '',
                        'description' => __('En caso de no seleccionar aparecerá logo por defecto', 'js_composer')
                    ),
            array(
                'type' => 'textfield',
                'heading' => __('Nombre de la clase extra', 'js_composer'),
                'param_name' => 'el_class',
                'description' => __('Añada un nombre de clase CSS personalizado.', 'js_composer'),
            )
        ),
    ));


    vc_map(array(
        "name" => __("Vocento Header Tipo Uno"),
        'base' => 'header_uno',
        "icon" => get_template_directory_uri() . "/includes/vocentoIcon.png",
        'category' => __('Vocento', 'js_composer'),
        'description' => __('Header Tipo Uno.', 'js_composer'),
        'params' => array(
            array(
                "type" => "headertipouno",
                "class" => "",
                "param_name" => "headertipouno",
            ),
            array(
                'type' => 'textarea_html',
                'holder' => 'div',
                'heading' => __('Entradilla', 'js_composer'),
                'param_name' => 'content',
                'description' => __('Texto.', 'js_composer'),
            ),
            array(
                'type' => 'attach_image',
                'heading' => __('Imagen frontal Video (Opcional)', 'js_composer'),
                'param_name' => 'stillurlh1',
                'description' => __('Imagen que aparecerá antes de que el video se reproduzca', 'js_composer')
            ),
            array(
                'type' => 'textfield',
                'heading' => __('Nombre de la clase extra', 'js_composer'),
                'param_name' => 'el_class',
                'description' => __('Añada un nombre de clase CSS personalizado.', 'js_composer'),
            )
        ),

    ));



    vc_map(array(
        "name" => __("Vocento Header Tipo Dos"),
        'base' => 'header_dos',
        "icon" => get_template_directory_uri() . "/includes/vocentoIcon.png",
        'category' => __('Vocento', 'js_composer'),
        'description' => __('Header Tipo Dos.', 'js_composer'),
        'params' => array(
            array(
                "type" => "headertipodos",
                "class" => "",
                "param_name" => "headertipodos",
            ),
            array(
                'type' => 'textarea_html',
                'holder' => 'div',
                'heading' => __('Entradilla', 'js_composer'),
                'param_name' => 'content',
                'description' => __('Texto.', 'js_composer'),
            ),
             array(
                'type' => 'attach_image',
                'heading' => __('Imagen frontal Video (Opcional)', 'js_composer'),
                'param_name' => 'stillurlh2',
                'description' => __('Imagen que aparecerá antes de que el video se reproduzca', 'js_composer')
            ),
            array(
                'type' => 'textfield',
                'heading' => __('Nombre de la clase extra', 'js_composer'),
                'param_name' => 'el_class',
                'description' => __('Añada un nombre de clase CSS personalizado.', 'js_composer'),
            )
        ),

    ));
    /********* COMUNES ***************/
    function load_wp_media_files()
    {
        wp_enqueue_media();
    }
    add_action('admin_enqueue_scripts', 'load_wp_media_files');
    /********* COMUNES ***************/




    /******* CABECERA RESPONSIVE *******/

    /******* CABECERA RESPONSIVE *******/




    /******** H2 *******/
    vc_map(array(
        "name" => __("Vocento H2"),
        'base' => 'vocento_h2',
        "icon" => get_template_directory_uri() . "/includes/vocentoIcon.png",
        'category' => __('Vocento', 'js_composer'),
        'description' => __('Cabecera H2.', 'js_composer'),
        'params' => array(
            array(
                'type' => 'textfield',
                'heading' => __('Texto a mostrar', 'js_composer'),
                'param_name' => 'textoh2',
                'description' => __('Texto.', 'js_composer'),
            ),
            array(
                'type' => 'textfield',
                'heading' => __('Nombre de la clase extra', 'js_composer'),
                'param_name' => 'el_class',
                'description' => __('Añada un nombre de clase CSS personalizado.', 'js_composer'),
            )
        ),
    ));

    /******** H2 *******/



    /******* H3 *******/
    vc_map(array(
        "name" => __("Vocento H3"),
        'base' => 'vocento_h3',
        "icon" => get_template_directory_uri() . "/includes/vocentoIcon.png",
        'category' => __('Vocento', 'js_composer'),
        'description' => __('Cabecera H3.', 'js_composer'),
        'params' => array(
            array(
                'type' => 'textfield',
                'holder' => 'div',
                'heading' => __('Texto', 'js_composer'),
                'param_name' => 'textoh3',
            ),
            array(
                'type' => 'textfield',
                'heading' => __('Nombre de la clase extra', 'js_composer'),
                'param_name' => 'el_class',
                'description' => __('Añada un nombre de clase CSS personalizado.', 'js_composer'),
            )
        ),
    ));
    /******* H3 *******/



    /******* PÁRRAFO *******//*
    vc_map( array(
        'name' => __("Vocento Párrafo"),
        'base' => 'vocento_wp_text',
        "icon" => get_template_directory_uri() . "/includes/vocentoIcon.png",
        'category' => __( 'Vocento', 'js_composer' ),
        'class' => 'wpb_vc_wp_widget',
        'weight' => - 50,
        'description' => __( 'Párrafo', 'js_composer' ),
        'params' => array(
            array(
                'type' => 'textarea_html',
                'holder' => 'div',
                'heading' => __( 'Texto', 'js_composer' ),
                'param_name' => 'content',
            ),
            array(
                'type' => 'textfield',
                'heading' => __( 'Nombre de la clase extra', 'js_composer' ),
                'param_name' => 'el_class',
                'description' => __( 'Añada un nombre de clase CSS personalizado.', 'js_composer' ),
            ),
        ),
    ));

    /******* PÁRRAFO *******/



    /******* LISTA HORIZONTAL *******/

    vc_map(array(
        "name" => __("Vocento Lista Horizontal"),
        'base' => 'lista_horizontal',
        "icon" => get_template_directory_uri() . "/includes/vocentoIcon.png",
        'category' => __('Vocento', 'js_composer'),
        'description' => __('Lista Horizontal.', 'js_composer'),
        'params' => array(
            array(
                "type" => "listahorizontal",
                "class" => "",
                "param_name" => "listahorizontal",
            ),
            array(
                'type' => 'textfield',
                'heading' => __('Nombre de la clase extra', 'js_composer'),
                'param_name' => 'el_class',
                'description' => __('Añada un nombre de clase CSS personalizado.', 'js_composer'),
            )
        ),
    ));

    /******* LISTA HORIZONTAL *******/



    /******* LISTA VERTICAL *******//*
    vc_map(array(
        'name' => __( 'Vocento Lista Vertical', 'js_composer' ),
        'base' => 'lista_vertical',
        'class' => '',
        "icon" => get_template_directory_uri() . "/includes/vocentoIcon.png",
        'category' => __( 'Vocento', 'js_composer' ),
        'description' => __( 'Lista Vertical', 'js_composer' ),
        'params' => array(
            array(
                'type' => 'textfield',
                'heading' => __( 'Título lista', 'js_composer' ),
                'param_name' => 'titulo',
            ),
            array(
                'type' => 'param_group',
                'heading' => __( 'Values', 'js_composer' ),
                'param_name' => 'values',
                'params' => array(
                    array(
                        'type' => 'textfield',
                        'heading' => __( 'Encabezado', 'js_composer' ),
                        'param_name' => 'encabezado',
                        'description' => __( 'Introduce el texto.', 'js_composer' ),
                        'admin_label' => true,
                    ),
                    array(
                        'type' => 'textarea',
                        'heading' => __( 'Texto 1', 'js_composer' ),
                        'param_name' => 'content',
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => __( 'Clase Extra para elemento', 'js_composer' ),
                        'param_name' => 'claseExtra',
                        'description' => __( 'Introduce la clase personalizada.', 'js_composer' ),
                        'admin_label' => true,
                    ),
                    array(
                        'type' => 'attach_image',
                        'heading' => __( 'Image', 'js_composer' ),
                        'param_name' => 'image_url',
                        'value' => '',
                        'description' => __( 'Seleccionar una imagen.', 'js_composer' )
                    ),
                    array(
                        'type' => 'attach_image',
                        'heading' => __( 'Zoom Image', 'js_composer' ),
                        'param_name' => 'image_url_zoom',
                        'value' => '',
                        'description' => __( 'Seleccionar una imagen aumentada para que se pueda hacer zoom', 'js_composer' )
                    ),

                ),
                'callbacks' => array(
                    'after_add' => 'vcChartParamAfterAddCallback',
                ),
            ),
                 array(
                        'type' => 'checkbox',
                        'heading' => __( 'Animacion waypotint', 'js_composer' ),
                        'param_name' => 'animacionway',
                        'description' => __( 'offset: 50%', 'js_composer' ),
                    ),

            array(
                'type' => 'textfield',
                'heading' => __( 'Extra class name', 'js_composer' ),
                'param_name' => 'el_class',
                'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'js_composer' ),
            ),
        ),
    ));

    /******* LISTA VERTICAL *******/



    /****** IMAGEN INDIVIDUAL CON PIE DE FOTO *******/
    vc_map(array(
        "name" => __("Vocento Imagen Individual"),
        'base' => 'vocento_single_image',
        "icon" => get_template_directory_uri() . "/includes/vocentoIcon.png",
        'category' => __('Vocento', 'js_composer'),
        'description' => __('Imagen individual con pie de foto lateral.', 'js_composer'),
        'params' => array(
            array(
                'type' => 'attach_image',
                'heading' => __('Image', 'js_composer'),
                'param_name' => 'image_url',
                'value' => '',
                'description' => __('Seleccionar una imagen.', 'js_composer')
            ),
            array(
                'type' => 'textfield',
                'holder' => 'div',
                'heading' => __('Pié de foto', 'js_composer'),
                'param_name' => 'piedefoto',
                'description' => __('pié de foto.', 'js_composer'),
            ),
            array(
                'type' => 'checkbox',
                'heading' => __('Animacion waypotint', 'js_composer'),
                'param_name' => 'animacionway',
                'description' => __('offset: 50%', 'js_composer'),
            ),
            array(
                'type' => 'textfield',
                'heading' => __('Nombre de la clase extra', 'js_composer'),
                'param_name' => 'el_class',
                'description' => __('Añada un nombre de clase CSS personalizado.', 'js_composer'),
            ),
        ),
    ));


    /****** IMAGEN INDIVIDUAL CON PIE DE FOTO *******/



    /******* SWIPER SLIDER *******/


    vc_map(array(
        "name" => __("Vocento Swiper Slider"),
        'base' => 'swiper_slider',
        "icon" => get_template_directory_uri() . "/includes/vocentoIcon.png",
        'category' => __('Vocento', 'js_composer'),
        'description' => __('Swiper Slider.', 'js_composer'),
        'params' => array(
            array(
                "type" => "sliders",
                "class" => "",
                "param_name" => "sliders",
            ),
            array(
                'type' => 'textfield',
                'heading' => __('Nombre de la clase extra', 'js_composer'),
                'param_name' => 'el_class',
                'description' => __('Añada un nombre de clase CSS personalizado.', 'js_composer'),
            )
        ),
    ));

    /******* SWIPER SLIDER *******/


    /******** IMAGEN BLOQUOTE ******/
    vc_map(array(
        "name" => __("vocento Imagen Bloquote"),
        'base' => 'vocento_bloquote_image',
        "icon" => get_template_directory_uri() . "/includes/vocentoIcon.png",
        'category' => __('Vocento', 'js_composer'),
        'description' => __('Imagen con bloquote.', 'js_composer'),
        'params' => array(
            array(
                'type' => 'attach_image',
                'heading' => __('Image', 'js_composer'),
                'param_name' => 'image_url',
                'value' => '',
                'description' => __('Seleccionar una imagen.', 'js_composer')
            ),
                          array(
            'type' => 'dropdown',
            'heading' => __('Blockquote alineamiento', 'js_composer'),
            'param_name' => 'alignment',
            'value' => array(
                __('Derecha', 'js_composer') => 'derecha',
                __('Izquerda', 'js_composer') => 'izquierda',
            ),
            'save_always' => true,
            'description' => __('Seleccionar donde irá alineado el texto blockquote', 'js_composer'),
            ),
            array(
                'type' => 'textarea_html',
                'holder' => 'div',
                'heading' => __('Texto', 'js_composer'),
                'param_name' => 'content',
                'description' => __('Texto lateral.', 'js_composer'),
            ),
            array(
                'type' => 'textfield',
                'heading' => __('Nombre de la clase extra', 'js_composer'),
                'param_name' => 'el_class',
                'description' => __('Añada un nombre de clase CSS personalizado.', 'js_composer'),
            ),
        ),
    ));

    /******** IMAGEN BLOQUOTE ******/



    /******* IMAGE-VIDEO BLOCKQUOTE *******/
    if (is_plugin_active('vocento-brightcove/vocento-brightcove.php')) {
        vc_map(array(
            "name" => __("Vocento Video con descripción"),
            'base' => 'video_bloquote',
            "icon" => get_template_directory_uri() . "/includes/vocentoIcon.png",
            'category' => __('Vocento', 'js_composer'),
            'description' => __('Vocento Video Bloquote.', 'js_composer'),
            'params' => array(
             array(
                    'type' => 'textarea_html',
                    'heading' => __('Texto Bloquote', 'js_composer'),
                    'param_name' => 'content',
                    'description' => __('Texto lateral.', 'js_composer'),
                ),
                array(
                    "type" => "videobloquote",
                    "class" => "",
                    "param_name" => "videobloquote",
                ),
                    array(
                        'type' => 'attach_image',
                        'heading' => __('Imagen frontal Video (Opcional)', 'js_composer'),
                        'param_name' => 'stillurlvb',
                        'description' => __('Imagen que aparecerá antes de que el video se reproduzca', 'js_composer')
                    ),
                array(
                    'type' => 'textfield',
                    'heading' => __('Nombre de la clase extra', 'js_composer'),
                    'param_name' => 'el_class',
                    'description' => __('Añada un nombre de clase CSS personalizado.', 'js_composer'),
                )
            ),

        ));

        /******* IMAGE-VIDEO BLOCKQUOTE *******/



        /******* VIDEO SECUNDARIO *******/
        if (is_plugin_active('vocento-brightcove/vocento-brightcove.php')) {
            vc_map(array(
                "name" => __("Vocento Video Secundario"),
                'base' => 'video_secundario',
                "icon" => get_template_directory_uri() . "/includes/vocentoIcon.png",
                'category' => __('Vocento', 'js_composer'),
                'description' => __('Video Secundario.', 'js_composer'),
                'params' => array(
                    array(
                        "type" => "videosecundario",
                        "class" => "",
                        "param_name" => "videosecundario",
                    ),
                    array(
                        'type' => 'attach_image',
                        'heading' => __('Imagen frontal Video (Opcional)', 'js_composer'),
                        'param_name' => 'stillurlvs',
                        'description' => __('Imagen que aparecerá antes de que el video se reproduzca', 'js_composer')
                    ),
                ),
            ));
        }
    }
    /******* VIDEO SECUNDARIO *******/



    /******* FULL IMAGE *******/
    vc_map(array(
        "name" => __("Vocento Full Image"),
        'base' => 'full_image',
        "icon" => get_template_directory_uri() . "/includes/vocentoIcon.png",
        'category' => __('Vocento', 'js_composer'),
        'description' => __('Full Image.', 'js_composer'),
        'params' => array(
            array(
                "type" => "fullimage",
                "class" => "",
                "param_name" => "fullimage",
            ),
              array(
                'type' => 'checkbox',
                'heading' => __('Animacion waypotint', 'js_composer'),
                'param_name' => 'animacionway',
                'description' => __('offset: 50%', 'js_composer'),
            ),
            array(
                'type' => 'textfield',
                'heading' => __('Nombre de la clase extra', 'js_composer'),
                'param_name' => 'el_class',
                'description' => __('Añada un nombre de clase CSS personalizado.', 'js_composer'),
            )
        ),
    ));
    /******* FULL IMAGE *******/



    /******* COMPARTIR CONTENIDO *******/
    //SI SE SELECCIONA EL TEXTO EN LA PÁGINA YA HACER LA FUNCIÓN DE COMPARTIR CONTENIDO.
    /******* COMPARTIR CONTENIDO *******/



    /******* MARKER ANIMATED *******/
    vc_map(array(
        "name" => __("Vocento Párrafo"),
        'base' => 'vocento_marquer_animated',
        "icon" => get_template_directory_uri() . "/includes/vocentoIcon.png",
        'category' => __('Vocento', 'js_composer'),
        'description' => __('Párrafo', 'js_composer'),
        'params' => array(
            array(
                'type' => 'textarea_html',
                'holder' => 'div',
                'heading' => __('Texto animado', 'js_composer'),
                'param_name' => 'content',
                'description' => __('Poner en negrita el texto que se quiere subrayar.', 'js_composer'),
            ),
                array(
                'type' => 'checkbox',
                'heading' => __('Animacion waypotint', 'js_composer'),
                'param_name' => 'animacionway',
                'description' => __('offset: 50%', 'js_composer'),
            ),
            array(
                'type' => 'textfield',
                'heading' => __('Nombre de la clase extra', 'js_composer'),
                'param_name' => 'el_class',
                'description' => __('Añada un nombre de clase CSS personalizado.', 'js_composer'),
            ),
        ),
    ));
    /******* MARKER ANIMATED *******/



    /******* GRÁFICOS CHART *******/

    vc_map(array(
        'name' => __('Vocento Gráficos Chart', 'js_composer'),
        'base' => 'vocento_graficos_chart',
        'class' => '',
        "icon" => get_template_directory_uri() . "/includes/vocentoIcon.png",
        'category' => __('Vocento', 'js_composer'),
        'description' => __('Gráficos Chart', 'js_composer'),
        'params' => array(
            array(
                'type' => 'param_group',
                'heading' => __('Values', 'js_composer'),
                'param_name' => 'values',
                'params' => array(
                    array(
                        'type' => 'input_number',
                        'heading' => __('Porcentaje', 'js_composer'),
                        'param_name' => 'porcentaje',
                        'description' => __('Introduce el porcentaje.', 'js_composer'),
                        'admin_label' => true,
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => __('Texto 1', 'js_composer'),
                        'param_name' => 'texto1',
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => __('Texto 2', 'js_composer'),
                        'param_name' => 'texto2',
                    ),
                ),
                'callbacks' => array(
                    'after_add' => 'vcChartParamAfterAddCallback',
                ),
            ),
           array(
                'type' => 'checkbox',
                'heading' => __('Animacion waypotint', 'js_composer'),
                'param_name' => 'animacionway',
                'description' => __('offset: 50%', 'js_composer'),
            ),
            array(
                'type' => 'textfield',
                'heading' => __('Extra class name', 'js_composer'),
                'param_name' => 'el_class',
                'description' => __('Style particular content element differently - add a class name and refer to it in custom CSS.', 'js_composer'),
            ),
        ),
    ));

    /******* GRÁFICOS CHART *******/



    /******* MÓDULO EMERGENTE *******/
    vc_map(array(
        "name" => __("Vocento Módulo Emergente"),
        'base' => 'vocento_modulo_emergente',
        "icon" => get_template_directory_uri() . "/includes/vocentoIcon.png",
        'category' => __('Vocento', 'js_composer'),
        'description' => __('Módulo Emergente.', 'js_composer'),
        'params' => array(
            array(
                'type' => 'textarea_html',
                'holder' => 'div',
                'heading' => __('Texto explicativo', 'js_composer'),
                'param_name' => 'content',
                'description' => __('Texto explicativo.', 'js_composer'),
            ),
            array(
                'type' => 'textfield',
                'heading' => __('Nombre de la clase extra', 'js_composer'),
                'param_name' => 'el_class',
                'description' => __('Añada un nombre de clase CSS personalizado.', 'js_composer'),
            ),
        ),
    ));
    /******* MÓDULO EMERGENTE *******/



    /******* AUDIO PLAYER *******/
    vc_map(array(
        "name" => __("Vocento Audio Player"),
        'base' => 'vocento_audio_player',
        "icon" => get_template_directory_uri() . "/includes/vocentoIcon.png",
        'category' => __('Vocento', 'js_composer'),
        'description' => __('Audio Player.', 'js_composer'),
        'params' => array(
            array(
                'type' => 'selectaudio',
                'param_name' => 'mp3',
            ),
               array(
            'type' => 'dropdown',
            'heading' => __('Audio alineamiento', 'js_composer'),
            'param_name' => 'alignment',
            'value' => array(
                __('Derecha', 'js_composer') => 'derecha',
                __('Izquerda', 'js_composer') => 'izquierda',
            ),
            'save_always' => true,
            'description' => __('Seleccionar donde irá alineado el audio', 'js_composer'),
            ),
                  array(
                        'type' => 'attach_image',
                        'heading' => __('Image', 'js_composer'),
                        'param_name' => 'image_url',
                        'value' => '',
                        'description' => __('Seleccionar una imagen de fondo para el audio.', 'js_composer')
                    ),
            array(
                'type' => 'textfield',
                'heading' => __('Nombre de la clase extra', 'js_composer'),
                'param_name' => 'el_class',
                'description' => __('Añada un nombre de clase CSS personalizado.', 'js_composer'),
            ),
        ),
    ));
    /******* AUDIO PLAYER *******/

    /******* BLOQUE PIE *******/
    vc_map(array(
        "name" => __("Vocento Bloque Pié"),
        'base' => 'vocento_bloque_pie',
        "icon" => get_template_directory_uri() . "/includes/vocentoIcon.png",
        'category' => __('Vocento', 'js_composer'),
        'description' => __('Bloque pié.', 'js_composer'),
        'params' => array(
            array(
                'type' => 'textarea_html',
                'holder' => 'div',
                'heading' => __('Bloque pié', 'js_composer'),
                'param_name' => 'content',
                'value' => 'Este contenido ha sido desarrollado por <strong>Content Factory, la unidad de contenidos de marca de Vocento,</strong> con <strong> CLIENTE</strong>. En su elaboración no ha intervenido la redacción de este medio.',
                'description' => __('Texto bloque pié.', 'js_composer'),
            ),
            array(
                'type' => 'textfield',
                'heading' => __('Nombre de la clase extra', 'js_composer'),
                'param_name' => 'el_class',
                'description' => __('Añada un nombre de clase CSS personalizado.', 'js_composer'),
            ),
        ),
    ));

    /******* BLOQUE PIE *******/

    /******* MAS VISTOS O RELACIONADOS *******/

    // choosing postTypes
    $postTypes['post'] = 'post';

    vc_map(array(

        "name" => __("Vocento Más Vistos o Relacionados"),
        'base' => 'vocento_mas_vistos',
        "icon" => get_template_directory_uri() . "/includes/vocentoIcon.png", // Simply pass url to your icon here
        'category' => __('Vocento', 'js_composer'),
        'description' => __('Noticias relacionadas o más vistas.', 'js_composer'),
        'params' => array(
           array(
                        'type' => 'dropdown',
                        'heading' => __('Tipo', 'js_composer'),
                        'param_name' => 'post_type',
                        'value' => $postTypes,
                        'save_always' => true,
                        'description' => __('Contenido de las relaciones', 'js_composer'),
                        'admin_label' => true,
                    ),
                    array(
                        'type' => 'dropdown',
                        'heading' => __('Tipo Relación', 'js_composer'),
                        'param_name' => 'mas_vistos',
                           'description' => __('Seleccione que post se visualizarán por pantalla.', 'js_composer'),
                        'save_always' => true,
                        'admin_label' => true,
                        'value' => array( __('Más Vistos', 'js_composer') => 'vistos', __('Relacionados', 'js_composer') => 'relacionados' ),
                        ),
                  array(
                        'type' => 'input_number',
                        'heading' => __('Posts a mostrar', 'js_composer'),
                        'param_name' => 'max_items',
                        'admin_label' => true,
                                        'description' => __('Configure los objetos a mostrar', 'js_composer'),
                    ),
            array(
                'type' => 'textfield',
                'heading' => __('Nombre de la clase extra', 'js_composer'),
                'param_name' => 'el_class',
                'description' => __('Añada un nombre de clase CSS personalizado.', 'js_composer'),
            ),
        ),
    ));
    /******* CITA BLOCKQUOTE *******/
    vc_map(array(
    'name' => __("Vocento Cita Blockquote"),
    'base' => 'vocento_blockquote',
    'icon' => get_template_directory_uri() . "/includes/vocentoIcon.png", // Simply pass url to your icon here
    'category' => __('Vocento', 'js_composer'),
    'class' => 'wpb_vc_wp_widget',
    'weight' => - 50,
    'description' => __('Cita Blockquote', 'js_composer'),
    'params' => array(
        array(
            'type' => 'textarea_html',
            'holder' => 'div',
            'heading' => __('Cita', 'js_composer'),
            'param_name' => 'content',
        ),
        array(
            'type' => 'textarea',
            'holder' => 'div',
            'heading' => __('Autor', 'js_composer'),
            'param_name' => 'blockquote',
            'description' => __('Texto blockquote.', 'js_composer'),
        ),

        array(
            'type' => 'dropdown',
            'heading' => __('Blockquote alineamiento', 'js_composer'),
            'param_name' => 'alignment',
            'value' => array(
                __('Izquerda', 'js_composer') => 'izquierda',
                __('Derecha', 'js_composer') => 'derecha',
            ),
            'save_always' => true,
            'description' => __('Seleccionar donde irá alineado el texto blockquote', 'js_composer'),
        ),
        array(
            'type' => 'textfield',
            'heading' => __('Nombre de la clase extra', 'js_composer'),
            'param_name' => 'el_class',
            'description' => __('Añada un nombre de clase CSS personalizado.', 'js_composer'),
        ),
    ),
));



    /******* CITA BLOCKQUOTE *******/


    /******* LISTA VERTICAL CCOMIENZO *******/

    vc_map(array(
        "name" => __("Vocento Lista Vertical Comienzo"),
        'base' => 'lista_vertical_comienzo',
        "icon" => get_template_directory_uri() . "/includes/vocentoIcon.png",
        'category' => __('Vocento', 'js_composer'),
        'description' => __('Lista Vertical comienzo.', 'js_composer'),
        'params' => array(
          array(
                'type' => 'textfield',
                'heading' => __('Título lista', 'js_composer'),
                'param_name' => 'titulo',
                'description' => __('Asignar un título a la lista', 'js_composer'),
            ),
          array(
                        'type' => 'checkbox',
                        'heading' => __('Animacion waypotint', 'js_composer'),
                        'param_name' => 'animacionway',
                        'description' => __('offset: 50%', 'js_composer'),
                    ),

            array(
                'type' => 'textfield',
                'heading' => __('Nombre de la clase extra', 'js_composer'),
                'param_name' => 'el_class',
                'description' => __('Añada un nombre de clase CSS personalizado.', 'js_composer'),
            )
        ),
    ));

    /******* LISTA VERTICAL COMIENZO *******/


    /******* ELEMENTOS LISTA VERTICAL *******/

    vc_map(array(
        "name" => __("Vocento Items Lista Vertical"),
        'base' => 'lista_vertical_item',
        "icon" => get_template_directory_uri() . "/includes/vocentoIcon.png",
        'category' => __('Vocento', 'js_composer'),
        'description' => __('Items Lista Vertical comienzo.', 'js_composer'),
        'params' => array(
                array(
                        'type' => 'textfield',
                        'heading' => __('Encabezado', 'js_composer'),
                        'param_name' => 'encabezado',
                        'description' => __('Introduce el texto.', 'js_composer'),
                        'admin_label' => true,
                    ),
          array(
                  'type' => 'textarea_html',
                'holder' => 'div',
                'heading' => __('Contenido', 'js_composer'),
                'param_name' => 'content',
                'description' => __('Contenido a mostrar en el elemento de la lista', 'js_composer'),
            ),

                     array(
                        'type' => 'checkbox',
                        'heading' => __('Animacion waypotint', 'js_composer'),
                        'param_name' => 'animacionway',
                        'description' => __('offset: 50%', 'js_composer'),
                    ),

                    array(
                        'type' => 'attach_image',
                        'heading' => __('Image', 'js_composer'),
                        'param_name' => 'image_url',
                        'value' => '',
                        'description' => __('Seleccionar una imagen.', 'js_composer')
                    ),
                    array(
                        'type' => 'attach_image',
                        'heading' => __('Zoom Image', 'js_composer'),
                        'param_name' => 'image_url_zoom',
                        'value' => '',
                        'description' => __('Seleccionar una imagen aumentada para que se pueda hacer zoom', 'js_composer')
                    ),

                     array(
                        'type' => 'checkbox',
                        'heading' => __('¿Final de lista?', 'js_composer'),
                        'param_name' => 'finallista',
                        'description' => __('offset: 50%', 'js_composer'),
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => __('Clase Extra para elemento', 'js_composer'),
                        'param_name' => 'claseExtra',
                        'description' => __('Introduce la clase personalizada.', 'js_composer'),
                        'admin_label' => true,
                    ),


        ),
    ));

    /******* ELEMENTOS LISTA VERTICAL *******/



    /******* MAS VISTOS O RELACIONADOS CON FILTRADO TAGS *******/

    // choosing postTypes
    $postTypes['post'] = 'post';
    vc_map(array(
            "name" => __("Vocento Más Vistos o Relacionados Con TAGS Eslaliga"),
            'base' => 'vocento_mas_vistos_tags',
            "icon" => get_template_directory_uri() . "/includes/vocentoIcon.png", // Simply pass url to your icon here
            'category' => __('Vocento', 'js_composer'),
            'description' => __('Noticias relacionadas o más vistas.', 'js_composer'),
            'params' => array(
            array(
                    'type' => 'dropdown',
                    'heading' => __('Tipo', 'js_composer'),
                    'param_name' => 'post_type',
                    'value' => $postTypes,
                    'save_always' => true,
                    'description' => __('Contenido de las relaciones', 'js_composer'),
                    'admin_label' => true,
                ),
                array(
                    'type' => 'dropdown',
                    'heading' => __('Tipo Relación', 'js_composer'),
                    'param_name' => 'mas_vistos',
                        'description' => __('Seleccione que post se visualizarán por pantalla.', 'js_composer'),
                    'save_always' => true,
                    'admin_label' => true,
                    'value' => array( __('Más Vistos', 'js_composer') => 'vistos', __('Relacionados', 'js_composer') => 'relacionados' ),
                    ),
                array(
                    'type' => 'tag_field',
                    'holder' => 'div',
                    'class' => '',
                    'admin_label' => true,
                    'heading' => __('Término relación', 'textdomain'),
                    'param_name' => 'tag_name',
                ),
                array(
                    'type' => 'input_number',
                    'heading' => __('Posts a mostrar', 'js_composer'),
                    'param_name' => 'max_items',
                    'admin_label' => true,
                    'description' => __('Configure los objetos a mostrar', 'js_composer'),
                ),
                array(
                    'type' => 'textfield',
                    'heading' => __('Nombre de la clase extra', 'js_composer'),
                    'param_name' => 'el_class',
                    'description' => __('Añada un nombre de clase CSS personalizado.', 'js_composer'),
                ),
            ),
        ));


    /******* MAS VISTOS O RELACIONADOS CON FILTRADO TAGS *******/

    // choosing postTypes
    $postTypes['post'] = 'post';
    vc_map(array(
            "name" => __("Vocento Más Vistos o Relacionados Con TAGS"),
            'base' => 'vocento_mas_vistos_tags_general',
            "icon" => get_template_directory_uri() . "/includes/vocentoIcon.png", // Simply pass url to your icon here
            'category' => __('Vocento', 'js_composer'),
            'description' => __('Noticias relacionadas o más vistas.', 'js_composer'),
            'params' => array(
            array(
                    'type' => 'dropdown',
                    'heading' => __('Tipo', 'js_composer'),
                    'param_name' => 'post_type',
                    'value' => $postTypes,
                    'save_always' => true,
                    'description' => __('Contenido de las relaciones', 'js_composer'),
                    'admin_label' => true,
                ),
                array(
                    'type' => 'dropdown',
                    'heading' => __('Tipo Relación', 'js_composer'),
                    'param_name' => 'mas_vistos',
                        'description' => __('Seleccione que post se visualizarán por pantalla.', 'js_composer'),
                    'save_always' => true,
                    'admin_label' => true,
                    'value' => array( __('Más Vistos', 'js_composer') => 'vistos', __('Relacionados', 'js_composer') => 'relacionados' ),
                    ),
                array(
                    'type' => 'tag_field',
                    'holder' => 'div',
                    'class' => '',
                    'admin_label' => true,
                    'heading' => __('Término relación', 'textdomain'),
                    'param_name' => 'tag_name',
                ),
                array(
                    'type' => 'input_number',
                    'heading' => __('Posts a mostrar', 'js_composer'),
                    'param_name' => 'max_items',
                    'admin_label' => true,
                    'description' => __('Configure los objetos a mostrar', 'js_composer'),
                ),
                array(
                    'type' => 'textfield',
                    'heading' => __('Nombre de la clase extra', 'js_composer'),
                    'param_name' => 'el_class',
                    'description' => __('Añada un nombre de clase CSS personalizado.', 'js_composer'),
                ),
            ),
        ));

    // Create galleryModal element for Visual Composer
    vc_map(array(
            'name' => __('galleryModal', 'textdomain'),
            'description' => __('Galería Modal', ''),
            'base' => 'gallerymodal',
            'show_settings_on_create' => true,
            'category' => __('Content', 'textdomain'),
            'params' => array(
                array(
                    'type' => 'attach_image',
                    'holder' => 'div',
                    'class' => '',
                    'admin_label' => true,
                    'heading' => __('Imagen', 'textdomain'),
                    'param_name' => 'imagen',
                ),
                array(
                    'type' => 'textfield',
                    'holder' => 'div',
                    'class' => '',
                    'admin_label' => true,
                    'heading' => __('Titular', 'textdomain'),
                    'param_name' => 'titular',
                ),
                array(
                    'type' => 'textarea_html',
                    'holder' => 'div',
                    'class' => '',
                    'admin_label' => true,
                    'heading' => __('Contenido', 'textdomain'),
                    'param_name' => 'content',
                ),
                array(
                    "type" => "order_id",
                    "param_name" => "textfield",
                    'heading' => __('orderid', 'textdomain'),
                    "value" => array(),
                ),
            )
        ));
}
