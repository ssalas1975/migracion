<?php
/**
* Template Name: Formularios de Inscripción
*
* @package WordPress
*/

if( jevelin_page_layout() == 'sidebar-right' || jevelin_page_layout() == 'sidebar-left' ) :
  $layout_sidebar = esc_attr( jevelin_page_layout() );
endif;

$class = '';
if( function_exists('fw_ext_page_builder_is_builder_post') && !fw_ext_page_builder_is_builder_post( get_queried_object_id() ) ) {
 $class = ' page-default-content';
}

add_action('wp_head', 'head_function_arquia');
function head_function_arquia() {
    if ( is_page() ) {
        echo '
        <!-- Font Awesome -->
        <link
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"
        rel="stylesheet"
        />
        <!-- Google Fonts -->
        <link
        href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap"
        rel="stylesheet"
        />
        <!-- MDB -->
        <link
        href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.3.2/mdb.min.css"
        rel="stylesheet"
        />
        ';
    }
}

add_action('wp_footer', 'footer_function_arquia');
function footer_function_arquia() {
    if ( is_page() ) {
        echo '
       <!-- MDB -->
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.3.2/mdb.umd.min.js"></script>
        ';
    }
}


get_header();
?>

<div style="
    display: flex;
    flex-direction: column;
    justify-items: center;
    align-items: center;
    justify-content: center;
    padding-right: 150px;
    padding-left: 150px;
">
<h2 style="font-size: 35px; color: #FBBA00; font-family:Neue Haas Grotesk Display Pro;">PREMIOS FARMACÉUTICOS CONTIGO ARQUIA BANCA</h2> 
<p style="
text-align: center;
font-size: 20px;
font-family: Neue Haas Grotesk Display Pro;
letter-spacing: 0px;
color: #000000;
padding-right: 90px;
    padding-left: 90px;
">En la I Edición de los Premios Farmacéuticos CONTIGO Arquia Banca se reconocen proyectos, iniciativas y trayectorias profesionales en el ámbito farmacéutico. Las inscripciones deben realizarse en idioma español.</p>
<p style="
text-align: center;
font-size: 20px;
font-weight: bold;
font-family: Neue Haas Grotesk Display Pro;
letter-spacing: 0px;
color: #000000;
padding-right: 150px;
    padding-left: 150px;
">PARA OPTAR AL PREMIO ES NECESARIO COMPLETAR ESTE FORMULARIO ANTES DEL 21 DE OCTUBRE A LAS 12:00H</p>
</div>
<!-- Tabs navs -->
<ul class="nav nav-tabs nav-fill mb-3" id="ex1" role="tablist">
  <li class="nav-item" role="presentation">
    <a
      data-mdb-tab-init
      class="nav-link active"
      id="ex2-tab-1"
      href="#ex2-tabs-1"
      role="tab"
      aria-controls="ex2-tabs-1"
      aria-selected="true"
      >Trayectoria destacada</a
    >
  </li>
  <li class="nav-item" role="presentation">
    <a
      data-mdb-tab-init
      class="nav-link"
      id="ex2-tab-2"
      href="#ex2-tabs-2"
      role="tab"
      aria-controls="ex2-tabs-2"
      aria-selected="false"
      >Compromiso comunitario</a
    >
  </li>
  <li class="nav-item" role="presentation">
    <a
      data-mdb-tab-init
      class="nav-link"
      id="ex2-tab-3"
      href="#ex2-tabs-3"
      role="tab"
      aria-controls="ex2-tabs-3"
      aria-selected="false"
      >Proyecto singular</a
    >
  </li>
</ul>
<!-- Tabs navs -->

<!-- Tabs content -->
<div class="tab-content" id="ex2-content">
  <div
    class="tab-pane fade show active"
    id="ex2-tabs-1"
    role="tabpanel"
    aria-labelledby="ex2-tab-1"
  >
<!-- TRAYECTORIA DESTACADA -->
<h2 style="
  text-align: center;
  font-size:30px;
font-family: Neue Haas Grotesk Display Pro;
letter-spacing: 0px;
color: #FBBA00;
opacity: 1;
  ">CATEGORÍA COMPROMISO COMUNITARIO</h2>
  <p style="
  text-align: center;
  font-size:30px;
font: Neue Haas Grotesk Display Pro;
letter-spacing: 0px;
color: #000000;
opacity: 1;
padding-right: 150px;
    padding-left: 150px;
  ">
  Esta categoría reconoce y premia a aquellos farmacéuticos con una carrera larga y destacada, que hayan mantenido un alto nivel profesional y de excelencia a lo largo de su servicio. Se valorarán los años de servicio en la profesión farmacéutica, contribuciones profesionales significativas, el impacto positivo en la comunidad y en sus pacientes, y los reconocimientos y premios que haya recibido.
  </p>
  <div style="
    display: flex;
    justify-content: center;
