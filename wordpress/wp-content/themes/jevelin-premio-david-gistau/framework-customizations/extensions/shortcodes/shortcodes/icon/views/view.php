<?php
/*-----------------------------------------------------------------------------------*/
/* Icon Box HTML
/*-----------------------------------------------------------------------------------*/

if ( ! defined( 'FW' ) ) { die( 'Forbidden' ); }
$animated = ( isset( $atts['animation'] ) && $atts['animation'] != 'none' ) ? ' sh-animated '. $atts['animation'] : '';
$animated_delay = ( $animated && $atts['animation_delay'] ) ? 'data-wow-delay="'. $atts['animation_delay'] .'s"' : '';
$animated_speed = ( $animated && $atts['animation_speed'] ) ? 'data-wow-duration="'. $atts['animation_speed'] .'s"' : '';
?>

<div id="icon-<?php echo esc_attr( $atts['id'] ); ?>" class="sh-icon sh-icon-<?php echo esc_attr( $atts['alignment'] ); ?><?php echo esc_attr( $animated ); ?>" <?php echo wp_kses_post( $animated_speed ) . wp_kses_post( $animated_delay ); ?>>
	<div class="sh-icon-container">
		<i class="sh-icon-data <?php echo esc_attr( $atts['icon'] ); ?>"></i>
	</div>
</div>
