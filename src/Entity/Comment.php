<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use App\Repository\CommentRepository;
use ApiPlatform\Metadata\GetCollection;
use App\Service\DateTimeImmutableGenerator;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Post;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    operations: [
        new GetCollection(
            normalizationContext: ['groups' => ['read:comment:list']],
            order: ['createdAt' => 'DESC'],
            paginationClientItemsPerPage: true,
            paginationMaximumItemsPerPage: 20,
        ),
        new GetCollection(
            uriTemplate: '/admin/comments/all',
            order: ['createdAt' => 'DESC'],
            paginationEnabled: false,
	    normalizationContext: ['groups' => ['read:comment:list', 'admin:read:comment:list']]
        ),
        new Post(
            denormalizationContext: ['groups' => ['write:comment']]
        ),
        new Delete(
            uriTemplate: '/admin/comments/{id}',
            security: 'is_granted("ROLE_ADMIN")',
            stateless: false
        )
    ]
)]
#[ORM\Entity(repositoryClass: CommentRepository::class)]
class Comment
{
    #[Groups(['read:comment:list'])]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ApiFilter(SearchFilter::class, strategy: 'exact')]
    #[Groups(['write:comment', 'admin:read:comment:list'])]
    #[ORM\ManyToOne(inversedBy: 'comments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Project $project = null;

    #[Groups(['read:comment:list', 'write:comment'])]
    #[Assert\NotBlank(message: 'Veuillez entrer votre nom')]
    #[Assert\Length(max: 200, maxMessage: '200 caractères maximum')]
    #[ORM\Column(length: 255)]
    private ?string $fullName = null;

    #[Groups(['read:comment:list', 'write:comment'])]
    #[Assert\Length(max: 200, maxMessage: '200 caractères maximum')]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $company = null;

    #[Groups(['read:comment:list', 'write:comment'])]
    #[Assert\NotBlank(message: 'Veuillez écrire un message')]
    #[Assert\Length(max: 2000, maxMessage: '2000 caractères maximum')]
    #[ORM\Column(type: Types::TEXT)]
    private ?string $content = null;

    #[Groups(['read:comment:list'])]
    #[ORM\Column]
    private \DateTimeImmutable $createdAt;

    #[Groups(['read:comment:list'])]
    #[ORM\OneToMany(targetEntity: Answer::class, mappedBy: 'comment', orphanRemoval: true)]
    private Collection $answers;


    public function __construct()
    {
        $this->createdAt = DateTimeImmutableGenerator::now();
        $this->answers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProject(): ?Project
    {
        return $this->project;
    }

    public function setProject(?Project $project): static
    {
        $this->project = $project;

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

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return Collection<int, Answer>
     */
    public function getAnswers(): Collection
    {
        return $this->answers;
    }

    public function addAnswer(Answer $answer): static
    {
        if (!$this->answers->contains($answer)) {
            $this->answers->add($answer);
            $answer->setComment($this);
        }

        return $this;
    }

    public function removeAnswer(Answer $answer): static
    {
        if ($this->answers->removeElement($answer)) {
            // set the owning side to null (unless already changed)
            if ($answer->getComment() === $this) {
                $answer->setComment(null);
            }
        }

        return $this;
    }
}
