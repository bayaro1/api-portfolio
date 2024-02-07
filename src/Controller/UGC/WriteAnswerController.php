<?php
namespace App\Controller\UGC;

use App\Entity\Answer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;

class WriteAnswerController extends AbstractController
{
    public function __construct(
        private Security $security
    )
    {
        
    }

    public function __invoke(Answer $answer): Answer
    {
        if($user = $this->security->getUser())
        {
            if(in_array('ROLE_ADMIN', $user->getRoles()))
            {
                $answer->setByAdmin(true);
            }
        }
        return $answer;
    }
}