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

    public function __construct($host, $seccion, $duration = 90)
    {
        $this->host = $host; //"https://www.laverdad.es"
        $this->seccion = $seccion; //externo/blogs
        $this->duration = $duration;
    }

    public function getHtmlLayoutFrontalService()
    {
        $frontalClient = new HttpFrontalClient("https://".$this->host);
        $cacheFile = new CacheExternalFile($this->duration);
        $fileName = $cacheFile->nameCacheFile('cabecera_' . $this->host);

        if ($cacheFile->loadHTML($fileName)) {
            $cabecera = $cacheFile->loadHTML($fileName);
        } else {
            try {
                $html = $frontalClient->getHtmlLayout($this->host, "portal", "externo/blogs");
                $cabecera = $html->getBody()->__toString();
                $fichero = WP_CONTENT_DIR.'/uploads/'.$fileName;
                file_put_contents($fichero, $cabecera, FILE_USE_INCLUDE_PATH);
            } catch (\Exception $e) {
                // var_dump($e->getRequest());
            }
        }

        return $cabecera;
    }

    public function getHtmlLayoutURL()
    {
        $cacheFile = new CacheExternalFile($this->duration);
        $fileName = $cacheFile->nameCacheFile('cabecera_' . $this->host);

        if ($cacheFile->loadHTML($fileName)) {
            $cabecera = $cacheFile->loadHTML($fileName);
        } else {
            try {
                $cabecera = file_get_contents($this->host);
                $fichero = WP_CONTENT_DIR.'/uploads/'.$fileName;
                file_put_contents($fichero, $cabecera, FILE_USE_INCLUDE_PATH);
            } catch (\Exception $e) {
                // var_dump($e->getRequest());
            }
        }
        return $cabecera;
    }
}
