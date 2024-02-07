<?php
namespace App\Controller\Skill;

use App\Entity\Skill;
use App\Service\Image\PicturePathResolver;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[AsController]
class ReadSkillListController extends AbstractController
{
    public function __construct(
        private PicturePathResolver $picturePathResolver
    )
    {
        
    }

    /**
     * @param Skill[] $data
     * @return Skill[]
     */
    public function __invoke($data)
    {
        foreach($data as $skill)
        {
            if($logoPath = $this->picturePathResolver->resolve($skill, 'logoFile'))
            {
                $skill->setLogoPath($logoPath);
            }
        }
        return $data;
    }
}