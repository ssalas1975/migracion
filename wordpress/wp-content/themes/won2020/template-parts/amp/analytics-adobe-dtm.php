<?php

global $post;
$slug = $post->post_name;
$categoriaSecundaria = '';

$tipoContenido = 'noticia';
$idPost = $post->ID;
$titular = $post->post_title;
$etiquetas = '';
$autorNoticia = $post->post_author;
$firmaAutor = get_the_author_meta('user_nicename', $autorNoticia);
$firmaAutorPermutive = '"'.get_the_author_meta('user_nicename', $autorNoticia).'"';
$creadorContenido = get_the_author_meta('user_nicename', $post->post_author);
$fechaPrimeraPublicacion = '';
$fechaModificacion = '';
$fechaPrimeraPublicacion = date('Ymdhis', strtotime($post->post_date)); //get_the_date("Ymdhis");
$fechaModificacion = date('Ymdhis', strtotime($post->post_modified));   //the_modified_date("Ymdhis"); sin echo
$content = $post->post_content;
$descripcion = substr(strip_tags(trim($content)), 0, 200);
$contentCount = str_word_count($content);
$parrafosCount = count(cantidad_parrafos($content));
$slot = '';

function cantidad_parrafos($texto, $rs="\r?\n") { 
    $parrafos = preg_split("/(.*?$rs)(?:$rs)+/s", $texto, -1, PREG_SPLIT_DELIM_CAPTURE|PREG_SPLIT_NO_EMPTY); 
    return $parrafos; 
}

if ($post->post_type == 'videocpt') {
    $tipoContenidoA = 'noticia-video';
    $seccion = 'videos';
    $subSeccion = 'video';
}

if ($post->post_type == 'audio') {
  $tipoContenidoA = 'noticia-audio';
  $seccion = 'podcasts';
  $subSeccion = 'podcast';
}

if ($post->post_type == 'noticia') {
  $seccion = 'noticias';
  $subSeccion = 'noticia';
  if (have_rows('galeria')) {
      $tipoContenido = 'fotogaleria';
      $tipoContenidoA = 'fotogaleria-vertical';
      $slot = 0;
  } else {
      $tipoContenido = 'noticia';
      $tipoContenidoA = 'noticia-estandar';
      $seccion = 'noticias';
  }
  if($post->post_content==''){
    $content = get_field('contenido');
  }else{
    $content = $post->post_content;
  }
  $descripcion = substr(strip_tags(trim(get_field('resumen'))), 0, 200);
  $contentCount = str_word_count($content);
  $parrafosCount = count(cantidad_parrafos($content));
}

$tags = get_the_terms($post->ID, 'category');

foreach ($tags as $tag) {
  $tagFinal = str_replace(array('&', '<', '>', '\'', '"'), array('', '', '', '', ''), $tag->slug);
  if (!empty($etiquetas)) {
      $etiquetas .= ',' .$tagFinal;
      $etiquetasPermutive .= ',"' . $tagFinal.'"';
  } else {
      $etiquetas .= $tagFinal;
      $etiquetasPermutive .= '"'.$tagFinal.'"';
  }
}

?>
<!-- Voc GTM -->
<amp-analytics config="https://www.googletagmanager.com/amp.json?id=GTM-MBVSSXS&gtm.url=SOURCE_URL" data-credentials="include" data-block-on-consent></amp-analytics>

