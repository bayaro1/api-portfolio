<?php
namespace App\Controller\Project;

use App\Entity\Project;
use App\Service\Image\PicturePathResolver;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ReadProjectItemController extends AbstractController
{
    public function __construct(
        private PicturePathResolver $picturePathResolver
    )
    {
        
    }

    public function __invoke(Project $project): Project
    {
        if($screenMobilePath = $this->picturePathResolver->resolve($project, 'screenMobileFile'))
        {
            $project->setScreenMobilePath($screenMobilePath);
        }
        if($screenDesktopPath = $this->picturePathResolver->resolve($project, 'screenDesktopFile'))
        {
            $project->setScreenDesktopPath($screenDesktopPath);
        }
        return $project;
    }
}