">
    <p style="
      text-align: center;
      font-size:30px;
      font: Neue Haas Grotesk Display Pro;
      letter-spacing: 0px;
      color: #FFFFFF;
      opacity: 1;
      background: #FBBA00 0% 0% no-repeat padding-box;
      border-radius: 34px;
      opacity: 1;
      width: 271px;
      height: 67px;
      padding-top: 8px;
      ">
    Más información  
  </p>
  </div>
  <?php echo do_shortcode( '[contact-form-7 id="4633" title="trayectoria-destacada"]' ); ?>
<!-- TRAYECTORIA DESTACADA -->

  </div>
  <div
    class="tab-pane fade"
    id="ex2-tabs-2"
    role="tabpanel"
    aria-labelledby="ex2-tab-2"
  >
  <!-- COMPROMISO COMUNITARIO -->
  <h2 style="
  text-align: center;
  font-size:30px;
font-family: Neue Haas Grotesk Display Pro;
letter-spacing: 0px;
color: #FBBA00;
opacity: 1;
  ">CATEGORÍA COMPROMISO COMUNITARIO</h2>
  <p style="
  text-align: center;
  font-size:30px;
font: Neue Haas Grotesk Display Pro;
letter-spacing: 0px;
color: #000000;
opacity: 1;
padding-right: 150px;
    padding-left: 150px;
  ">
  Esta categoría reconoce y premia a las farmacias que han demostrado un compromiso excepcional con sus comunidades. El objetivo es celebrar el verdadero espíritu de servicio y la contribución significativa que han hecho. Se valorará cómo cada farmacia ha sido un pilar de apoyo y ayuda para todos.
  </p>
  <div style="
    display: flex;
    justify-content: center;
">
    <p style="
      text-align: center;
      font-size:30px;
      font: Neue Haas Grotesk Display Pro;
      letter-spacing: 0px;
      color: #FFFFFF;
      opacity: 1;
      background: #FBBA00 0% 0% no-repeat padding-box;
      border-radius: 34px;
      opacity: 1;
      width: 271px;
      height: 67px;
      padding-top: 8px;
      ">
    Más información  
  </p>
  </div>
  <?php echo do_shortcode( '[contact-form-7 id="4631" title="compromiso-comunitario"]' ); ?>
  <!-- COMPROMISO COMUNITARIO -->
  </div>
  <div
    class="tab-pane fade"
    id="ex2-tabs-3"
    role="tabpanel"
    aria-labelledby="ex2-tab-3"
  >
    <!-- PROYECTO SINGULAR -->
    <h2 style="
  text-align: center;
  font-size:30px;
font-family: Neue Haas Grotesk Display Pro;
letter-spacing: 0px;
color: #FBBA00;
opacity: 1;
  ">CATEGORÍA COMPROMISO COMUNITARIO</h2>
  <p style="
  text-align: center;
  font-size:30px;
font: Neue Haas Grotesk Display Pro;
letter-spacing: 0px;
color: #000000;
opacity: 1;
padding-right: 150px;
    padding-left: 150px;
  ">
  Esta categoría reconoce y premia a las farmacias que han llevado a cabo proyectos innovadores o han implementado soluciones novedosas. Se valorará su impacto significativo en la atención al paciente, en la gestión farmacéutica o en la promoción de la salud
  </p>
  <div style="
    display: flex;
    justify-content: center;
">
    <p style="
      text-align: center;
      font-size:30px;
      font: Neue Haas Grotesk Display Pro;
      letter-spacing: 0px;
      color: #FFFFFF;
      opacity: 1;
      background: #FBBA00 0% 0% no-repeat padding-box;
      border-radius: 34px;
      opacity: 1;
      width: 271px;
      height: 67px;
      padding-top: 8px;
      ">
    Más información  
  </p>
  </div>
  <?php echo do_shortcode( '[contact-form-7 id="4632" title="proyecto-singular"]' ); ?>
    <!-- PROYECTO SINGULAR -->
  </div>
</div>
<!-- Tabs content -->


<?php if( isset($layout_sidebar) && $layout_sidebar ) : ?>
		<div id="sidebar" class="<?php echo esc_attr( $layout_sidebar ); ?>">
			<?php get_sidebar(); ?>
		</div>
	<?php endif; ?>

<?php
get_footer();
