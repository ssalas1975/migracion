<?php
class VocentoFormatoPublicidad
{
	public function __construct()
    {
		//generar shortcode
		if ( is_plugin_active( 'js_composer/js_composer.php' ) ) {
			vc_map( array(
				"name" => __("VCM Formatos Publicidad"),
				'base' => 'vcm_formatos',
				"icon" => get_template_directory_uri() . "/includes/vocentoIcon.png",
				'category' => __( 'VCM Formatos', 'js_composer' ),
				'description' => __( 'VCM Formatos.', 'js_composer' ),
				'params' => array(
					
				),
			));
		}
	
		add_shortcode( 'vcm_formatos', array( $this, 'vcm_formatospublicidad' ) );
		
    }
	
	
	public function vcm_formatospublicidad()
	{
		wp_enqueue_style( 'vcmformatos_styles', plugins_url('/css/vcm_formatos.css', __FILE__)  );
		wp_enqueue_script( 'vcmformatos-script', plugins_url( '/js/custom.js', __FILE__ ) );
		
		
		$args = array(
			'post_type' => 'vcm-formatos',
			'order'     => 'ASC'
		);
		
		$contador = 0;
		
		$posts_array = get_posts( $args );
		
		$html = '<div class="dformats-container">
					<div class="dformats-left">
						<ul class="dformats-list">';
		
		foreach( $posts_array as $post )
		{
			

			if( 0 == $contador )
			{
				$html .= '<li class="active" data-img="'.wp_get_attachment_url( get_post_thumbnail_id($post->ID), 'full' ).'" data-alt="'.$post->post_title.'">
                        <span class="dformats-title"><span class="dformats-icon">+</span>'.$post->post_title.'</span>
                        <ul class="dformats-info">';
                            if( null != get_post_meta( $post->ID, 'tamaño', true )){ $html .= '<li><span>Tamaño:</span> '.get_post_meta( $post->ID, 'tamaño', true ).'</li>'; }
							if( null != get_post_meta( $post->ID, 'peso_max', true )){ $html .= '<li><span>Peso máx:</span> '.get_post_meta( $post->ID, 'peso_max', true ).'</li>'; }
							if( null != get_post_meta( $post->ID, 'formato', true )){ $html .= '<li><span>Formato:</span> '.get_post_meta( $post->ID, 'formato', true ).'</li>'; }
							if( null != get_post_meta( $post->ID, 'tecnologia', true )){ $html .= '<li><span>Tecnología:</span> '.get_post_meta( $post->ID, 'tecnologia', true ).'</li>'; }
                $html .='   <li><span>Observaciones:</span> '.$post->post_content.'</li>       
                        </ul>
                    </li>';
			}else{
				$html .= '<li data-img="'.wp_get_attachment_url( get_post_thumbnail_id($post->ID), 'full' ).'" data-alt="'.$post->post_title.'">
                        <span class="dformats-title"><span class="dformats-icon">+</span>'.$post->post_title.'</span>
                        <ul class="dformats-info">';
                            if( null != get_post_meta( $post->ID, 'tamaño', true )){ $html .= '<li><span>Tamaño:</span> '.get_post_meta( $post->ID, 'tamaño', true ).'</li>'; }
							if( null != get_post_meta( $post->ID, 'peso_max', true )){ $html .= '<li><span>Peso máx:</span> '.get_post_meta( $post->ID, 'peso_max', true ).'</li>'; }
							if( null != get_post_meta( $post->ID, 'formato', true )){ $html .= '<li><span>Formato:</span> '.get_post_meta( $post->ID, 'formato', true ).'</li>'; }
							if( null != get_post_meta( $post->ID, 'tecnologia', true )){ $html .= '<li><span>Tecnología:</span> '.get_post_meta( $post->ID, 'tecnologia', true ).'</li>'; }
                 $html .= '<li><span>Observaciones:</span> '.$post->post_content.'</li>       
						</ul>
                    </li>';
			}
			
			$contador ++;
			
		}
		
		$html .= '		</ul>
					</div>
					<div class="dformats-right">
						<img src="'.wp_get_attachment_url( get_post_thumbnail_id($posts_array[0]->ID), 'full' ).'" alt="'.$posts_array[0]->post_title.'" />
					</div>
				</div> ';
				
		return $html;
		

	}
	
}