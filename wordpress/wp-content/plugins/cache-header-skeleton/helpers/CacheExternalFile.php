<?php

// class CacheExternalFile
// {
//     private $timeToCache;

//     public function __construct($timeToCache = 900)
//     {
//         $this->timeToCache = $timeToCache;
//     }

//     public function loadHTML($url)
//     {
//         $fileName = $this->nameCacheFile($url);
//         if (file_exists(WP_CONTENT_DIR.'/uploads/headercache/headercache_'.$fileName) && time() - $this->timeToCache < filemtime(WP_CONTENT_DIR.'/uploads/headercache/headercache_'.$fileName)) {
//             $html = file_get_contents(WP_CONTENT_DIR.'/uploads/headercache/headercache_'.$fileName);
//         } else {
//             $html = null;
//         }

//         return $html;
//     }

//     public function nameCacheFile($url)
//     {
//         $break = Explode('/', $url);

//         $entities = array('%21', '%2A', '%27', '%28', '%29', '%3B', '%3A', '%40', '%26', '%3D', '%2B', '%24', '%2C', '%2F', '%3F', '%25', '%23', '%5B', '%5D');
//         $replacements = array('_', '_', "_", "_", "_", "_", "_", "_", "_", "_", "_", "_", "_", "_", "_", "_", "_", "_", "_");
        
//         $fileName = str_replace($entities, $replacements, urlencode(implode("", $break)));
//         // $fileName = str_replace('.', '_', $fileName);
//         // $fileName = str_replace('-', '_', $fileName);

//         return $fileName;
//     }
// }

class CacheExternalFile
{
    static private $timeToCache;

    public function __construct($timeToCache = 900)
    {
        CacheExternalFile::$timeToCache = $timeToCache;
    }

    static public function loadHTML($url)
    {
        $fileName = CacheExternalFile::nameCacheFile($url);
        if (file_exists(WP_CONTENT_DIR . '/uploads/headercache/headercache_' . $fileName) && time() - CacheExternalFile::$timeToCache < filemtime(WP_CONTENT_DIR . '/uploads/headercache/headercache_' . $fileName)) {
            $html = file_get_contents(WP_CONTENT_DIR . '/uploads/headercache/headercache_' . $fileName);
        } else {
            $html = null;
        }

        return $html;
    }

    static public function nameCacheFile($url)
    {
        $break = Explode('/', $url);

        $entities = array('%21', '%2A', '%27', '%28', '%29', '%3B', '%3A', '%40', '%26', '%3D', '%2B', '%24', '%2C', '%2F', '%3F', '%25', '%23', '%5B', '%5D');
        $replacements = array('_', '_', "_", "_", "_", "_", "_", "_", "_", "_", "_", "_", "_", "_", "_", "_", "_", "_", "_");

        $fileName = str_replace($entities, $replacements, urlencode(implode("", $break)));
        // $fileName = str_replace('.', '_', $fileName);
        // $fileName = str_replace('-', '_', $fileName);

        return $fileName;
    }

    static public function deletecache()
    {
        $dir = WP_CONTENT_DIR . '/uploads/headercache/';

        $cdir = scandir($dir);
        foreach ($cdir as $key => $value) {
            if (!in_array($value, array(".", "..", "01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12",))) {
                unlink($dir . $value);
            }
        }
    }

    static public function getHtmlLayoutURL($url, $fileName)
    {
        $cabecera = "";
        if (CacheExternalFile::loadHTML($fileName)) {
            $cabecera = CacheExternalFile::loadHTML($fileName);
        } else {
            try {
                $cabecera = file_get_contents($url);
                $fichero = WP_CONTENT_DIR . '/uploads/headercache/headercache_' . $fileName;

                if (!is_dir(WP_CONTENT_DIR . '/uploads/headercache/')) {
                    wp_mkdir_p(WP_CONTENT_DIR . '/uploads/headercache/');
                }

                file_put_contents($fichero, $cabecera, FILE_USE_INCLUDE_PATH);
            } catch (\Exception $e) {
                // var_dump($e->getRequest());
            }
        }
        return $cabecera;
    }
}


