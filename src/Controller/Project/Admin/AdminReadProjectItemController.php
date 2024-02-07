<?php
namespace App\Controller\Project\Admin;

use App\Entity\Skill;
use App\Entity\Project;
use App\Service\Image\PictureUploader;
use App\Service\Image\PicturePathResolver;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


#[AsController]
class AdminReadProjectItemController extends AbstractController
{
    public function __construct(
        private PictureUploader $pictureUploader,
        private PicturePathResolver $picturePathResolver
    )
    {
        
    }

    public function __invoke(Project $project): Project
    {
        if($screenMobilePath = $this->picturePathResolver->resolve($project, 'screenMobileFile'))
        {
            $screenMobileBase64 = $this->pictureUploader->convertPathToBase64($screenMobilePath);
            $project->setScreenMobileBase64($screenMobileBase64);
        }
        if($screenDesktopPath = $this->picturePathResolver->resolve($project, 'screenDesktopFile'))
        {
            $screenDesktopBase64 = $this->pictureUploader->convertPathToBase64($screenDesktopPath);
            $project->setScreenDesktopBase64($screenDesktopBase64);
        }

        return $project;
    }
}