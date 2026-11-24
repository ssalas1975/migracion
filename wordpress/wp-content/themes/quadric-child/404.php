<?php get_header(); ?>

  <?php get_template_part( 'title' ); ?>

  <div class="edgtf-container">
  <?php do_action('edgtf_quadric_after_container_open'); ?>
    <div class="edgtf-container-inner edgtf-404-page">
      <div class="edgtf-page-not-found">
        <?php 
        print do_shortcode('[edgtf_number number="404" position="left" font_size="110" font_family="Raleway" font_weight="700" letter_spacing="1"]');
        print do_shortcode('[edgtf_separator type="transparent" top_margin="-35" bottom_margin="0"]');        
        ?>
        <h2>          
          <?php if(edgtf_quadric_options()->getOptionValue('404_title')){
            echo esc_html(edgtf_quadric_options()->getOptionValue('404_title'));
          }
          else{?>           
            <?php esc_html_e('La página que estás buscando no existe', 'quadric');
          } ?>
        </h2>
        <h4>
          <?php if(edgtf_quadric_options()->getOptionValue('404_text')){
            echo esc_html(edgtf_quadric_options()->getOptionValue('404_text'));
          }
          else{
            esc_html_e('La página que estás buscando no ha podido ser encontrada. Es posible que haya sido trasladada o eliminada. Te recomendamos volver a la home y ver si puedes encontrar lo que estás buscando y bien utilizar el buscador.', 'quadric');
          } ?>
        </h4>
        <?php
          $params = array();
          if (edgtf_quadric_options()->getOptionValue('404_back_to_home')){
            $params['text'] = edgtf_quadric_options()->getOptionValue('404_back_to_home');
          }
          else{
            $params['text'] = "Back to Home Page";
          }
          $params['link'] = esc_url(home_url('/'));
          $params['target'] = '_self';
        echo edgtf_quadric_execute_shortcode('edgtf_button',$params);?>
      </div>
    </div>
    <?php do_action('edgtf_quadric_before_container_close'); ?>
  </div>
<?php get_footer(); ?>