<?php

/******* GRÁFICOS CHART *******/
vc_add_shortcode_param( 'input_number', 'my_param_settings_field' );

function my_param_settings_field( $settings, $value ) {
    return '<div class="my_param_block">'
    .'<input name="' . esc_attr( $settings['param_name'] ) . '" class="wpb_vc_param_value wpb-textinput ' .
    esc_attr( $settings['param_name'] ) . ' ' .
    esc_attr( $settings['type'] ) . '_field" type="number" value="' . esc_attr( $value ) . '" />' .
    '</div>'; // This is html markup that will be outputted in content elements edit form }
}
add_shortcode( 'vocento_graficos_chart', 'graficos_chart_function' ); 
function graficos_chart_function( $atts, $content ) {
    extract( shortcode_atts( array(
        'animacionway'    => '',
        'el_class'    => '',
        'values'      => '',
    ), $atts ) );
    $html = "";

    //echo rawurldecode($values);
    $json = json_decode(rawurldecode($values), true);
	$i = 0;
    foreach($json as $res)
    {
        echo $res['porcentaje'];
        $rand = rand(1, 200); 
        $porcentaje = $res['porcentaje'];
        $texto1 = $res['texto1'];
        $texto2 = $res['texto2'];
        $valorCalculado = (615*$porcentaje)/100;
        $porciento = $valorCalculado.', 615';
        $html .= '<div id="contentvocentochar"><div class="infografia'.$el_class.'">';
        if(isset($atts['animacionway'])){

            $html .= '<span class="content_anim5 infographic-circle"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 200" enable-background="new 0 0 200 200"><circle class="circle" cx="100" cy="100" r="98" stroke="#c4c8cb" stroke-width="1" stroke-linecap="round" stroke-miterlimit="10" fill="none"></circle><path d="M2 100a98 98 0 1 1 196 0a98 98 0 1 1 -196 0 " class="progress path'.$rand.'" stroke="#507991" stroke-width="2" stroke-linecap="round" stroke-miterlimit="1" fill="none"></path></svg></span>';
        }else{
            $html .= '<span class="content_anim5 infographic-circle animated fadeIn active"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 200" enable-background="new 0 0 200 200"><circle class="circle" cx="100" cy="100" r="98" stroke="#c4c8cb" stroke-width="1" stroke-linecap="round" stroke-miterlimit="10" fill="none"></circle><path d="M2 100a98 98 0 1 1 196 0a98 98 0 1 1 -196 0 " class="progress path'.$rand.'" stroke="#507991" stroke-width="2" stroke-linecap="round" stroke-miterlimit="1" fill="none"></path></svg></span>';
        }
        $html .= '<p class="num">'.$porcentaje.'%</p><p>'.$texto1.'</p><p>'.$texto2.'</p>';
        $html .= '</div></div>';

	    
        if(isset($atts['animacionway'])){
            $html.= "<script>
                             $(document).ready(function(){
                                    $('span.content_anim5.infographic-circle ').waypoint(function () {
                                                $(this).addClass('animated fadeIn active'); 

			                            $('.path".$rand."').css('stroke-dasharray','".$valorCalculado.",610');
		
                                    }, { offset: '50%' });
                           
                             });
                </script>";
        }else{
			  $html.= "<script>
                             $(document).ready(function(){
                                
			                            $('.path".$rand."').css('stroke-dasharray','".$valorCalculado.",610');
                               });
                </script>";
        
        }
		$i ++;
    }

    return $html;
	
}


/******* GRÁFICOS CHART *******/

?>