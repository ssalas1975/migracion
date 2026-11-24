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
        <div class="header-candidacy">
                <h1>CANDIDATURAS</h1>
                <div class="logo-container">
                    <img src="/wp-content/uploads/sites/25/2024/10/logo-arquia-premiosfarmaceuticos.svg" alt="Premios Farmacéuticos Contigo Arquia" class="logo">
                </div>
            </div>

            <div class="category-section">
                <h2>CATEGORÍA<br>TRAYECTORIA DESTACADA</h2>
                <p>La información alojada en este portal es confidencial y para uso exclusivo de las personas implicadas en las deliberaciones de los Premios Farmacéuticos Contigo Arquia.</p>
            </div>
        <div id="tableData_wrapper" class="dataTables_wrapper no-footer">
            <div class="dataTables_info" id="tableData_info" role="status" aria-live="polite"><strong>Candidatos Trayectoria Destacada</strong></div>
            <table id="tableData" class="display cell-border row-border nowrap tableData" style="width:100%">
                <thead>
                    <tr >
                            <th>NOMBRE</th>
                            <th>APELLIDOS</th>
                            <th>EMAIL</th>
                            <th>CATEGORÍA</th>
                        <th class="no-sort">DETALLE</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($loop->have_posts()) : $loop->the_post();
                    $data = get_post_meta(get_the_ID(), 'candidato_metadata', true);
                    if($data['type'] == 'trayectoria-destacada'){
                    ?>
                    <tr class="clickable-link" data-href='url://'>
                        <td><?php echo $data['nombrefarmacia']; ?></td>
                                <td><?php echo $data["apellidos"]; ?></td>
                                <td><?php echo $data["email"]; ?></td>
                                <td><?php echo $data['type']; ?></td>
                        <td class="detalle-box"><a href="<?php echo get_site_url().'/'.get_option('slugDetallCandidato').'?id='.get_the_ID(); ?>"><div class="detalle"></div></a></td>
                    </tr>
                    <?php
                    };
                    endwhile; // End of the loop.
                    wp_reset_postdata();
                    ?>   
                </tbody>
            </table>
            <span style="margin-top: -80px; margin-left: 20px; float: left; z-index: 1; position: relative" class="perico">
                <div class="dataTables_info" id="tableData_info" role="status" aria-live="polite" style="font-weight: bold;color: #FB1717;top: -9px;">
                    <a class="link-candidacy" href="<?php echo plugin_dir_url('voc-gistau').'voc-gistau/admin/functions.php'; ?>?action=wp_ajax_nopriv_excel_pull_ext&amp;the_issue_key=4&amp;the_name=candidaturasactivas">Descargar listado</a> 
                    <a href="<?php echo plugin_dir_url('voc-gistau').'voc-gistau/admin/functions.php'; ?>?action=wp_ajax_nopriv_excel_pull_ext&amp;the_issue_key=4&amp;the_name=candidaturasactivas">
                        <img src="https://www.premioscontigoarquiabanca.com/wp-content/uploads/sites/15/2020/09/icono-xls.png" alt="">
                    </a>
                </div>
            </span>
		</main><!-- #main -->
	</div><!-- #primary -->
</div><!-- .wrap -->