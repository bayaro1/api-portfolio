<?php
namespace App\Controller\Skill\Admin;

use App\Entity\Skill;
use App\Service\Image\PictureUploader;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


#[AsController]
class WriteSkillController extends AbstractController
{
    public function __construct(
        private PictureUploader $pictureUploader
    )
    {
        
    }

    public function __invoke(Skill $skill): Skill
    {
        if($logoBase64 = $skill->getLogoBase64())
        {
            $uploadableFile = $this->pictureUploader->uploadBase64($logoBase64, 'logo.png');
            $skill->setLogoFile($uploadableFile);
        }
        return $skill;
    }
}