<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Repository\SkillRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Attribute\Groups;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ApiResource(
    paginationEnabled: false,
    operations: [
        new GetCollection(
            normalizationContext: ['groups' => ['read:skill:list']]
        ),
        new Post(
            security: 'is_granted("ROLE_ADMIN")'
        ), 
        new Patch(
            security: 'is_granted("ROLE_ADMIN")'
        )
    ]
)]
#[ORM\Entity(repositoryClass: SkillRepository::class)]
#[Vich\Uploadable]
class Skill
{
    public const CAT_FRAMEWORKS = 'skill.cat_frameworks';

    public const CAT_LANGUAGES = 'skill.cat_languages';

    public const CAT_UTILS = 'skill.cat_utils';


    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ApiFilter(SearchFilter::class, strategy: 'partial')]
    #[Groups(['read:skill:list'])]
    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[Groups(['read:skill:list'])]
    #[ORM\Column(length: 255)]
    private ?string $category = null;

    #[Groups(['read:skill:list'])]
    #[ORM\Column]
    private ?\DateTimeImmutable $learnedAt = null;

    //IMAGE
    
    #[Vich\UploadableField(mapping: 'skill_logo', fileNameProperty: 'logoName', size: 'logoSize')]
    private ?File $logoFile = null;

    private ?string $logoBase64 = null;

    #[Groups(['read:skill:list'])]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $logoName = null;

    #[ORM\Column(nullable: true)]
    private ?int $logoSize = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(string $category): static
    {
        $this->category = $category;

        return $this;
    }

    public function getLearnedAt(): ?\DateTimeImmutable
    {
        return $this->learnedAt;
    }

    public function setLearnedAt(\DateTimeImmutable $learnedAt): static
    {
        $this->learnedAt = $learnedAt;

        return $this;
    }

    public function getLogoName(): ?string
    {
        return $this->logoName;
    }

    public function setLogoName(?string $logoName): static
    {
        $this->logoName = $logoName;

        return $this;
    }

    public function getUpdatedAt(): ?DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getLogoSize(): ?int
    {
        return $this->logoSize;
    }

    public function setLogoSize(?int $logoSize): static
    {
        $this->logoSize = $logoSize;

        return $this;
    }

    public function getLogoFile(): ?File
    {
        return $this->logoFile;
    }

    public function setLogoFile(?File $logoFile = null): static
    {
        $this->logoFile = $logoFile;

        if(null !== $logoFile)
        {
            $this->updatedAt = new DateTimeImmutable();
        }

        return $this;
    }

    public function getLogoBase64(): ?string
    {
        return $this->logoBase64;
    }

    public function setLogoBase64(?string $logoBase64): static
    {
        $this->logoBase64 = $logoBase64;

        return $this;
    }
}
