<?php
$urls = get_option('patron_url');

echo '
<!--link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css"-->
<link rel="stylesheet" href="'. plugin_dir_url( __DIR__ )  . 'assets/css/datatables.css">
<style>
.dataTables_info {
	display: none;
}
.container {
	margin-top: 20px;
}

.dataTables_wrapper, div.row {
	display: contents;
}

.dataTables_wrapper, div.row, div.col-sm-12 {
	margin-bottom: 10px;;
}

</style>

<div id="wrap">
			<div class="container">
			<input type="hidden" class="ajaxurl" value="'.admin_url( "admin-ajax.php" ).'" />
				<table cellpadding="0" cellspacing="0" border="0" class="datatable table table-striped table-bordered">
					<thead>
						<tr>
							<th>#</th>
							<th>Url</th>
							<th>#</th>
							<th><button type="button" class="btn btn-outline-dark" data-toggle="modal" data-target="#newurl">+</button></th>
						</tr>
					</thead>
					<tbody>';
					foreach( $urls as $url) {
						echo '<tr class="gradeX">
							<td>'.$url["id"].'</td>
							<td>'.$url["url"].'</td>
                            <td>
                                <span class="btn dashicons-before dashicons-edit btnEditar" data-url="'.$url['url'].'" data-id="'.$url['id'].'" data-toggle="modal" data-target="#editurl"></span>
                                <span class="btn dashicons-before dashicons-trash btnBorrar" data-id="'.$url['id'].'"></span>
                            </td>
							<td class="center"><button type="button" class="btn btn-outline-dark btn_create_var" data-toggle="modal" data-target="#newvariables" data-ulr="'.$url["url"].'" data-id="'.$url['id'].'" >Añadir Variables</button></td>
						</tr>';
					}
echo 			   '</tbody>
					<tfoot>
						<tr>
							<th>#</th>
							<th>Url</th>
							<th>#</th>
							<th><button type="button" class="btn btn-outline-dark" data-toggle="modal" data-target="#newurl">+</button></th>
						</tr>
					</tfoot>
				</table>
			</div>
        </div>

        <script src="'. plugin_dir_url( __DIR__ )  . 'assets/js/jquery.dataTables.min.js"></script>
		<script src="'. plugin_dir_url( __DIR__ )  . 'assets/js/datatables.js"></script>
		<script type="text/javascript">
		$(document).ready(function() {
			$(".datatable").dataTable({
				"sPaginationType": "bs_full",
			});
			$(".datatable").each(function(){
				var datatable = $(this);
				var search_input = datatable.closest(".dataTables_wrapper").find("div[id$=_filter] input");
				search_input.attr("placeholder", "Search");
				search_input.addClass("form-control input-sm");
				var length_sel = datatable.closest(".dataTables_wrapper").find("div[id$=_length] select");
				length_sel.addClass("form-control input-sm");
			});
		});
		</script>

		    <!-- ventanas modales -->
    <div class="modal fade" id="newurl" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalCenterTitle">Nueva Url</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <label for="basic-url">URL</label>
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon3">url</span>
                </div>
                <input type="text" class="form-control" id="taggin-url" aria-describedby="basic-addon3">
            </div>
            <label for="basic-url">Patrón</label>
            <div class="input-group mb-3">
            <div class="input-group-prepend">
                <div class="input-group-text">
                <input type="checkbox" class="usarpatron" aria-label="Usar patron">
                </div>
            </div>
            <input type="text" class="form-control" placeholder="Expresión regular" id="regular-expresion" aria-label="Regular expression">
            </div>
                <div class="jumbotron">
                <p class="lead">[texto].</p>
                <p class="lead">[numero].</p>
                <hr class="my-4">
                </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
            <button type="button" class="btn btn-primary btnguardarurls" data-dismiss="modal">Guardar</button>
        </div>
        </div>
    </div>
    </div>

    <div class="modal fade" id="newvariables" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalCenterTitle">Añadir Variables: <span class="nomUrl"></span></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true" class="closecross">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text">Variable | Posición</span>
                </div>
                <input type="hidden" class="idtotal">
                <input type="text" class="form-control valueVar">
                <input type="text" class="form-control valueVal">
                <input class="btn btn-primary addNew" type="button" value="+" style="margin-left: 20px;">
            </div>
            <div class="primarycontent"></div>
            <div class="jumbotron">
            <p class="lead">En el campo posición indicar la posición que ocupa la variable en la url</p>
            <p class="lead">Para la fecha de publicación introducir el valor: [aaaammdd][publicacion].</p>
            <p class="lead">Para la fecha de modificación introducir el valor: [aaaammdd][modificacion].</p>
            <p class="lead">Para los valores literales introducir los valores indicagos en el tagging plan.</p>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary btnclosemodal" data-dismiss="modal">Cancelar</button>
            <button type="button" class="btn btn-primary btnguardartags" data-dismiss="modal">Guardar</button>
        </div>
        </div>
    </div>
    </div>



    <div class="modal fade" id="editurl" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalCenterTitle">Editar URL: <span class="textUrl"></span></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true" class="closecross">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text">Url</span>
                </div>
                <input type="hidden" class="idEditUrl">
                <input type="text" class="form-control valEditUrl">
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
            <button type="button" class="btn btn-primary actualizarUrl" data-dismiss="modal">Guardar</button>
        </div>
        </div>
    </div>
    </div>

';
