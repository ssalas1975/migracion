<style>
    body {
        font-family: Arial, Helvetica, sans-serif;
    }

    /* The Modal (background) */
    .modal {
        display: none;
        /* Hidden by default */
        position: fixed;
        /* Stay in place */
        z-index: 1;
        /* Sit on top */
        padding-top: 200px;
        /* Location of the box */
        left: 0;
        top: 0;
        width: 100%;
        /* Full width */
        height: 100%;
        /* Full height */
        overflow: auto;
        /* Enable scroll if needed */
        background-color: rgb(0, 0, 0);
        /* Fallback color */
        background-color: rgba(0, 0, 0, 0.4);
        /* Black w/ opacity */
    }

    /* Modal Content */
    .modal-content {
        background-color: #fefefe;
        margin: auto;
        padding: 20px;
        border: 1px solid #888;
        width: 400px;
    }

    /* The Close Button */
    .close {
        color: #aaaaaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
    }

    .close:hover,
    .close:focus {
        color: #000;
        text-decoration: none;
        cursor: pointer;
    }
</style>

<div class="wrap">
    <link rel="stylesheet" href="<?php echo GISTAU__PLUGIN_URL . 'templates/candidateTemplate/'; ?>styleCandidate.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script src="<?php echo GISTAU__PLUGIN_URL . 'templates/candidateTemplate/'; ?>codeCandidate.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@400;700&display=swap" rel="stylesheet">
    <?php
    $page_now = isset($_GET['page']) ? sanitize_text_field($_GET['page']) : '';
    $titulo_pantalla = 'Presentados 1ª ronda';

    if ($page_now === 'candidatos') {
        $titulo_pantalla = 'Inscripcion propia';
    } elseif ($page_now === 'nominadores') {
        $titulo_pantalla = 'Nominaciones';
    }
    ?>
    <h1><?php echo $titulo_pantalla; ?></h1>
    <table id="tableData" class="display cell-border row-border" style="width:100%">
        <thead>
            <tr>
                <th>FECHA DE INSCRIPCIÓN</th>
                <th>VER DETALLE</th>
                <th>NOMBRE</th>
                <th>APELLIDOS</th>
                <th>EMAIL</th>
                <?php if ($page_now === 'candidatos'): ?>
                    <th>FOTO</th>
                    <th>LOGO</th>
                <?php endif; ?>
                <th>TOP 100</th>
            </tr>
        </thead>
        <tbody>
            <?php
            while ($loop->have_posts()):
                $loop->the_post();
                $data = get_post_meta(get_the_ID(), 'candidato_metadata', true);


                $tipo_solicitud = $data["tipo_solicitud"];
                $nombre_candidato = $data["nombre_candidato"];
                $apellidos_candidato = $data["apellidos_candidato"];
                $cargo_candidato = $data["cargo_candidato"];
                $empresa_candidato = $data["empresa_candidato"];
                $email_candidato = $data["email_candidato"];
                $telefono_candidato = $data["telefono_candidato"];
                $linkedin_candidato = $data["linkedin_candidato"];
                $web_empresa_candidato = $data["web_empresa_candidato"];
                $meritos_vision = $data["meritos_vision"];
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

                $nombre = "";
                $apellidos = "";
                $mail = "";

                if ($tipo_solicitud == "1") {
                    $nombre = $nombre_nominador;
                    $apellidos = $apellidos_nominador;
                    $mail = $email_nominador;
                } else {
                    $nombre = $nombre_candidato;
                    $apellidos = $apellidos_candidato;
                    $mail = $email_candidato;
                }


                ?>
                <tr class="clickable-link" data-href='url://'>
                    <td><?php echo get_the_date('Y/m/d'); ?></td>
                    <td><a href="/concursoagrosummit/wp-admin/options-general.php?page=candidatos-detalle&candidatoid=<?php echo get_the_ID(); ?>"
                            target="_blank">Ver detalle</a></td>
                    <td><?php echo $nombre; ?></td>
                    <td><?php echo $apellidos; ?></td>
                    <td><?php echo $mail; ?></td>
                    <?php if ($page_now === 'candidatos'): ?>
                        <td>
                            <?php if (!empty($data['foto_directivo'])): ?>
                                <a href="<?php echo $data['foto_directivo']; ?>" target="_blank">Ver foto</a>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if (!empty($data['logo_empresa'])): ?>
                                <a href="<?php echo $data['logo_empresa']; ?>" target="_blank">Ver logo</a>
                            <?php endif; ?>
                        </td>
                    <?php endif; ?>
                    <td class="detalle-box"><a href="">
                            <div class="detalle siguienteronda" data-ronda="0" data-id="<?php echo get_the_ID(); ?>"></div>
                        </a></td>
                </tr>

                <?php
            endwhile; // End of the loop.
            wp_reset_postdata(); ?>
        </tbody>
    </table>

    <!-- Modal content 001-->
    <div id="myModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <table class="form-table" role="presentation">
                <tbody>
                    <tr>
                        <th scope="row" style="width: 60px;">Mover a:</th>
                        <td>
                            <div class="classic-editor-options">
                                <p>
                                    <input type="radio" name="classic-editor-replace" id="rondaPrejurado" value="0">
                                    <label for="classic-editor-classic">Candidatos Inscrito</label>
                                </p>
                            </div>
                        </td>
                        <td>
                            <input type="button" name="submit" id="submit" class="button button-primary btnMover"
                                value="Mover">
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <!-- Modal content -->
    <?php
    $export_url = admin_url('admin-ajax.php?action=excel_pull&the_issue_key=1&the_name=candidaturasactivas');
    $export_url_candidatos = admin_url('admin-ajax.php?action=excel_pull_imgs&the_issue_key=1&the_name=listadocandidatos');
    if (isset($_GET['page'])) {
        $export_url .= '&the_page=' . sanitize_text_field($_GET['page']);
    }
    ?>
    <a href="<?php echo esc_url($export_url); ?>" class="descargaexcel"><img
            src="<?php echo GISTAU__PLUGIN_URL . 'assets/btnExcel.jpg'; ?>" style="width: 48px;" /><span
            style="position: absolute; margin-top: 20px; margin-left: 10px;">Descargar
            listado</span></a>

