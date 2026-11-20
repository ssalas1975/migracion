<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of configuradorEventosClass
 *
 * @author s.salas
 */
class configuradorEventosClass {

    //put your code here

    public static function crearEvento($docente, $nombre, $hora, $asistentes) {
        $args = array(
            'post_status' => 'publish',
            'post_type' => 'evento',
        );
        $post_id = wp_insert_post($args);
        $metas = array(
            'docente' => $docente,
            'nombre' => $nombre,
            'hora' => $hora,
            'asistentes' => $asistentes
        );
        add_post_meta($post_id, 'data_event', $metas);
    }

    public static function tablaEventos() {

        $defaults = array(
            'numberposts' => -1,
            'post_type' => 'evento',
            'order' => 'ASC'
        );

        $posts = get_posts($defaults);
        
        ?>
        <div class="bs-example" data-example-id="condensed-table" style="padding-left: 80px; padding-right: 110px;"> 
            <table class="table table-condensed"> 
                <thead> 
                    <tr> 
                        <th>Evento</th> 
                        <th>Docente</th> 
                        <th>Hora</th>
                        <th>Asistentes</th>
                        <th>Shortcode</th>
                        <th>Borrar</th>
                <th>Editar</th>
                </thead> 
                <tbody> 
        <?php
        foreach ($posts as $post) {
            $evento = get_post_meta($post->ID, 'data_event');
            echo '
                <tr> 
                    <td>' . $evento[0]['nombre'] . '</td>
                    <td>' . $evento[0]["docente"] . '</td> 
                    <td>' . $evento[0]["hora"] . '</td>
                    <td>' . $evento[0]["asistentes"] . '</td>
                    <td>[event id="' . $post->ID . '"]</th>
                    <td>
                        <a href="' . wp_delete_post_link($post->ID) . '">
                            <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                        </a>
                    </td> 
                    <td>
                        <a href="' . get_edit_post_link( $post->ID ) . '">
                            <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
                        </a>
                    </td>
                </tr> 
            ';
        }
        ?>
                </tbody> 
            </table> 

            <div class="col-sm-offset-10 col-sm-2">
                <a href="<?php echo plugin_dir_url( __DIR__ ); ?>register-2-excel.php" class="exportar">
                    <button type="button" class="btn btn-default">Generar Excel</button>
                </a>
            </div>

        </div>
        <?php
    }
    

}
