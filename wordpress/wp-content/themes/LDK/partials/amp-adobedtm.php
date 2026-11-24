<amp-analytics type="adobeanalytics_nativeConfig" id="analytics3">
    <script type="application/json">
        {
            "requests": {
                "base": "https://${host}",
				"iframeMessage": "${base}/stats.html#&pageURL=${ampdocUrl}&ref=${documentReferrer}&vtm_scrollInfinito=false&vtm_seccion=<?php echo trim($vtm_seccion)?>&vtm_subseccion1=<?php echo trim($vtm_subseccion1)?>&vtm_subseccion2=<?php echo trim($vtm_subseccion2)?>&vtm_subseccion3=<?php echo trim($vtm_subseccion3)?>&vtm_subseccion4=<?php echo $vtm_subseccion4?>&vtm_titular=<?php echo $vtm_titular?>&vtm_errorPagina=&vtm_tipoDominio=1&vtm_edicion=&vtm_tipoContenido=<?php echo $vtm_tipoContenido?>&vtm_tipoContenidoA=&vtm_contenidoPremium=no&vtm_plataformaContenido=amp&vtm_sistemaPublicacion=wordpress&vtm_fechaPrimeraPublicacion=<?php echo $datec?>&vtm_fechaModificacion=<?php echo $datem?>&vtm_hiperlocalSeccion=&vtm_hiperlocalEdicionEspecial=&vtm_contenidoDestacado=no&vtm_origenPortada=0&vtm_idioma=es&vtm_tipologiaLogin=anonimo&vtm_sitioRegistro=<?php echo $COMSCORE_MEDIO?>&vtm_adBlockers=0&vtm_origenContenido=wordpress&vtm_topics=&vtm_proceso=&vtm_funnel=&vtm_paso=&vtm_procesoFinalizado=&vtm_errorRechazo=&vtm_procesoEstatus=&vtm_tipoRegistroLogin=&vtm_tipoBuscador=&vtm_keyword=&vtm_nResultados=&vtm_errorBusqueda=&vtm_contenidoEmbebido=no&vtm_swipeScreen="
            },
            "vars": {
                "host": "<?php echo $ADOBE_MEDIO?>"
            },
            "extraUrlParams": {
                "vtm_medioCampana": "${queryParam(vtm_medioCampana)}",
                "vtm_origenCampana": "${queryParam(vtm_origenCampana)}",
                "vtm_sitioCampana": "${queryParam(vtm_sitioCampana)}",
                "vtm_localizacion": "${queryParam(vtm_localizacion)}",
                "vtm_ContenidoCampana": "${queryParam(vtm_ContenidoCampana)}",
                "vtm_precioAsociadoCampana": "${queryParam(vtm_precioAsociadoCampana)}",
                "vtm_tipoCampana": "${queryParam(vtm_tipoCampana)}",
                "vtm_campanaPago": "${queryParam(vtm_campanaPago)}"
            }
        }
    </script>
</amp-analytics>