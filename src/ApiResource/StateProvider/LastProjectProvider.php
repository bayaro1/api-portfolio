<?php
namespace App\ApiResource\StateProvider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Repository\ProjectRepository;

class LastProjectProvider implements ProviderInterface
{
    public function __construct(
        private ProjectRepository $projectRepository
    )
    {
        
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        $project = $this->projectRepository->createQueryBuilder('p')
                                        ->orderBy('p.endAt', 'DESC')
                                        ->setMaxResults(1)
                                        ->getQuery()
                                        ->getOneOrNullResult()
                                        ;
        return $project;
    }
}