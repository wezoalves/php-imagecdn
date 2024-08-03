<?php

include ("../vendor/autoload.php");

use ImgOnthefly\NameFile;
use ImgOnthefly\ImageCrop;
use ImgOnthefly\ParseUrl;
use Arrilot\DotEnv\DotEnv;

$url = isset($_GET['u']) ? $_GET['u'] : null;
$width = isset($_GET['w']) ? $_GET['w'] : 1200; // best SEO 19:9
$height = isset($_GET['h']) ? $_GET['h'] : 675; // best SEO 19:9
$key = isset($_GET['key']) ? $_GET['key'] : 'DEFAULT';

DotEnv::load(dirname(__FILE__) . './../.env.php');

if (DotEnv::get('KEY_DOMAIN') != $key || ! $url) {
  header('Content-Type: image/gif');
  echo base64_decode('R0lGODlhAQABAJAAAP8AAAAAACH5BAUQAAAALAAAAAABAAEAAAICBAEAOw==');
  exit();
}

if (! (new ParseUrl($url))->getHost()) {
  $domain = base64_decode($key);
  $url = "{$domain}{$url}";
}

$file = new NameFile($url, $width, $height);

// if image original not exists
if (! $file->getFile(1)) {
  // storage image
  $saved = $imagemProcessada = (new ImageCrop($file))->save($url, $width, $height);

  // on error, return placeholder
  if (! $saved) {
    header('Content-Type: image/gif');
    echo base64_decode('R0lGODlhAQABAJAAAP8AAAAAACH5BAUQAAAALAAAAAABAAEAAAICBAEAOw==');
    exit();
  }
}

// if image optimized not exists
if (! $file->getFile(2)) {
  try {
    // create a image optimized
    $imageCroped = (new ImageCrop($file))->cropAndSave($file->getFile(1), $width, $height);
  } catch (\Throwable $th) {
    header('Content-Type: image/gif');
    echo base64_decode('R0lGODlhAQABAJAAAP8AAAAAACH5BAUQAAAALAAAAAABAAEAAAICBAEAOw==');
    exit();
  }
}

// if optimized image not created, return original image
$fileOut = $file->getFile(2) ?? $file->getFile(1);

$imageInfo = getimagesize($fileOut);
$fp = fopen($fileOut, 'rb');
header('Content-Type: image/webp');
header('Content-Length: ' . filesize($fileOut));

// Set cache headers for a long TTL
$cacheDuration = 31536000; // 1 year in seconds
header('Cache-Control: public, max-age=' . $cacheDuration);
header('Expires: ' . gmdate('D, d M Y H:i:s', time() + $cacheDuration) . ' GMT');
header('Pragma: cache');

fpassthru($fp);