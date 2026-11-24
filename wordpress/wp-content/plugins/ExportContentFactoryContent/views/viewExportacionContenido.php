<?php
$exportacionContenido = new ExportacionContenido();

$users = get_users();
$categorias = get_categories();

echo '<h1>Exportar Contenido</h1>';

 $target_dir = plugin_dir_path(PATH);
 $contentFile = file_get_contents(PATH.'/class/sample_data.xml');

if(isset($_POST['submit'])){


    $defaultRole = $_POST['default_role'];
    $defaultCategory = $_POST['default_category'];
    $tiponoticia = $_POST['tipo_noticia'];
    $desde = $_POST['trip-start'];
    $hasta = $_POST['trip-end'];
    $defaultcategoryfinal = $_POST['default_category_final'];
    $exportacionContenido::exportData($defaultRole, $defaultCategory, $desde, $hasta, $defaultcategoryfinal, $tiponoticia);

    $filename = PATH.'/class/sample_data.xml';

    header("Content-type: application/x-msdownload",true,200);
    header("Content-Disposition: attachment; filename=sample_data.xml");
    header("Pragma: no-cache");
    header("Expires: 0");

    while (ob_get_level()) {
        ob_end_clean();
        @readfile(PATH.'/class/sample_data.xml');
        
    }

    unlink(PATH.'/class/sample_data.xml');
    exit();
}else{

}

$date = date('d/m/Y');

?>


<form method="post" action="#">
<table class="form-table" role="presentation">
<tbody>
    <!--<tr>
        <th>Autor:</th>
        <td>
            <select name="default_role" id="default_role">
                <option selected="selected" value="0">Selecciona</option>
                <?php
                    // foreach($users as $user){
                    //     echo '<option value="'.$user->ID.'">'.$user->user_login.'</option>';
                    // }
                ?>
            </select>
        </td>
    </tr>-->
    <tr>
        <th>Categoría inicial:</th>
        <td>
            <select name="default_category" id="default_category">
                <option selected="selected" value="0">Selecciona</option>
                <?php
                    foreach($categorias as $categoria){
                        echo '<option value="'.$categoria->term_id.'">'.$categoria->name.'</option>';
                    }
                ?>
            </select>
        </td>
    </tr>
    <!--<tr>
        <th>Categoría final:</th>
        <td>
            <select name="default_category_final" id="default_category">
                <option selected="selected" value="0">Selecciona</option>
                <option value="a-fondo-a|fondo">a fondo</option>
                <option value="ciencia|ciencia">ciencia</option>
                <option value="firmas|firmas">firmas</option>
                <option value="arturo-perez-reverte|arturoperezreverte">arturoperezreverte</option>
                <option value="benjamin-lana|benjaminlana">benjaminlana</option>
                <option value="carlos-herrera|carlosherrera">carlosherrera</option>
                <option value="carlos-maribona|carlosmaribona">carlosmaribona</option>
                <option value="carmen-posadas|carmenposadas">carmenposadas</option>
                <option value="cartas-lector|Cartas de los lectores">Cartas de los lectores</option>
                <option value="david-trueba|davidtrueba">davidtrueba</option>
                <option value="isabel-coixet|isabelcoixet">isabelcoixet</option>
                <option value="juan-manuel-de-prada|juanmanueldeprada">juanmanueldeprada</option>
                <option value="pauarenos|pauarenos">pauarenos</option>
                <option value="gastronomia|gastronomia">gastronomia</option>
                <option value="recetas|recetas">recetas</option>
                <option value="historia|historia">historia</option>
                <option value="naturaleza|naturaleza">naturaleza</option>
                <option value="personajes|personajes">personajes</option>
                <option value="salud|salud">salud</option>
            </select>
        </td>
    </tr>-->
    <!--<tr>
        <th>Tipo de noticia:</th>
        <td>
            <select name="tipo_noticia" id="default_category">
                <option selected="selected" value="0">Selecciona</option>
                <option value="ne">Notica estandar</option>
                <option value="re">Reportaje</option>
                <option value="en">Entrevista</option>
                <option value="op">Opinión</option>
            </select>
        </td>
    </tr>-->
    <tr>
        <th>Desde:</th>
        <td>
            <input type="date" id="start" name="trip-start" value="" min="" max="">
        </td>
    </tr>
    <tr>
        <th>Hasta:</th>
        <td>
            <input type="date" id="end" name="trip-end" value="" min="" max="">
        </td>
    </tr>

</tbody></table>
<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Exportar"></p>
</form>