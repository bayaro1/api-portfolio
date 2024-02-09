<?php
namespace App\Controller\Project;

use App\Entity\Project;
use App\Service\Image\PicturePathResolver;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class ReadProjectItemController extends AbstractController
{
    public function __construct(
        private PicturePathResolver $picturePathResolver
    )
    {
        
    }

    public function __invoke(Project $data): Project
    {
        if($screenMobilePath = $this->picturePathResolver->resolve($data, 'screenMobileFile'))
        {
            $data->setScreenMobilePath($screenMobilePath);
        }
        if($screenDesktopPath = $this->picturePathResolver->resolve($data, 'screenDesktopFile'))
        {
            $data->setScreenDesktopPath($screenDesktopPath);
        }
        return $data;
    }
}