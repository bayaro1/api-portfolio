<?php
namespace App\Controller\Project\Admin;

use App\Entity\Project;
use App\Entity\Skill;
use App\Service\Image\PictureUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class WriteProjectController extends AbstractController
{
    public function __construct(
        private PictureUploader $pictureUploader
    )
    {
        
    }

    public function __invoke(Project $project): Project
    {
        if($screenMobileBase64 = $project->getScreenMobileBase64())
        {
            $uploadableFile = $this->pictureUploader->uploadBase64($screenMobileBase64, 'screen_mobile.jpg');
            $project->setScreenMobileFile($uploadableFile);
        }
        if($screeDesktopBase64 = $project->getScreenDesktopBase64())
        {
            $uploadableFile = $this->pictureUploader->uploadBase64($screeDesktopBase64, 'screen_desktop.jpg');
            $project->setScreenDesktopFile($uploadableFile);
        }
        return $project;
    }
}