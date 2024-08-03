<?php
namespace ImgOnthefly;


final class ImageCrop
{
    public $file = null;

    public function __construct($file = null)
    {
        $this->file = $file;
    }


    function crop($urlImage, $widthToResize, $heightToResize)
    {

        $imagem = imagecreatefromstring(file_get_contents($urlImage));

        $width = imagesx($imagem);
        $height = imagesy($imagem);

        $proporcaoLargura = $widthToResize / $width;
        $proporcaoAltura = $heightToResize / $height;

        $proporcaoRedimensionamento = max($proporcaoLargura, $proporcaoAltura);

        $widthNew = $width * $proporcaoRedimensionamento;
        $heightNew = $height * $proporcaoRedimensionamento;

        $imageResized = imagecreatetruecolor($widthNew, $heightNew);

        imagecopyresampled($imageResized, $imagem, 0, 0, 0, 0, $widthNew, $heightNew, $width, $height);

        $x = ($widthNew - $widthToResize) / 2;
        $y = ($heightNew - $heightToResize) / 2;

        $imageCroped = imagecreatetruecolor($widthToResize, $heightToResize);

        imagecopy($imageCroped, $imageResized, 0, 0, $x, $y, $widthToResize, $heightToResize);

        imagedestroy($imagem);
        imagedestroy($imageResized);

        return $imageCroped;
    }

    function save($urlImage, $widthToResize, $heightToResize)
    {

        $imagick = new \Imagick();

        $imagick->readImage($urlImage);

        $imagick->stripImage();

        $pathImage = dirname(__FILE__) . "/../src/image/{$this->file->getFolder()}/{$this->file->getOriginal()}";

        $imagick->writeImage($pathImage);

        return $imagick;
    }


    public function cropAndSave($pathImage, $widthToResize, $heightToResize)
    {
        // Cria uma nova instância Imagick
        $imagick = new \Imagick();

        // Carrega a imagem original
        $imagick->readImage($pathImage);

        // Redimensiona e corta a imagem
        $imagick->cropThumbnailImage($widthToResize, $heightToResize);

        // Define o formato da imagem para WebP
        $imagick->setImageFormat('webp');

        // Define o caminho para a imagem otimizada
        $pathWebPImage = dirname(__FILE__) . "/../src/image/{$this->file->getFolder()}/{$this->file->getOptimized()}";

        // Salva a imagem no formato WebP
        $result = $imagick->writeImage($pathWebPImage);

        // Destroi o objeto Imagick para liberar a memória
        $imagick->destroy();

        // Verifica se a imagem foi salva corretamente
        if ($result) {
            return $pathWebPImage;
        }
        
        return false;

    }
}