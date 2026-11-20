<?php

/**
 * carga librerías
 */
require_once(__DIR__."/resources/autoload.php");
require_once(__DIR__."/CacheExternalFile.php");
use Vocento\Infrastructure\Client\Frontal\Http\HttpFrontalClient;

class FrontalClient
{
    private $host;
    private $seccion;
    private $duration;
    private $dataurl;

    public function __construct($host, $seccion, $duration = 90, $dataUrlskeleton)
    {
        $this->host = $host; //"https://www.laverdad.es"
        $this->seccion = $seccion; //externo/blogs
        $this->duration = $duration;
        $this->dataurl = $dataUrlskeleton;
    }

    public function getHtmlLayoutFrontalService($custom_value, $ifAMP)
    {
        $frontalClient = new HttpFrontalClient("https://".$this->host);
        $cacheFile = new CacheExternalFile($this->duration);
        // $fileName = $cacheFile->nameCacheFile('cabecera_' . $this->host);
        $fileName = $custom_value;

        if ($cacheFile->loadHTML($ifAMP.$fileName)) {
            $cabecera = $cacheFile->loadHTML($ifAMP.$fileName);
        } else {
            try {
                $html = $frontalClient->getHtmlLayout($this->host, "portal", "externo/blogs");
                $cabecera = $html->getBody()->__toString();

                if ( ! is_dir( WP_CONTENT_DIR.'/uploads/headercache/' ) ) {
                    wp_mkdir_p( WP_CONTENT_DIR.'/uploads/headercache/' );
                }

                $fichero = WP_CONTENT_DIR.'/uploads/headercache/headercache_'.$ifAMP.$fileName;
                file_put_contents($fichero, $cabecera, FILE_USE_INCLUDE_PATH);
            } catch (\Exception $e) {
                // var_dump($e->getRequest());
            }
        }

        return $cabecera;
    }



    public function getHtmlLayoutURL($custom_value, $ifAMP, $tipodispositivo, $section )
    {
        $cacheFile = new CacheExternalFile($this->duration);
        // $fileName = $cacheFile->nameCacheFile('cabecera_' . $this->host);
        $fileName = $custom_value;

        if ($cacheFile->loadHTML($tipodispositivo . $section .'_'.$ifAMP.$fileName)) {
            $cabecera = $cacheFile->loadHTML($tipodispositivo .$section .'_'.$ifAMP.$fileName);
        } else {
            try {
                $url = $this->host.http_build_query($this->dataurl);
                
                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
                $cabecera = curl_exec($ch);
                curl_close($ch);
                

                // $cabecera = file_get_contents($this->host);
                $fichero = WP_CONTENT_DIR.'/uploads/headercache/headercache_'.$tipodispositivo .$section .'_'.$ifAMP.$fileName;


                if ( ! is_dir( WP_CONTENT_DIR.'/uploads/headercache/' ) ) {
                    wp_mkdir_p( WP_CONTENT_DIR.'/uploads/headercache/' );
                }

                file_put_contents($fichero, $cabecera, FILE_USE_INCLUDE_PATH);
            } catch (\Exception $e) {
                // var_dump($e->getRequest());
            }
        }
        return $cabecera;
    }
}
