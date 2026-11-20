<style>
body {font-family: Arial, Helvetica, sans-serif;}

/* The Modal (background) */
.modal {
  display: none; /* Hidden by default */
  position: fixed; /* Stay in place */
  z-index: 1; /* Sit on top */
  padding-top: 200px; /* Location of the box */
  left: 0;
  top: 0;
  width: 100%; /* Full width */
  height: 100%; /* Full height */
  overflow: auto; /* Enable scroll if needed */
  background-color: rgb(0,0,0); /* Fallback color */
  background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
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
    <link rel="stylesheet" href="<?php echo GISTAU__PLUGIN_URL.'templates/candidateTemplate/'; ?>styleCandidate.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script src="<?php echo GISTAU__PLUGIN_URL.'templates/candidateTemplate/'; ?>codeCandidate.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@400;700&display=swap" rel="stylesheet">
    <h1>RONDA FINALISTAS</h1>
    <table id="tableData" class="display cell-border row-border" style="width:100%">
        <thead>
            <tr >
                <th>FECHA DE INSCRIPCIÓN</th>
                <th>VER DETALLE</th>
                <th>NOMBRE</th>
                <th>APELLIDOS</th>
                <th>CATEGORÍA</th>
                <th>TÍTULO</th>
                <th>MEDIO PUBLICACIÓN</th>
                <th class="no-sort">MOVER</th>
            </tr>
        </thead>
        <tbody>
            <?php
            while ($loop->have_posts()) : $loop->the_post();
$data = get_post_meta(get_the_ID(), 'candidato_metadata', true); ?>
            <tr class="clickable-link" data-href='url://'>
                <td><?php echo get_the_date('Y/m/d'); ?></td>
                <td><a href="/premiodavidgistau/wp-admin/options-general.php?page=candidatos-detalle&candidatoid=<?php echo get_the_ID(); ?>" target="_blank">Ver detalle</a></td>
                <td><?php echo $data["Nombre"][0]; ?></td>
                <td><?php echo $data["Apellidos"][0]; ?></td>
                <td><?php echo is_array($data["Categoria"]) ? $data["Categoria"][0] : $data["Categoria"]; ?></td>
                <td><?php echo $data["TitulodelTrabajo"]; ?></td>
                <td><?php echo $data["MedioPublicacion"]; ?></td>
                <td class="detalle-box"><a href=""><div class="detalle siguienteronda" data-ronda="1" data-id="<?php echo get_the_ID(); ?>"></div></a></td>
            </tr>
            
            <?php
            endwhile; // End of the loop.
            wp_reset_postdata(); ?>
        </tbody>
    </table>

    <!-- Modal content 004-->
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
                                    <input type="radio" name="classic-editor-replace" id="rondaPrejurado" value="2" checked="">
                                    <label for="classic-editor-classic">Ronda prejurado</label>
                                </p>
                            </div>
                        </td>
                        <td>
                            <input type="button" name="submit" id="submit" class="button button-primary btnMover" value="Mover">
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <!-- Modal content -->
    <a href="<?php echo admin_url('admin-ajax.php?action=excel_pull&the_issue_key=4&the_name=rondafinalistas'); ?>" class="descargaexcel"><img src="<?php echo GISTAU__PLUGIN_URL . 'assets/btnExcel.jpg'; ?>" style="width: 48px;"/><span style="position: absolute; margin-top: 20px; margin-left: 10px;">Descargar listado</span></a>
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
    span.onclick = function() {
        modal.style.display = "none";
    }

    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }

    let idPost = 0;
    let idFase = 0;

    id.forEach( el => {
        el.addEventListener('click', function(e){
            e.preventDefault();
            modal.style.display = "block";
            idPost = el.dataset.id;
            idFase = el.dataset.ronda;
        });
    });

    btnMover.addEventListener('click', function(e) {
        let rondaPrejurado = document.querySelector('#rondaPrejurado').value;
        let valueCheck = rondaPrejurado;
        jQuery.ajax({
            type: "post",
            url: '<?php echo admin_url('admin-ajax.php');?>',
            data: "action=updatefaseuno&id="+idPost+"&fase="+valueCheck+"&nonce=<?php echo wp_create_nonce('my-ajax-nonce');?>",
            success: function(result){
                modal.style.display = "none";
                location.reload();
            }
        });
    });


    let idDetalleCandidatura = document.querySelectorAll('.detalleCandidatura');
    idDetalleCandidatura.forEach( el => {
        el.addEventListener('click', function(){
            jQuery.ajax({
                type: "post",
                url: '<?php echo admin_url('admin-ajax.php'); ?>',
                data: "action=detallecandidato&id="+el.dataset.id+"&nonce=<?php echo wp_create_nonce('my-ajax-nonce'); ?>",
                success: function(result){
                    console.log(result);
                }
            });
        });
    });
</script>
