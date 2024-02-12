<?php
namespace App\Controller\UGC;

use App\Entity\Answer;
use App\Repository\CommentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;

class WriteAnswerController extends AbstractController
{
    public function __construct(
        private Security $security,
        private CommentRepository $commentRepository
    )
    {
        
    }

    public function __invoke(Answer $answer, Request $request): Answer
    {
        //on met le user
        if($user = $this->security->getUser())
        {
            if(in_array('ROLE_ADMIN', $user->getRoles()))
            {
                $answer->setByAdmin(true);
            }
        }
        
        //on associe le comment passé dans la query 
        if($comment = $this->commentRepository->find($request->query->get('commentId')))
        {
            $comment->addAnswer($answer);
        }

        return $answer;
    }
}