<br />
    <a href="<?php echo esc_url($export_url_candidatos); ?>" class="descargaexcel"><img
            src="<?php echo GISTAU__PLUGIN_URL . 'assets/btnExcel.jpg'; ?>" style="width: 48px;" /><span
            style="position: absolute; margin-top: 20px; margin-left: 10px;">Descargar
            listado fotos candidatos</span></a>
</div>



<script>
    let id = document.querySelectorAll('.siguienteronda');

    // Get the modal
    let modal = document.getElementById("myModal");

    // Get the button that opens the modal
    let btn = document.getElementById("myBtn");

    // Get the <span> element that closes the modal
    let span = document.getElementsByClassName("close")[0];

    let btnMover = document.querySelector('.btnMover');

    // When the user clicks on <span> (x), close the modal
    span.onclick = function () {
        modal.style.display = "none";
    }

    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function (event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }

    let idPost = 0;
    let idFase = 0;

    id.forEach(el => {
        el.addEventListener('click', function (e) {
            e.preventDefault();
            modal.style.display = "block";
            idPost = el.dataset.id;
            idFase = el.dataset.ronda;
        });
    });

    btnMover.addEventListener('click', function (e) {
        let rondaPrejurado = document.querySelector('#rondaPrejurado').value;
        // let descartadoSecretaria = document.querySelector('#descartadoSecretaria').value;
        let valueCheck = rondaPrejurado;
        // (document.querySelector('#rondaPrejurado').checked) ? valueCheck = rondaPrejurado : valueCheck = descartadoSecretaria;
        jQuery.ajax({
            type: "post",
            url: '<?php echo admin_url('admin-ajax.php'); ?>',
            data: "action=updatefaseunotop100&id=" + idPost + "&fase=" + valueCheck + "&nonce=<?php echo wp_create_nonce('my-ajax-nonce'); ?>",
            success: function (result) {
                modal.style.display = "none";
                location.reload();
            }
        });
    });


    let idDetalleCandidatura = document.querySelectorAll('.detalleCandidatura');
    idDetalleCandidatura.forEach(el => {
        el.addEventListener('click', function () {
            jQuery.ajax({
                type: "post",
                url: '<?php echo admin_url('admin-ajax.php'); ?>',
                data: "action=detallecandidato&id=" + el.dataset.id + "&nonce=<?php echo wp_create_nonce('my-ajax-nonce'); ?>",
                success: function (result) {
                    console.log(result);
                }
            });
        });
    });

    // jQuery(document).ready(function($){
    //     $( document ).on( 'click', '.descargaexcel', function(event){

    //         event.preventDefault();

    //         // Use ajax to do something...
    //         var postData = {
    //             action: 'excel_pull',
    //             the_issue_key: 0,
    //         }

    //         //Ajax load more posts
    //         $.ajax({
    //             type: "GET",
    //             data: postData,
    //             url: '<?php echo admin_url('admin-ajax.php'); ?>?action=csv_pull',
    //             success: function (response) {
    //                 alert( response );
    //                 // window.location.href = response.url;
    //             }
    //             //This fires when the ajax 'comes back' and it isn't valid json
    //         }).fail(function (data) {
    //             console.log(data);
    //         });

    //     });

    // });
</script>
