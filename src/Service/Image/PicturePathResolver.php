<?php
namespace App\Service\Image;

use App\Entity\Picture;
use App\Config\SiteConfig;
// use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

class PicturePathResolver
{
    public function __construct(
        private UploaderHelper $helper,
        // private CacheManager $imagineCacheManager
    )
    {

    }
    public function resolve(?Object $uploadable, string $fileProperty, string $filter = null): string
    {
        $path = '/img/default.png';
        if($resolvedPath = $this->helper->asset($uploadable, $fileProperty))
        {
            $path = $resolvedPath;
        }
        // if($filter)
        // {
        //     return $this->imagineCacheManager->getBrowserPath($path, $filter);
        // }
        return SiteConfig::SITE_URL . $path;
    }
}