<amp-analytics type="adobeanalytics_nativeConfig" id="analytics_adobe">
    <script type="application/json">
        {
            "requests": {
                "base": "https://${host}",
                "iframeMessage": "${base}/stats.html#&pageURL=${ampdocUrl}&ref=${documentReferrer}&vtm_seccion=<?php echo $seccion; ?>&vtm_subseccion1=<?php echo $subSeccion; ?>&vtm_subseccion2=&vtm_subseccion3=&vtm_subseccion4=&vtm_titular=<?php echo sanitize_title(get_the_title()); ?>&vtm_errorPagina=&vtm_tipoDominio=1&vtm_edicion=&vtm_tipoContenido=<?php echo $tipoContenido; ?>&vtm_tipoContenidoA=<?php echo $tipoContenidoA; ?>&vtm_contenidoPremium=no&vtm_plataformaContenido=amp&vtm_sistemaPublicacion=wordpress&vtm_fechaPrimeraPublicacion=<?php echo get_the_date("Ymdhis"); ?>&vtm_fechaModificacion=<?php the_modified_date("Ymdhis"); ?>&vtm_movilidad=&vtm_hiperlocalSeccion=&vtm_hiperlocalEdicionEspecial=no&vtm_contenidoDestacado=no&vtm_origenPortada=0&vtm_loMas=no&vtm_idioma=es&vtm_tipologiaLogin=anonimo&vtm_IDGigya=&vtm_edad=&vtm_codigoPostal=&vtm_genero=&vtm_rutaNavegacion=&vtm_sitioRegistro=womennow&vtm_IDEvolok=&vtm_productoEvolok=&vtm_nPaginasConsumidas=&vtm_nPaginasPermitidas=&vtm_autorizacionContenido=&vtm_idCookieEvolok=&vtm_IDPedido=&vtm_SKU=&vtm_nombreProducto=&vtm_categoriaProducto=&vtm_unidadesProducto=&vtm_precio=&vtm_nombreTienda=&vtm_accionEcommerce=&vtm_impuestos=&vtm_gastosEnvio=&vtm_precioTotal=&vtm_fechaRegistroEcommerce=&vtm_CSsubchannel=&vtm_CSgrupo=&vtm_CSsubgrupo=&vtm_adBlockers=&vtm_capasMostradas=&vtm_origenTags=&vtm_firmaAutor=&vtm_fichaAutor=&vtm_origenContenido=&vtm_topics=<?php echo $etiquetas; ?>&vtm_proceso=&vtm_funnel=&vtm_paso=&vtm_procesoFinalizado=&vtm_errorRechazo=&vtm_procesoEstatus=&vtm_tipoRegistroLogin=&vtm_tipoBuscador=&vtm_keyword=&vtm_nResultados=&vtm_errorBusqueda=&vtm_contenidoEmbebido=&vtm_swipeScreen=&vtm_slot=<?php echo $slot; ?>&vtm_idNoticia=<?php echo get_the_ID(); ?>"
            },
            "vars": {
                "host": "iframes.womennow.es"
            },
            "extraUrlParams": {
                "vtm_IDGigya": "${queryParam(vus)}",
                "vtm_medioCampana": "${queryParam(vmc)}",
                "vtm_origenCampana": "${queryParam(vso)}",
                "vtm_idCampana": "${queryParam(vca)}",
                "vtm_seccionContenidoCampana": "${queryParam(vli)}",
                "vtm_precioAsociadoCampana": "${queryParam(ns_fee)}",
                "vtm_campanaPago": "${queryParam(vvo)}"
            }
        }
    </script>
</amp-analytics>


<amp-geo layout="nodisplay">
  <script type="application/json">
    {
      "ISOCountryGroups": {
        "eea": ["af","ax","al","dz","as","ad","ao","ai","aq","ag","ar","am","aw","au","at","az","bs","bh","bd","bb","by","be","bz","bj","bm","bt","bo","ba","bw","bv","br","io","bn","bg","bf","bi","kh","cm","ca","cv","ky","cf","td","cl","cn","cx","cc","co","km","cg","cd","ck","cr","ci","hr","cu","cy","cz","dk","dj","dm","do","ec","eg","sv","gq","er","ee","et","fk","fo","fj","fi","fr","gf","pf","tf","ga","gm","ge","de","gh","gi","gr","gl","gd","gp","gu","gt","gg","gn","gw","gy","ht","hm","va","hn","hk","hu","is","in","id","ir","iq","ie","im","il","it","jm","jp","je","jo","kz","ke","ki","kr","kw","kg","la","lv","lb","ls","lr","ly","li","lt","lu","mo","mk","mg","mw","my","mv","ml","mt","mh","mq","mr","mu","yt","mx","fm","md","mc","mn","me","ms","ma","mz","mm","na","nr","np","nl","an","nc","nz","ni","ne","ng","nu","nf","mp","no","om","pk","pw","ps","pa","pg","py","pe","ph","pn","pl","pt","pr","qa","re","ro","ru","rw","bl","sh","kn","lc","mf","pm","vc","ws","sm","st","sa","sn","rs","sc","sl","sg","sk","si","sb","so","za","gs","es","lk","sd","sr","sj","sz","se","ch","sy","tw","tj","tz","th","tl","tg","tk","to","tt","tn","tr","tm","tc","tv","ug","ua","ae","gb","us","um","uy","uz","vu","ve","vn","vg","vi","wf","eh","ye","zm","zw"]
      }
    }
  </script>
