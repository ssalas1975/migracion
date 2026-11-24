<?php
/*
  Plugin Name: configuradorEventosClass
  Plugin URI:
  Description:
  Version: 1.0.0
  Author: s.salas
  Author URI:
  License: GPLv2
 */

require_once plugin_dir_path(__FILE__) . 'class/configuradorEventosClass.php';
require_once plugin_dir_path(__FILE__) . 'class/initFunctions.php';

function configurador_menu() {

    add_menu_page('Configurar Eventos', 'Configurar Eventos', 'manage_options', 'configurar-eventos', 'configurador_admin_page');
}

add_action('admin_menu', 'configurador_menu');

function configurador_admin_page() {
    $configuradorclass = new configuradorEventosClass();
    ?>
    <link rel="stylesheet" href="<?php echo plugin_dir_url(__FILE__); ?>/assets/bootstrap.min.css">
    <script src="<?php echo plugin_dir_url(__FILE__); ?>/assets/jquery.min.js"></script>
    <script src="<?php echo plugin_dir_url(__FILE__); ?>/assets/bootstrap.min.js"></script>

    <div class="container">
        <h2>Administrar Eventos</h2>
        <form class="form-horizontal" method="POST">
            <div class="form-group">
                <label class="control-label col-sm-2" for="email">Docente del Evento:</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="configurador-docente-evento" placeholder="Docente del Evento" name="configurador-docente-evento">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2" for="pwd">Nombre del Evento:</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="configurador-titulo-evento" placeholder="Nombre del Evento" name="configurador-titulo-evento">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2" for="pwd">Hora del Evento:</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="configurador-hora-evento" placeholder="Hora del Evento" name="configurador-hora-evento">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2" for="pwd">Número de asistentes:</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="configurador-asistentes-evento" placeholder="Número de asistentes" name="configurador-asistentes-evento">
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <button type="submit" class="btn btn-default">Crear</button>
                </div>
            </div>
        </form>
    </div>
    <?php

    if (isset($_POST['configurador-titulo-evento'])) {
        $configuradorclass::crearEvento($_POST['configurador-docente-evento'], $_POST['configurador-titulo-evento'], $_POST['configurador-hora-evento'], $_POST['configurador-asistentes-evento']);
    }

    $configuradorclass::tablaEventos();
}

/**
 * Add new fields above 'Update' button.
 *
 * @param WP_User $user User object.
 */
function tm_additional_profile_fields($user) {
    $usermeta = get_user_meta($user->ID, 'idevento');
    
    ?>
    <h1>Clases Reservadas</h1>

    <table class="form-table">
        <?php
        if( !empty($usermeta) )
        {
            foreach ($usermeta[0] as $res) {
            $metas = get_post_meta($res, 'data_event', true);
            //print_r($metas);
            echo '
                   <tr class="user-rich-editing-wrap">
                            <th scope="row">' . $metas["nombre"] . '</th>
                            <td><label for="rich_editing">
                                    <input name="clase[]" type="checkbox" id="clase" value=' . $res . '> Dar de baja.
                                </label>
                            </td>
                    </tr> 
                ';
            }
        }
        
        ?>
    </table>
    <?php
}

add_action('show_user_profile', 'tm_additional_profile_fields');
add_action('edit_user_profile', 'tm_additional_profile_fields');


/**
 * Save additional profile fields.
 *
 * @param  int $user_id Current user ID.
 */
function tm_save_profile_fields( $user_id ) {

    if ( ! current_user_can( 'edit_user', $user_id ) ) {
   	 return false;
    }

    if ( empty( $_POST['clase'] ) ) {
   	 return false;
    }
    
    $usermeta = get_user_meta($user_id, 'idevento',true);
    $temp = array();
    foreach ($usermeta as $res) {
        if (in_array($res, $_POST['clase'])) {
        }else{
            array_push($temp,$res);
        }
    }
    if(empty($temp))
    {
        delete_user_meta( $user_id, 'idevento' );
    }else{
        update_usermeta( $user_id, 'idevento', $temp );
    }

    
}

add_action( 'personal_options_update', 'tm_save_profile_fields' );
add_action( 'edit_user_profile_update', 'tm_save_profile_fields' );