<?php

class CacheExternalFile
{
    private $timeToCache;

    public function __construct($timeToCache = 900)
    {
        $this->timeToCache = $timeToCache;
    }

    public function loadHTML($url)
    {
        $fileName = $this->nameCacheFile($url);
        if (file_exists(WP_CONTENT_DIR.'/uploads/'.$fileName) && time() - $this->timeToCache < filemtime(WP_CONTENT_DIR.'/uploads/'.$fileName)) {
            $html = file_get_contents(WP_CONTENT_DIR.'/uploads/'.$fileName);
        } else {
            $html = null;
        }

        return $html;
    }

    public function nameCacheFile($url)
    {
        $break = Explode('/', $url);

        $entities = array('%21', '%2A', '%27', '%28', '%29', '%3B', '%3A', '%40', '%26', '%3D', '%2B', '%24', '%2C', '%2F', '%3F', '%25', '%23', '%5B', '%5D');
        $replacements = array('_', '_', "_", "_", "_", "_", "_", "_", "_", "_", "_", "_", "_", "_", "_", "_", "_", "_", "_");
        
        $fileName = str_replace($entities, $replacements, urlencode(implode("", $break)));
        // $fileName = str_replace('.', '_', $fileName);
        // $fileName = str_replace('-', '_', $fileName);

        return $fileName;
    }
}
