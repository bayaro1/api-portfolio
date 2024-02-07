<?php

namespace App\Entity;

use DateTimeImmutable;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Patch;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiFilter;
use App\Repository\SkillRepository;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use Symfony\Component\HttpFoundation\File\File;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use App\Controller\Skill\Admin\ReadSkillItemController;
use App\Controller\Skill\Admin\WriteSkillController;
use App\Controller\Skill\ReadSkillListController;
use Symfony\Component\Serializer\Attribute\Groups;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ApiResource(
    paginationEnabled: false,
    operations: [
        new GetCollection(
            normalizationContext: ['groups' => ['read:skill:list']],
            controller: ReadSkillListController::class //setLogoPath
        ),
        new Get(
            uriTemplate: '/admin/skills/{id}',
            security: 'is_granted("ROLE_ADMIN")',
            normalizationContext: ['groups' => ['admin:read:skill:item']],
            controller: ReadSkillItemController::class //setLogoBase64
        ),
        new Post(
            uriTemplate: '/admin/skills',
            security: 'is_granted("ROLE_ADMIN")',
            stateless: false,
            denormalizationContext: ['groups' => ['admin:write:skill']],
            normalizationContext: ['groups' => ['admin:read:skill:item']],
            controller: WriteSkillController::class //upload du logo
        ), 
        new Patch(
            uriTemplate: '/admin/skills/{id}',
            security: 'is_granted("ROLE_ADMIN")',
            stateless: false,
            denormalizationContext: ['groups' => ['admin:write:skill']],
            normalizationContext: ['groups' => ['admin:read:skill:item']],
            controller: WriteSkillController::class //upload du logo
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
    #[Groups(['read:skill:list', 'admin:read:skill:item'])]
    private ?int $id = null;

    #[ApiFilter(SearchFilter::class, strategy: 'partial')]
    #[Groups(['admin:read:skill:item', 'read:skill:list', 'admin:write:skill'])]
    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[Groups(['admin:read:skill:item', 'read:skill:list', 'admin:write:skill'])]
    #[ORM\Column(length: 255)]
    private ?string $category = null;

    #[Groups(['admin:read:skill:item', 'read:skill:list', 'admin:write:skill'])]
    #[ORM\Column]
    private ?\DateTimeImmutable $learnedAt = null;

    //IMAGE
    
    #[Vich\UploadableField(mapping: 'skill_logo', fileNameProperty: 'logoName', size: 'logoSize')]
    private ?File $logoFile = null;

    #[Groups(['admin:read:skill:item', 'read:skill:list'])]
    private ?string $logoPath = null;

    #[Groups(['admin:read:skill:item', 'admin:write:skill'])]
    private ?string $logoBase64 = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $logoName = null;

    #[Groups(['admin:read:skill:item'])]
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

    public function getLogoPath(): ?string
    {
        return $this->logoPath;
    }

    public function setLogoPath(string $logoPath): static
    {
        $this->logoPath = $logoPath;

        return $this;
    }
}
