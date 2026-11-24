<?php
/**
* Template Name: Congreso - Programa
 */

get_header(); ?>

<?php get_template_part( 'template-parts/header', 'congreso' ); ?>

<?php 



    $args = Array(
        "post_type"=>"programas",
        "posts_per_page"=>-1,
        'meta_query' => array(
            array(
                'key' => 'rel_cumbre',
                'value' => $num_cumbre,
                'compare' => '=',
            ),
        ),
        'meta_key' => 'fecha',
        'orderby'   => 'meta_value',
        'order' => 'ASC',
    );
    $query = new WP_Query($args);

 ?>

<div class="container clear congresoPlantilla">    
    <div class="row">
        <section id="primary" class="content-area col-sm-12">
            <main id="main" class="site-main" role="main">
                <div class="contenido-blanco">

                        <h1 class="entry-title">
                            <span><?php the_title(); echo (ICL_LANGUAGE_CODE=='es')?' de':' of'; ?> </span><?php echo get_the_title($num_cumbre) ?>
                        </h1>

						<?php if($año==2019): ?>
							<iframe src="<?php echo (ICL_LANGUAGE_CODE=='es')? 'https://congreso.madridfusion.net/madridfusion19/es/custom/MFProgramaframe?tipo=programa':'https://congreso.madridfusion.net/madridfusion19/en/custom/MFProgramaframe?tipo=programa';?>" frameborder="0" style="width:100%;" height="3300"></iframe>
						<?php endif; ?> 

						<?php if($año==2020): ?>
							<iframe src="<?php echo (ICL_LANGUAGE_CODE=='es')? 'https://congreso.madridfusion.net/madridfusion20/es/custom/MFProgramaframe?tipo=programa':'https://congreso.madridfusion.net/madridfusion20/en/custom/MFProgramaframe?tipo=programa';?>" frameborder="0" style="width:100%;" height="3300"></iframe>
						<?php endif; ?> 

						 

                        <?php 
                            $i=1; $dias = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sábado");
                            $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
                        ?>
                        <ul class="pestanas">
                            <?php while ( $query->have_posts() ) : $query->the_post(); ?>
                                <li class="fecha <?php echo 'dia'.$i; echo ($i==1)?' activo':''; $i++;?> ">
                                	<h2>
                                    <?php 
                                        $fecha=strtotime(get_post_meta($post->ID,'fecha',true)); 
                                        echo (ICL_LANGUAGE_CODE=='es')? date("d",$fecha).' de '.$meses[date("n",$fecha)-1].' ('.$dias[date("w",$fecha)].')' : date("d",$fecha).' '.date("F (l)",$fecha) ; 
                                    ?>
                                    </h2>
                                    
                                </li>
                            <?php  endwhile;?>
                        </ul>

                        <?php $i=1;  while ( $query->have_posts() ) : $query->the_post(); ?>
                            <div class="programa <?php echo 'programa'.$i; echo ($i==1)?' activo':''; $i++;?> ">
                                    <?php echo wp_specialchars_decode(apply_filters( 'the_content', get_the_content())); ?>
                            </div>
                        <?php  endwhile; wp_reset_postdata();?>

                    


                </div>
            </main>
        </section>
<?php
get_footer();