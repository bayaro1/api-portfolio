<?php

namespace App\Entity;

use DateTimeImmutable;
use ApiPlatform\Metadata\Get;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\ApiResource\StateProvider\LastProjectProvider;
use App\Controller\Project\Admin\AdminReadProjectItemController;
use App\Controller\Project\Admin\WriteProjectController;
use App\Controller\Project\ReadProjectItemController;
use App\Controller\Project\ReadProjectListController;
use App\Repository\ProjectRepository;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ApiResource(
    paginationEnabled: false,
    operations: [
        new GetCollection(
            normalizationContext: ['groups' => ['read:project:list']],
            order: ['endAt' => 'DESC'],
            controller: ReadProjectListController::class //setPicturesPath
        ),
        new Get(
            normalizationContext: ['groups' => ['read:project:item']],
            controller: ReadProjectItemController::class //setPicturesPath
        ),
        new Get(
            uriTemplate: '/project/last',
            openapiContext: [
                'summary' => 'Retrieve last project',
                'description' => 'Retrieve last project'
            ],
            normalizationContext: ['groups' => ['read:project:item']],
            provider: LastProjectProvider::class,
            controller: ReadProjectItemController::class //setPicturesPath
        ),
        new Get(
            uriTemplate: '/admin/project/{id}',
            security: 'is_granted("ROLE_ADMIN")',
            normalizationContext: ['groups' => ['read:project:item', 'admin:read:project:item']],
            controller: AdminReadProjectItemController::class, //setPicturesBase64
            stateless: false
        ),
        new Post(
            uriTemplate: '/admin/project',
            security: 'is_granted("ROLE_ADMIN")',
            normalizationContext: ['groups' => ['read:project:item', 'admin:read:project:item']],
            denormalizationContext: ['groups' => ['admin:write:project']],
            controller: WriteProjectController::class, //upload pictures
            stateless: false
        ),
        new Patch(
            uriTemplate: '/admin/project/{id}',
            security: 'is_granted("ROLE_ADMIN")',
            normalizationContext: ['groups' => ['read:project:item', 'admin:read:project:item']],
            denormalizationContext: ['groups' => ['admin:write:project']],
            controller: WriteProjectController::class, //upload pictures
            stateless: false
        ),
        new Delete(
            uriTemplate: '/admin/project/{id}',
            security: 'is_granted("ROLE_ADMIN")',
            stateless: false
        )
    ]
)]
#[ORM\Entity(repositoryClass: ProjectRepository::class)]
#[Vich\Uploadable]
class Project
{
    #[Groups(['read:project:list', 'read:project:item'])]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank(message: 'Le titre est obligatoire')]
    #[Assert\Length(max: 200, maxMessage: '200 caractères maximum')]
    #[Groups(['read:project:list', 'read:project:item', 'admin:write:project', 'admin:read:comment:list'])]
    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[Assert\NotBlank(message: 'Le type est obligatoire')]
    #[Assert\Length(max: 200, maxMessage: '200 caractères maximum')]
    #[Groups(['read:project:list', 'read:project:item', 'admin:write:project'])]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $type = null;

    #[Assert\Length(max: 200, maxMessage: '200 caractères maximum')]
    #[Groups(['read:project:list', 'read:project:item', 'admin:write:project'])]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $url = null;

    #[Assert\Length(max: 200, maxMessage: '250 caractères maximum')]
    #[Groups(['read:project:list', 'read:project:item', 'admin:write:project'])]
    #[ORM\Column(length: 255)]
    private ?string $shortDescription = null;

    #[Assert\Length(max: 5000, maxMessage: '5000 caractères maximum')]
    #[Groups(['read:project:item', 'admin:write:project'])]
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $longDescription = null;

    #[Assert\NotNull(message: 'La date de début est obligatoire')]
    #[Groups(['read:project:list', 'read:project:item', 'admin:write:project'])]
    #[ORM\Column]
    private ?\DateTimeImmutable $startedAt = null;

    #[Assert\NotNull(message: 'La date de fin est obligatoire')]
    #[Groups(['read:project:list', 'read:project:item', 'admin:write:project'])]
    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $endAt = null;

    #[ORM\OneToMany(targetEntity: Comment::class, mappedBy: 'project', orphanRemoval: true)]
    private Collection $comments;

    //IMAGES
    
    //en cas de modification uniquement d'une image ce champ sera modifié : obligatoire pour que le changement d'image soit persisté
    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    //screen_mobile
    #[Assert\File(maxSize: '500k', maxSizeMessage: 'Poids maximum autorisé : 500k', mimeTypes: ['image/jpeg', 'image/jpg'], mimeTypesMessage: 'Formats acceptés : jpeg, jpg')]
    #[Vich\UploadableField(mapping: 'project_screen_mobile', fileNameProperty: 'screenMobileName', size: 'screenMobileSize')]
    private ?File $screenMobileFile = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $screenMobileName = null;

    #[Groups(['admin:read:project:item'])]
    #[ORM\Column(nullable: true)]
    private ?int $screenMobileSize = null;

