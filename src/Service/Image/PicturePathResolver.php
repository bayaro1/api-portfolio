<?php
namespace App\Service\Image;

use App\Entity\Picture;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;


class PicturePathResolver 
{
    public function __construct(
        private UploaderHelper $helper,
        private CacheManager $imagineCacheManager
    )
    {

    }
    public function getPath(?Picture $picture, string $filter = null): string
    {
        $path = '/img/default.jpg';
        if($picture)
        {
            if($resolvedPath = $this->helper->asset($picture, 'file'))
            {
                $path = $resolvedPath;
            }
        }
        if($filter)
        {
            return $this->imagineCacheManager->getBrowserPath($path, $filter);
        }
        return $path;
    }
    
    public function getAlt(?Picture $picture, string $lang): string
    {
        if($picture && $picture->getAlt() !== null)
        {
            $getLang = 'get' . ucfirst($lang);
            if(method_exists($picture->getAlt(), $getLang) && $picture->getAlt()->$getLang() !== null)
            {
                return $picture->getAlt()->$getLang();
            }
        }
        return 'Photo';
    }
}