<?php

class VocentoAnalyticsAdminHelp
{
    /**
     * Start up
     */
    public function __construct()
    {

    }

    public function render_page($render_links = true)
    {
        // Set class property
        ?>
        <div class="wrap theme-vcanly">
            <div class="vcanly-fields form-wrapper">
                <div class="vcanly-fields localdata">
                    <h3>Ayuda para configurar e instalar el plugin</h3>
                    <div class="vcanly_info">
                        Para el correcto funcionamiento del plugin en <b>necesario</b> incluir las siguientes lineas a
                        su theme:
                    </div>
                    <br/>
                    <p>
                        <b>1.- Justo despues de la apertura de la etiqueta body pegue la siguiente linea:</b><br/>
                    <pre> do_action('vocento_analytics_after_body_start');</pre>
                    <br/>
                    <br/>
                    De manera que quede:<br/>
                    <br/>

                        <pre>
                        &lt;body&gt;
                         &lt;?php  do_action('vocento_analytics_after_body_start');?&gt;
                        </pre>
                    <br/>
                    <br/>

                    <b>2.- Justo antes del cierre de la etiqueta body pegue la siguiente linea:</b><br/>
                    <pre>do_action('vocento_analytics_before_body_end');</pre>
                    <br/>
                    <br/>

                    De manera que quede:<br/>
                    <br/>
                        <pre>
                        &lt;?php  do_action('vocento_analytics_before_body_end');?&gt;
                        &lt;/body&gt;
                        </pre>
                    </p>
                    <br/>
                    <h3>Filtros</h3>
                    <p>
                        Puede utilizar su propio código para cada red medición.<br/>
                        Utilizando estos filtros puede sobreescribir el código por defecto que se incluirá.<br/>
                        En el siguiente listado se muestran los filtros disponibles y el fichero que con el que
                        corresponden:
                    </p>
                    <ul>
                        <li><b>vcanly_comscore_body_start</b> (/inc/comscore/body_start.html)</li>
                        <li><b>vcanly_gtm_body_start</b> (/inc/gtm/body_start.html)</li>
                        <li><b>vcanly_amp_body_start</b> (/inc/amp/body_start.html)</li>
                        <li><b>vcanly_softclick_footer</b> (/inc/softclick/footer.html)</li>
                        <li><b>vcanly_chartbeat_head</b> (/inc/chartbeat/head.html)</li>
                        <li><b>vcanly_ga_head</b> (/inc/ga/head.html)</li>
                        <li><b>vcanly_amp_head</b> (/inc/amp/head.html)</li>
                        <li><b>vcanly_inspectlet_head</b> (/inc/inspectlet/head.html)</li>
                        <li><b>vcanly_crazyegg_head</b> (/inc/crazyegg/head.html)</li>
                        <li><b>vcanly_chartbeat_body_end</b> (/inc/chartbeat/body_end.html)</li>
                    </ul>
                    <p>
                        *Recuerde que puede acceder a la configuracion de las redes desde su ficheros a través del
                        objeto "VocentoWP_Analytics" de javascript<br/><br/>
                        Ejemplo:
                    </p>
                    <pre>
                    <span style="color:red;">&lt;?php</span>
                    <span style="color:cadetblue;">add_filter</span>('vcanly_ga_head',function(<span
                            style="color:blueviolet;">$current_code</span>){
                    &nbsp;&nbsp;<span
                            style="color:green;">//En lugar de cargar el fichero original cargamos uno propio.</span>
                    &nbsp;&nbsp;<span style="color:blueviolet;">$my_gtm_file</span> = <span style="color:cadetblue;">file_get_contents</span>("mygtm_file_and_custom_data_layer.html");
                    &nbsp;&nbsp;return <span style="color:blueviolet;">$my_gtm_file</span>;
                    });
                    <span style="color:red;">?&gt;</span>
                    </pre>
                    </p>
                    <h3>Pageview candidate / páginas virtuales</h3>
                    <div class="vcanly_info">
                        No todos los themes/desarrollos permiten el uso de Pageview candidate por lo que está
                        información <b>no es requerida para el funcionamiento del plugin</b> y <b>solo ha de incluirse
                            en desarrollos que cumplan los requisitos de Pageview candidadate</b>.
                    </div>
                    <p>
                        Si desea utilizar Pageview candidate debe realizar una llamada a la siguiente funcion javascript
                        enviando los nuevos valores:
                    </p>
                    <br/>
                    <pre>
                    VocentoWP_Analytics.<span style="color:darkgoldenrod">utils</span>.<span
                            style="color:darkgoldenrod">dynamic</span>.<span
                            style="color:darkgoldenrod">refreshTracking</span>(voc_se,voc_tn,voc_name,name,voc_ho,voc_tc,voc_au,voc_fep,ns_http_status,document_location);
                    </pre>
                    <br/>
                    <p> Esta función realizará las peticiones <b>únicamente</b> a las redes de medición que tenga
                        activadas en su configuración.</p><br/><br/>
                    <div class="vcanly_info">
                        (*) Las redes que enviarán las peticiones (en caso de estar activadas) son:<br/>
                        <ul>
                            <li><b>Comscore</b></li>
                            <li><b>Chartbeat</b></li>
                            <li><b>Google Analytics</b></li>
                            <li><b>Google Tag Manager</b></li>
                            <li><b>Krux</b></li>
                        </ul>
                    </div>
                    <br/>
                    <p>Tambien puede realizar las peticiones de manera individual para cada red de medición:</p>
                    <ul>
                        <li>
                            <b>Comscore:</b> <br/>
                            <pre>VocentoWP_Analytics.<span style="color:darkgoldenrod">utils</span>.<span
                                    style="color:darkgoldenrod">dynamic</span>.<span style="color:darkgoldenrod">comscore</span>.<span
                                    style="color:darkgoldenrod">refresh</span>(voc_se, voc_tn, voc_name, name, voc_ho, voc_tc, voc_au, voc_fep, ns_http_status);</pre>
                            <br/>
                        </li>
                        <li>
                            <b>Chartbeat:</b> <br/>
                            <pre>VocentoWP_Analytics.<span style="color:darkgoldenrod">utils</span>.<span
                                    style="color:darkgoldenrod">dynamic</span>.<span style="color:darkgoldenrod">chartbeat</span>.<span
                                    style="color:darkgoldenrod">refresh</span>(voc_se, voc_au, voc_tn,document_location);</pre>
                            <br/>
                        </li>
                        <li>
                            <b>Google Analytics:</b> <br/>
                            <pre>VocentoWP_Analytics.<span style="color:darkgoldenrod">utils</span>.<span
                                    style="color:darkgoldenrod">dynamic</span>.<span
                                    style="color:darkgoldenrod">ga</span>.<span
                                    style="color:darkgoldenrod">refresh</span>(document_location);</pre>
                            <br/>
                        </li>
                        <li>
                            <b>Google Tag Manager:</b> <br/>
                            <pre>VocentoWP_Analytics.<span style="color:darkgoldenrod">utils</span>.<span
                                    style="color:darkgoldenrod">dynamic</span>.<span
                                    style="color:darkgoldenrod">gtm</span>.<span
                                    style="color:darkgoldenrod">refresh</span>(voc_se, voc_tc, voc_ho);</pre>
                            <br/>
                        </li>
                        <li>
                            <b>Krux:</b> <br/>
                            <pre>VocentoWP_Analytics.<span style="color:darkgoldenrod">utils</span>.<span
                                    style="color:darkgoldenrod">dynamic</span>.<span
                                    style="color:darkgoldenrod">krux</span>.<span
                                    style="color:darkgoldenrod">refresh</span>(voc_se,voc_s1,voc_tc);</pre>
                            <br/>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <?php
    }
}