</amp-geo>
<amp-consent id="didomi" layout="nodisplay" type="didomi">
  <script type="application/json">
    {
      "uiConfig": {
        "overlay": false
      },
      "clientConfig": {
        "gdprAppliesGlobally": true,
        "config": {
          "notice": {
            "initialHeight": "60vh"
          },
          "app": {
            "apiKey": "54be359e-8720-4316-b789-ade04aa9f9cd"
          }
        }
      }
    }
  </script>
</amp-consent>

<amp-state id="permutiveConfig" data-block-on-consent> 
  <script type="application/json">
    {
      "apiKey": "390f11db-6eea-42eb-9ef9-4d27b1bb2bab",
      "projectId": "28986622-e6be-473d-9a8f-e603a25f3c66", 
      "environment": "production"
    }
  </script>
</amp-state>

<amp-script data-block-on-consent id="permutiveCachedTargeting" sandboxed script="permutiveCachedTargetingScript"></amp-script>
<script id="permutiveCachedTargetingScript" type="text/plain" target="amp-script">
  exportFunction('ct', () => {let c = JSON.parse(localStorage.getItem('_pdfps'));let i = localStorage.getItem('permutive-id');return {targeting:{permutive: c ? c : [], puid: i}, ppid: i};})
</script>

<amp-script data-block-on-consent sandboxed script="pamp-json"></amp-script>
<script id="pamp-json" type="text/plain" target="amp-script">
  fetch('https://cdn.permutive.app/queries/28986622-e6be-473d-9a8f-e603a25f3c66-amp.json').then(r => r.json()).then(d => AMP.setState({permutiveConfig: {ampJson: d}}));
</script>

<amp-script data-block-on-consent id="permutiveSdk" sandboxed src="https://cdn.permutive.app/ampsdk/28986622-e6be-473d-9a8f-e603a25f3c66-ampscript.js"></amp-script>

<!-- codigo permutive -->
<amp-analytics type="permutive-ampscript" data-block-on-consent> 
      <script type="application/json">
        {
          "extraUrlParams": {
            "customProperties": {
              "classifications_watson": {
                "categories": "$alchemy_taxonomy",
                "concepts": "$alchemy_concepts",
                "emotion": "$alchemy_document_emotion",
                "entities": "$alchemy_entities",
                "keywords": "$alchemy_keywords",
                "sentiment": "$alchemy_document_sentiment"
              },
              "type": "noticia",
              "article": {
                "authors": [<?php echo $firmaAutorPermutive; ?>], 
                "description": "<?php echo $descripcion; ?>",
                "id": "<?php echo $idPost; ?>",
                "modifiedAt": "<?php echo get_post_modified_time('Y-m-d\\TH:i:s', true) . 'Z'; ?>",
                "paragraphCount": <?php echo $parrafosCount; ?>,
                "premium": false,
                "publishedAt": "<?php echo get_post_time('Y-m-d\\TH:i:s', true) . 'Z'; ?>",
                "section": "<?php echo $seccion; ?>",
                "subsection": "<?php echo $subSeccion; ?>",
                "title": "<?php echo $titular; ?>",
                "topics": [<?php echo $etiquetasPermutive; ?>], 
                "wordCount": <?php echo $contentCount; ?>
              },
              "user": {
                "age": null, 
                "gender": "", 
                "type": ""
              },
              "content": {
                "categories": [<?php echo $etiquetasPermutive; ?>]
              }
            }
          }
        }
      </script>
    </amp-analytics>

</amp-analytics><amp-analytics type="gfksensic" data-block-on-consent="_till_responded">
    <script type="application/json">
        {
            "requests": {
                "custom_params": "cp_c1=womenow.es&cp_c2=<?php echo ICL_LANGUAGE_CODE.'/'.$subSeccion; ?>" 
            },
            "vars": {
                "regionID": "es",
                "mediaID": "WomennowAMP"
            },
            "triggers": {
                "trackConsent": {
                    "on": "visible",
                    "request": "impression"
                }
            }
        }
    </script>
</amp-analytics>

<?php wp_reset_postdata(); ?>