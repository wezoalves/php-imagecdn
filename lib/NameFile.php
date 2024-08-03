<?php
namespace ImgOnthefly;

use ImgOnthefly\ParseUrl;
use ImgOnthefly\ParseStr;

final class NameFile
{

  public string $url;
  public int $width = 1200;
  public int $height = 675;
  public string $crop = "smart";
  private string $name = "";
  private string $nameOriginal = "";

  private string $extension = "";

  public function __construct($url, $width = 1200, $height = 675, $crop = "smart", $name = "", $extension = ".webp")
  {

    $this->url = $this->fixUrl($url);
    $this->width = $width;
    $this->height = $height;
    $path = (new ParseUrl($this->url))->getName();
    $hash = hash('sha256', $this->url);

    $extensionOriginal = (new ParseStr($path))->getExtension();
    $this->nameOriginal = "{$hash}{$extensionOriginal}";

    $this->name = "{$hash}{$extension}";


  }

  public function getOriginal() : string
  {
    $hash = hash('crc32', "original");
    return "{$hash}-{$this->nameOriginal}";
  }

  public function getOptimized() : string
  {
    $hash = hash('crc32', "{$this->width}{$this->height}{$this->crop}");
    return "{$hash}-{$this->name}";
  }

  public function getFolder() : string
  {
    return (new ParseUrl($this->url))->getHost();
  }

  /**
   * 1 FOR ORIGINAL
   * 2 FOR OPTIMIZED
   */
  public function getFile(int $type) : string|bool
  {

    $imagePath = dirname(__FILE__) . "/../src/image/{$this->getFolder()}/";

    $fileCkeck = $type == 1 ? "{$imagePath}{$this->getOriginal()}" : "{$imagePath}{$this->getOptimized()}";

    if (! is_dir($imagePath)) {
      mkdir($imagePath);
    }

    if (file_exists($fileCkeck)) :

      return $fileCkeck;

    endif;

    return false;
  }

  public function fixUrl($url) : string
  {
    $url = strtr($url, [
      "https" => "",
      "http" => "",
      "//" => "",
      ":" => "",
    ]);

    $url = "https://{$url}";

    return $url;
  }
}
