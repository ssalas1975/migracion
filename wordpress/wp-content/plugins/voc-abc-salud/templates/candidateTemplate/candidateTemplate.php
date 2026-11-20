<?php
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

?>
<style>
.dataTables_wrapper .dataTables_scroll div.dataTables_scrollBody>table>thead>tr>th, .dataTables_wrapper .dataTables_scroll div.dataTables_scrollBody>table>thead>tr>td, .dataTables_wrapper .dataTables_scroll div.dataTables_scrollBody>table>tbody>tr>th, .dataTables_wrapper .dataTables_scroll div.dataTables_scrollBody>table>tbody>tr>td {
    vertical-align: middle;
    max-width: 156px !important;
    overflow: hidden !important;
}
</style>
<div class="wrap">
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
        <div id="tableData_wrapper" class="dataTables_wrapper no-footer">
            <div class="dataTables_info" id="tableData_info" role="status" aria-live="polite"><strong>La información alojada en este portal es confidencial y para uso exclusivo de las personas implicadas en las deliberaciones del Premio de Periodismo David Gistau..</strong></div>
            <table id="tableData" class="display cell-border row-border nowrap tableData" style="width:100%">
                <thead>
                    <tr >
                        <th>NOMBRE</th>
                        <th>APELLIDOS</th>
                        <th>CATEGORÍA</th>
                        <th>TÍTULO</th>
                        <th>MEDIO PUBLICACIÓN</th>
                        <th class="no-sort">DETALLE</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($loop->have_posts()) : $loop->the_post();
                    $data = get_post_meta(get_the_ID(), 'candidato_metadata', true);
                    // var_dump($data);
                    ?>
                    <tr class="clickable-link" data-href='url://'>
                        <td><?php echo $data["Nombre"][0]; ?></td>
                        <td><?php echo $data["Apellidos"][0]; ?></td>
                        <td><?php echo $data["Categoria"]; ?></td>
                        <td><?php echo $data["TitulodelTrabajo"]; ?></td>
                        <td><?php echo $data["MedioPublicacion"]; ?></td>
                        <td class="detalle-box"><a href="<?php echo get_site_url().'/'.get_option('slugDetallCandidato').'?id='.get_the_ID(); ?>"><div class="detalle"></div></a></td>
                    </tr>
                    <?php
                    endwhile; // End of the loop.
                    wp_reset_postdata();
                    ?>   
                </tbody>
            </table>
            <span style="margin-top: -80px; margin-left: 20px; float: left; z-index: 1; position: relative" class="perico">
                <div class="dataTables_info" id="tableData_info" role="status" aria-live="polite" style="font-weight: bold;color: #FB1717;top: -9px;">
                    <a style="color:#BF1717" href="<?php echo plugin_dir_url('voc-gistau').'voc-gistau/admin/functions.php'; ?>?action=wp_ajax_nopriv_excel_pull_ext&amp;the_issue_key=4&amp;the_name=candidaturasactivas">Descargar listado</a> 
                    <a href="<?php echo plugin_dir_url('voc-gistau').'voc-gistau/admin/functions.php'; ?>?action=wp_ajax_nopriv_excel_pull_ext&amp;the_issue_key=4&amp;the_name=candidaturasactivas">
                        <img src="https://www.premiodavidgistau.com/wp-content/uploads/sites/15/2020/09/icono-xls.png" alt="">
                    </a>
                </div>
            </span>
		</main><!-- #main -->
	</div><!-- #primary -->
</div><!-- .wrap -->