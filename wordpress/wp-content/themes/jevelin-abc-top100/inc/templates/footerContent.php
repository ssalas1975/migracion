<?php


$actual_link = (empty($_SERVER['HTTPS']) ? 'http' : 'https') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$re = '/http(.+?)\\/\\/(.+?)\\/(.+?)\\//m';
preg_match_all($re, $actual_link, $matches, PREG_SET_ORDER, 0);
if(($matches[0][3])) :
  $q = new WP_Query(
    array(
      'fields' => 'ids',
      'name'=>'logo-'.$matches[0][3],
      'post_type' =>'attachment',
      'post_status' => 'inherit',
      'ignore_sticky_posts' => true,
      'posts_per_page' => 1
    )
  );
  $attid = $q->posts[0];
  $dinamic_logo = wp_get_attachment_image_url($attid);
  if( $dinamic_logo ) {
      $standard_logo2 = $dinamic_logo;
  }

  echo '<!-- ______----footer3_____ '.$matches[0][3].' -->';
  echo '<!-- -------______------footer3______ '.$standard_logo2.' -->';
endif;

$logo_link = home_url( '/' );
if( isset( $matches[0][3] ) && $matches[0][3] == '100empresasfamiliares' ) :
    $logo_link = 'https://top.abc.es/100empresasfamiliares/';
elseif( isset( $matches[0][3] ) && $matches[0][3] == '100directivospymes' ) :
    $logo_link = 'https://top.abc.es/100directivospymes/';
endif;



$html = '
<div class="sh-footer-widgets">
    <div class="container">
        <div class="sh-footer-columns">
            <div id="text-2" class="widget-item widget_text">
                <div class="textwidget">
                    <a target="_blank" href="'.esc_url( $logo_link ).'" rel="noopener">
                        <img src="'.$standard_logo2.'" width="auto" height="80">
                    </a>
                </div>
            </div>
            <div id="text-3" class="widget-item widget_text">
                <div class="textwidget">
                    <p>
                        <a style="font-family: \'ABC Display Semibold\'; font-size: 14px; font-weight: 600; line-height: 21px;" target="_blank" href="mailto:contacta-top@abc.es" rel="noopener">contacta-top@abc.es</a>
                    </p>
                    <a target="_blank" class="facebook" href="https://www.facebook.com/ABCes" rel="noopener"><img src="/wp-content/uploads/sites/19/2022/04/Facebook-bl.svg" alt="facebook"> </a>
                    <a target="_blank" class="facebook" href="https://x.com/abc_es" rel="noopener"><img src="/wp-content/uploads/sites/19/2024/04/X.svg" alt="twitter"> </a>
                    <a target="_blank" class="facebook" href="https://www.instagram.com/abc_diario/" rel="noopener"><img src="/wp-content/uploads/sites/19/2022/04/Instagram-bl.svg" alt="instagram"> </a>
                    <a target="_blank" class="facebook" href="https://www.abc.es/" rel="noopener"><img src="/wp-content/uploads/sites/19/2022/04/ABC-bl.svg" alt="abc sección salud"> </a>
                    <br>
                    <p style="padding-top:12px">
                        <a href="https://www.vocento.com/aviso-legal" title="" target="_blank" rel="noopener"><span> Aviso Legal </span></a> |
                        <a href="https://www.vocento.com/politica-privacidad" title="" target="_blank" rel="noopener"><span> Política de Privacidad </span></a> |
                        <a href="https://www.vocento.com/politica-cookies" title="" target="_blank" rel="noopener"><span> Política de cookies </span></a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
';

echo $html;
