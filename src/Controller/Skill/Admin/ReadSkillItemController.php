<?php
namespace App\Controller\Skill\Admin;

use App\Entity\Skill;
use App\Service\Image\PicturePathResolver;
use App\Service\Image\PictureUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class ReadSkillItemController extends AbstractController
{
    public function __construct(
        private PictureUploader $pictureUploader,
        private PicturePathResolver $picturePathResolver
    )
    {
        
    }

    public function __invoke(Skill $skill): Skill
    {
        //si on a un logo
        if($logoPath = $this->picturePathResolver->resolve($skill, 'logoFile'))
        {
            //on configure la propriété skill.logoBase64
            $logoBase64 = $this->pictureUploader->convertPathToBase64($logoPath, 'png');
            $skill->setLogoBase64($logoBase64);
        }
        return $skill;
    }
}