    #[Groups(['admin:read:project:item', 'admin:write:project'])]
    private ?string $screenMobileBase64 = null;

    #[Groups(['read:project:list', 'read:project:item'])]
    private ?string $screenMobilePath = null;

    //screen_desktop
    #[Assert\File(maxSize: '500k', maxSizeMessage: 'Poids maximum autorisé : 500k', mimeTypes: ['image/jpeg', 'image/jpg'], mimeTypesMessage: 'Formats acceptés : jpeg, jpg')]
    #[Vich\UploadableField(mapping: 'project_screen_desktop', fileNameProperty: 'screenDesktopName', size: 'screenDesktopSize')]
    private ?File $screenDesktopFile = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $screenDesktopName = null;
    
    #[Groups(['admin:read:project:item'])]
    #[ORM\Column(nullable: true)]
    private ?int $screenDesktopSize = null;

    #[Groups(['admin:read:project:item', 'admin:write:project'])]
    private ?string $screenDesktopBase64 = null;

    #[Groups(['read:project:list', 'read:project:item'])]
    private ?string $screenDesktopPath = null;


    public function __construct()
    {
        $this->comments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): static
    {
        $this->url = $url;

        return $this;
    }

    public function getShortDescription(): ?string
    {
        return $this->shortDescription;
    }

    public function setShortDescription(string $shortDescription): static
    {
        $this->shortDescription = $shortDescription;

        return $this;
    }

    public function getLongDescription(): ?string
    {
        return $this->longDescription;
    }

    public function setLongDescription(?string $longDescription): static
    {
        $this->longDescription = $longDescription;

        return $this;
    }

    public function getStartedAt(): ?\DateTimeImmutable
    {
        return $this->startedAt;
    }

    public function setStartedAt(\DateTimeImmutable $startedAt): static
    {
        $this->startedAt = $startedAt;

        return $this;
    }

    public function getEndAt(): ?\DateTimeImmutable
    {
        return $this->endAt;
    }

    public function setEndAt(\DateTimeImmutable $endAt): static
    {
        $this->endAt = $endAt;

        return $this;
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): static
    {
        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
            $comment->setProject($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): static
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getProject() === $this) {
                $comment->setProject(null);
            }
        }

        return $this;
    }

    

    public function getScreenMobileName(): ?string
    {
        return $this->screenMobileName;
    }

    public function setScreenMobileName(?string $screenMobileName): static
    {
        $this->screenMobileName = $screenMobileName;

        return $this;
    }

    public function getScreenDesktopName(): ?string
    {
        return $this->screenDesktopName;
    }

    public function setScreenDesktopName(?string $screenDesktopName): static
    {
        $this->screenDesktopName = $screenDesktopName;

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

    public function getScreenMobileFile(): ?File
    {
        return $this->screenMobileFile;
    }

    public function setScreenMobileFile(?File $screenMobileFile = null): static
    {
        $this->screenMobileFile = $screenMobileFile;

        if(null !== $screenMobileFile)
        {
            $this->updatedAt = new DateTimeImmutable();
        }

        return $this;
    }

    public function getScreenMobileSize(): ?int
    {
        return $this->screenMobileSize;
    }

    public function setScreenMobileSize(?int $screenMobileSize): static
    {
        $this->screenMobileSize = $screenMobileSize;

        return $this;
    }

    public function getScreenMobileBase64(): ?string
    {
        return $this->screenMobileBase64;
    }

    public function setScreenMobileBase64(?string $screenMobileBase64): static
    {
        $this->screenMobileBase64 = $screenMobileBase64;

        return $this;
    }

    public function getScreenDesktopFile(): ?File
    {
        return $this->screenDesktopFile;
    }

    public function setScreenDesktopFile(?File $screenDesktopFile = null): static
    {
        $this->screenDesktopFile = $screenDesktopFile;

        if(null !== $screenDesktopFile)
        {
            $this->updatedAt = new DateTimeImmutable();
        }

        return $this;
    }

    public function getScreenDesktopSize(): ?int
    {
        return $this->screenDesktopSize;
    }

    public function setScreenDesktopSize(?int $screenDesktopSize): static
    {
        $this->screenDesktopSize = $screenDesktopSize;

        return $this;
    }
    
    public function getScreenDesktopBase64(): ?string
    {
        return $this->screenDesktopBase64;
    }

    public function setScreenDesktopBase64(?string $screenDesktopBase64): static
    {
        $this->screenDesktopBase64 = $screenDesktopBase64;

        return $this;
    }

    public function getScreenDesktopPath(): ?string
    {
        return $this->screenDesktopPath;
    }

    public function setScreenDesktopPath(?string $screenDesktopPath): static
    {
        $this->screenDesktopPath = $screenDesktopPath;

        return $this;
    }

    public function getScreenMobilePath(): ?string
    {
        return $this->screenMobilePath;
    }

    public function setScreenMobilePath(?string $screenMobilePath): static
    {
        $this->screenMobilePath = $screenMobilePath;

        return $this;
    }
}
