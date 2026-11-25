<?php
     $user_info = get_userdata(1);
     if($user_info->roles[0]=="administrator"){
         if(function_exists("register_field_group"))
         {
             register_field_group(array (
                 'id' => 'acf_aaa',
                 'title' => 'aaa',
                 'fields' => array (
                     array (
                         'key' => 'field_57a0bfa52e78a',
                         'label' => 'Url Medio',
                         'name' => 'url_medio',
                         'type' => 'text',
                         'required' => 1,
                         'default_value' => '',
                         'placeholder' => '',
                         'prepend' => '',
                         'append' => '',
                         'formatting' => 'html',
                         'maxlength' => '',
                     ),
                     array (
                         'key' => 'field_57a0bfbe2e78b',
                         'label' => 'Css',
                         'name' => 'css',
                         'type' => 'file',
                         'instructions' => 'Si no se rellena el css, coger el que existe por defecto',
                         'required' => 0,
                         'save_format' => 'object',
                         'library' => 'all',
                     ),
                     array (
                        'key' => 'field_57a0bfbe7e73b',
                        'label' => 'Css 2024',
                        'name' => 'css_2024',
                        'type' => 'file',
                        'instructions' => 'Si no se rellena el css, coger el que existe por defecto',
                        'required' => 0,
                        'save_format' => 'object',
                        'library' => 'all',
                    ),
                     array (
                         'key' => 'field_57a0c0122e78c',
                         'label' => 'Url cabecera',
                         'name' => 'url_cabecera',
                         'type' => 'text',
                         'required' => 0,
                         'default_value' => '',
                         'placeholder' => '',
                         'prepend' => '',
                         'append' => '',
                         'formatting' => 'html',
                         'maxlength' => '',
                     ),
                         array (
                         'key' => 'field_57a0c0122e78l',
                         'label' => 'Url Nav Mobile',
                         'name' => 'url_cabecera_mini',
                         'type' => 'text',
                         'required' => 0,
                         'default_value' => '',
                         'placeholder' => '',
                         'prepend' => '',
                         'append' => '',
                         'formatting' => 'html',
                         'maxlength' => '',
                     ),
                     array (
                         'key' => 'field_57a0c0272e78d',
                         'label' => 'Url Pie',
                         'name' => 'url_pie',
                         'type' => 'text',
                         'required' => 0,
                         'default_value' => '',
                         'placeholder' => '',
                         'prepend' => '',
                         'append' => '',
                         'formatting' => 'html',
                         'maxlength' => '',
                     ),
                     array (
                         'key' => 'field_573gc0272e78d',
                         'label' => 'Url Favicon',
                         'name' => 'url_favicon',
                         'type' => 'text',
                         'required' => 0,
                         'default_value' => '',
                         'placeholder' => '',
                         'prepend' => '',
                         'append' => '',
                         'formatting' => 'html',
                         'maxlength' => '',
                     ),
                     array (
                         'key' => 'field_57d1268672545',
                         'label' => 'Logo del medio Desktop',
                         'name' => 'logo_del_medio',
                         'type' => 'image',
                         'save_format' => 'object',
                         'preview_size' => 'thumbnail',
                         'library' => 'all',
                     ),
                     array (
                         'key' => 'field_57d1268672567',
                         'label' => 'Logo del medio Mobile',
                         'name' => 'logo_del_medio_mobile',
                         'type' => 'image',
                         'save_format' => 'object',
                         'preview_size' => 'thumbnail',
                         'library' => 'all',
                     ),

                       array (
                         'key' => 'field_57s0bfa52ead78d',
                         'label' => 'ID Post Tag Manager',
                         'name' => 'id_ptm',
                         'type' => 'text',
                         'required' => 1,
                         'default_value' => '',
                         'placeholder' => '',
                         'prepend' => '',
                         'append' => '',
                         'formatting' => 'text',
                         'maxlength' => '',
                     ),
                     array (
                         'key' => 'field_57c68b2f3a6d9',
                         'label' => 'Clase body',
                         'name' => 'clase_body',
                         'type' => 'text',
                         'instructions' => 'Introducir en el caso de que el body lleve una clase especifica',
                         'default_value' => '',
                         'placeholder' => '',
                         'prepend' => '',
                         'append' => '',
                         'formatting' => 'html',
                         'maxlength' => '',
                     ),




                     array (
                        'key' => 'field_572wbfbe7e73b',
                        'label' => 'Publisher FB',
                        'name' => 'publisher',
                        'type' => 'text',
                        'required' => 0,
                        'save_format' => 'object',
                        'library' => 'all',
                    ),
                    array (
                        'key' => 'field_5oiwbfbe7e73b',
                        'label' => 'Pages ID FB',
                        'name' => 'pages_id_fb',
                        'type' => 'text',
                        'required' => 0,
                        'save_format' => 'object',
                        'library' => 'all',
                    ),
                    array (
                        'key' => 'field_5oiwbfbeg473b',
                        'label' => 'APP ID FB',
                        'name' => 'app_id_fb',
                        'type' => 'text',
                        'required' => 0,
                        'save_format' => 'object',
                        'library' => 'all',
                    ),






                 ),
                 'location' => array (
                     array (
                         array (
                             'param' => 'ef_taxonomy',
                             'operator' => '==',
                             'value' => 'Medios',
                             'order_no' => 0,
                             'group_no' => 0,
                         ),
                     ),
                 ),
                 'options' => array (
                     'position' => 'normal',
                     'layout' => 'no_box',
                     'hide_on_screen' => array (
                     ),
                 ),
                 'menu_order' => 0,
             ));
         }
     }
?>
