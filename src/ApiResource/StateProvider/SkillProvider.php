<?php
namespace App\ApiResource\StateProvider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Repository\SkillRepository;

class SkillProvider implements ProviderInterface
{
    public function __construct(
        private SkillRepository $skillRepository
    )
    {
        
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        return $this->skillRepository->findFiltered(
            $context['filters']['name'] ?? null,
            $context['filters']['limit'] ?? 5
        );
    }
}