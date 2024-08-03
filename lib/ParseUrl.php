<?php
namespace ImgOnthefly;

final class ParseUrl
{
  public function __construct(public string $url = "")
  {
  }

  public function getHost() : string|null
  {
    return parse_url($this->url, PHP_URL_HOST);
  }

  public function getName()
  {
    return parse_url($this->url, PHP_URL_PATH) ?? null;
  }
}