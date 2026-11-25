<?php
add_shortcode( 'voc_tabla_2027', 'voc_tabla_2027_func' );
function voc_tabla_2027_func( $atts ) {
    $atts = shortcode_atts( array(
        'con_nombres_fila' => '',
        'tabla_columnas'   => '',
        'tabla_filas'      => '',
        'tabla_pie'        => '',
    ), $atts );

    $tiene_nombres_fila = ( $atts['con_nombres_fila'] === 'yes' );


    $wrapper_classes = 'tabla-2027';
    if ( $tiene_nombres_fila ) {
        $wrapper_classes .= ' con-nombres-fila';
    }

    $columnas = ! empty( $atts['tabla_columnas'] ) ? vc_param_group_parse_atts( $atts['tabla_columnas'] ) : array();
    $filas    = ! empty( $atts['tabla_filas'] ) ? vc_param_group_parse_atts( $atts['tabla_filas'] ) : array();


    $output = '<div class="' . esc_attr( $wrapper_classes ) . '">';
    $output .= '<div class="tabla-2027-scroll">';
    $output .= '<table class="tabla-2027-content">';
    if ( ! empty( $columnas ) ) {
        $output .= '<thead><tr>';
        if ( $tiene_nombres_fila ) {
            $output .= '<th scope="col"></th>';
        }
        foreach ( $columnas as $col ) {
            $titulo_col = isset( $col['col_titulo'] ) ? esc_html( $col['col_titulo'] ) : '';
            $output .= '<th scope="col">' . $titulo_col . '</th>';
        }
        $output .= '</tr></thead>';
    }
    if ( ! empty( $filas ) ) {
        $output .= '<tbody>';
        foreach ( $filas as $fila ) {
            $output .= '<tr>';
            if ( $tiene_nombres_fila ) {
                $nombre_fila = isset( $fila['fila_nombre'] ) ? esc_html( $fila['fila_nombre'] ) : '';
                $output .= '<th scope="row">' . $nombre_fila . '</th>';
            }
            $valores_raw = isset( $fila['fila_valores'] ) ? $fila['fila_valores'] : '';
            $celdas = explode( ',', $valores_raw );
            foreach ( $celdas as $celda ) {
                $output .= '<td>' . wp_kses_post( trim( $celda ) ) . '</td>';
            }
            $output .= '</tr>';
        }
        $output .= '</tbody>';
    }
    $output .= '</table>';
    $output .= '</div>';
    if ( ! empty( $atts['tabla_pie'] ) ) {
        $output .= '<figcaption class="tabla-2027-pie">' . esc_html( $atts['tabla_pie'] ) . '</figcaption>';
    }
    $output .= '</div>';

    return $output;
}

add_action( 'vc_before_init', 'voc_tabla_2027_integrateWithVC' );
function voc_tabla_2027_integrateWithVC() {
    vc_map( array(
        'name' => __( 'Tabla 2027', 'textdomain' ),
        'base' => 'voc_tabla_2027',
        'show_settings_on_create' => true,
        'category' => __( 'Vocento 2027', 'textdomain' ),
        'params' => array(
            array(
                'type' => 'checkbox',
                'heading' => __( 'Configuración de tabla', 'textdomain' ),
                'param_name' => 'con_nombres_fila',
                'value' => array( __( 'La primera columna contiene nombres de fila (cabeceras laterales)', 'textdomain' ) => 'yes' ),
                'description' => __( 'Activa esto si la primera celda de cada fila actúa como etiqueta.', 'textdomain' ),
            ),
            array(
                'type' => 'param_group',
                'heading' => __( 'Columnas de la Tabla (Cabeceras)', 'textdomain' ),
                'param_name' => 'tabla_columnas',
                'description' => __( 'Define los títulos de las columnas (ej: 2024, 2025, 2026).', 'textdomain' ),
                'params' => array(
                    array(
                        'type' => 'textfield',
                        'heading' => __( 'Nombre de la Columna', 'textdomain' ),
                        'param_name' => 'col_titulo',
                        'admin_label' => true,
                    ),
                ),
            ),
            array(
                'type' => 'param_group',
                'heading' => __( 'Filas de Datos', 'textdomain' ),
                'param_name' => 'tabla_filas',
                'description' => __( 'Añade las filas y sus respectivos valores separados por comas o introduce celdas independientes.', 'textdomain' ),
                'params' => array(
                    array(
                        'type' => 'textfield',
                        'heading' => __( 'Nombre / Etiqueta de la Fila (Si aplica)', 'textdomain' ),
                        'param_name' => 'fila_nombre',
                        'admin_label' => true,
                        'description' => __( 'Solo se usa si la opción "primera columna con nombres" está activada.', 'textdomain' ),
                    ),
                    array(
                        'type' => 'textarea',
                        'heading' => __( 'Valores de las celdas (separados por comas)', 'textdomain' ),
                        'param_name' => 'fila_valores',
                        'description' => __( 'Introduce los datos de cada columna para esta fila, separados por coma (,). Ejemplo: Dato 1 , Dato 2 , Dato 3', 'textdomain' ),
                    ),
                ),
            ),
            array(
                'type' => 'textarea',
                'heading' => __( 'Pie de tabla (Opcional)', 'textdomain' ),
                'param_name' => 'tabla_pie',
                'description' => __( 'Texto aclaratorio o fuente que aparecerá debajo de la tabla.', 'textdomain' ),
            ),
        ),
    ) );
}
