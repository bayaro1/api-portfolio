<?php

namespace App\Entity;

use ApiPlatform\Metadata\Post;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiFilter;
use App\Repository\AnswerRepository;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use App\Service\DateTimeImmutableGenerator;
use App\Controller\UGC\WriteAnswerController;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\Delete;
use App\ApiResource\StateProcessor\AnswerProcessor;
use DateTimeImmutable;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    operations: [
        new GetCollection(
            order: ['createdAt' => 'DESC'],
            paginationClientItemsPerPage: true,
            paginationMaximumItemsPerPage: 5,
        ),
        new Post(
            uriTemplate: '/admin/answers',
            denormalizationContext: ['groups' => ['write:answer']],
            controller: WriteAnswerController::class,
            security: 'is_granted("ROLE_ADMIN")',
            stateless: false,
            openapiContext: [
                'parameters' => [
                    [
                        'in' => 'query',
                        'name' => 'commentId',
                        'description' => 'Id du commentaire auquel on souhaite répondre',
                        'schema' => [
                            'type' => 'integer',
                            'minimum' => 0
                        ]
                    ]
                ]
            ]
        ),
        new Delete(
            uriTemplate: '/admin/answers/{id}',
            security: 'is_granted("ROLE_ADMIN")',
            stateless: false
        )
    ]
)]
#[ORM\Entity(repositoryClass: AnswerRepository::class)]
class Answer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['read:comment:list'])]
    private ?int $id = null;

    #[Groups(['read:comment:list'])]
    #[ORM\Column(options: ['default' => 0])]
    private ?bool $byAdmin = false;

    #[Groups(['write:answer', 'read:comment:list'])]
    #[Assert\NotBlank(message: 'Veuillez entrer votre nom')]
    #[Assert\Length(max: 200, maxMessage: '200 caractères maximum')]
    #[ORM\Column(length: 255)]
    private ?string $fullName = null;

    #[Groups(['write:answer', 'read:comment:list'])]
    #[Assert\Length(max: 200, maxMessage: '200 caractères maximum')]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $company = null;

    #[Groups(['write:answer', 'read:comment:list'])]
    #[Assert\NotBlank(message: 'Veuillez écrire un message')]
    #[Assert\Length(max: 2000, maxMessage: '2000 caractères maximum')]
    #[ORM\Column(type: Types::TEXT)]
    private ?string $content = null;

    #[Groups(['read:comment:list'])]
    #[ORM\Column]
    private \DateTimeImmutable $createdAt;

    #[ApiFilter(SearchFilter::class, strategy: 'exact')]
    #[ORM\ManyToOne(inversedBy: 'answers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Comment $comment = null;

    public function __construct()
    {
        $this->createdAt = DateTimeImmutableGenerator::now();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isByAdmin(): ?bool
    {
        return $this->byAdmin;
    }

    public function setByAdmin(bool $byAdmin): static
    {
        $this->byAdmin = $byAdmin;

        return $this;
    }

    public function getFullName(): ?string
    {
        return $this->fullName;
    }

    public function setFullName(string $fullName): static
    {
        $this->fullName = $fullName;

        return $this;
    }

    public function getCompany(): ?string
    {
        return $this->company;
    }

    public function setCompany(?string $company): static
    {
        $this->company = $company;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getComment(): ?Comment
    {
        return $this->comment;
    }

    public function setComment(?Comment $comment): static
    {
        $this->comment = $comment;

        return $this;
    }
}
