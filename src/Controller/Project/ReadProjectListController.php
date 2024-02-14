<?php
namespace App\Controller\Project;

use App\Entity\Project;
use App\Service\Image\PicturePathResolver;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class ReadProjectListController extends AbstractController
{
    public function __construct(
        private PicturePathResolver $picturePathResolver
    )
    {
        
    }

    /**
     * @param Project[] $data
     * @return Project[]
     */
    public function __invoke($data)
    {
        foreach($data as $project)
        {
            if($screenMobilePath = $this->picturePathResolver->resolve($project, 'screenMobileFile'))
            {
                $project->setScreenMobilePath($screenMobilePath);
            }
            if($screenDesktopPath = $this->picturePathResolver->resolve($project, 'screenDesktopFile'))
            {
                $project->setScreenDesktopPath($screenDesktopPath);
            }
        }
        return $data;
    }
}