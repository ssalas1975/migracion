<?php

$width = 0;
$height = 0;

if (isset($_GET['url'])) {
    $source = $_GET['url'];
} else {
    return true;
}

if (isset($_GET['width'])) {
    $width = $_GET['width'];
}
if (isset($_GET['height'])) {
    $height = $_GET['height'];
}

$extension = pathinfo($source, PATHINFO_EXTENSION);
$size = getimagesize($source);

switch ($size['mime']) {
    case "image/gif":
        $image = imagecreatefromgif($source);
        break;
    case "image/jpeg":
        $image = imagecreatefromjpeg($source);
        break;
    case "image/png":
        $image = imagecreatefrompng($source);
        break;
    case "image/webp":
        $image = imagecreatefromwebp($source);
        break;
}

$width = ($width) ? intval($width) : imagesx($image);
$height = ($height) ? intval($height) : imagesy($image);


// $image = imagescale( $image, $width, $height );
imagewebp($image, null, 80);
