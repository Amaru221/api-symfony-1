<?php

namespace App\Entity;

use Carbon\Carbon;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Post;
use Doctrine\DBAL\Types\Types;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Delete;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use App\Repository\DragonTreasureRepository;
use ApiPlatform\Doctrine\Orm\Filter\RangeFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Serializer\Filter\PropertyFilter;
use ApiPlatform\Doctrine\Orm\Filter\BooleanFilter;
use DateTimeImmutable;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;

#[ApiResource(
    shortName: "Treasure",
    description : "A rare and valuable treasure.",
    operations: [
        new Get(
            normalizationContext: [
                'groups' => ['treasure:read', 'treasure:item:get'],
            ]
        ),
        new Post(),
        new GetCollection(),
        new Delete(),
        new Put(),
        new Patch(),
    ],
    normalizationContext: [
        'groups' => ['treasure:read'],
    ],
    denormalizationContext: [
        'groups' => ['treasure:write'],
    ],
    paginationItemsPerPage: 10,
    formats: [
        'jsonld',
        'json',
        'html',
        'csv' => 'text/csv',
        'jsonhal'
    ]
    
)]
#[ApiResource(
    uriTemplate: '/api/users/{user_id}/treasures.{_format}',
    shortName: 'Treasure',
    operations: [new GetCollection()]
)]
#[ORM\Entity(repositoryClass: DragonTreasureRepository::class)]
#[ApiFilter(PropertyFilter::class)]
#[ApiFilter(SearchFilter::class, properties: [
    'owner.username' => 'partial'
])]
class DragonTreasure
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank]
    #[Assert\Length(min:2, max: 50, maxMessage: 'Describe your loot in 50 chars or less')]
    #[ORM\Column(length: 255)]
    #[Groups(['treasure:read', 'treasure:write', 'user:read', 'user:write'])]
    #[ApiFilter(SearchFilter::class, strategy: 'partial')]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['treasure:read', 'treasure:write', 'user:read', 'user:write'])]

     /**
     * Undocumented variable
     *
     * @var integer|null
     */
    #[Assert\NotBlank]
    #[ApiFilter(SearchFilter::class, strategy: 'partial')]
    private ?string $description = null;

    #[Assert\GreaterThanOrEqual(0)]
    #[ORM\Column]
    #[Groups(['treasure:read', 'treasure:write', 'user:read', 'user:write'])]
    #[ApiFilter(RangeFilter::class)]
    private ?int $value = 0;

    #[Assert\GreaterThanOrEqual(0)]
    #[Assert\LessThanOrEqual(10)]
    #[ORM\Column]
    #[Groups(['treasure:read', 'treasure:write'])]
    private ?int $coolFactor = 0;

    #[ORM\Column]
    private ?\DateTimeImmutable $plunderedAt = null;

    #[ORM\Column]
    #[ApiFilter(BooleanFilter::class)]
    private ?bool $isPublished = false;

    #[ORM\ManyToOne(inversedBy: 'dragonTreasures')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['treasure:read', 'treasure:write'])]
    #[ApiFilter(SearchFilter::class, strategy: 'exact')]
    #[Assert\Valid]
    private ?User $owner = null;

    public function __construct(string $name = null)
    {
        $this->name = $name;
        $this->plunderedAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    /*public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }*/

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    #[SerializedName('description')]
    #[Groups(['treasure:write'])]
    public function setTextDescription(string $description): static
    {
        $this->description = nl2br($description);

        return $this;
    }

    #[Groups(['treasure:read'])]
    public function getShortDescription(): ?string
    {
        return substr($this->description, 0, 100). '...';
    }

    public function getValue(): ?int
    {
        return $this->value;
    }

    public function setValue(int $value): static
    {
        $this->value = $value;

        return $this;
    }

    public function getCoolFactor(): ?int
    {
        return $this->coolFactor;
    }

    public function setCoolFactor(int $coolFactor): static
    {
        $this->coolFactor = $coolFactor;

        return $this;
    }

    public function getPlunderedAt(): ?\DateTimeImmutable
    {
        return $this->plunderedAt;
    }

    public function setPlunderedAt(DateTimeImmutable $createdAt): static
    {
        $this->plunderedAt = $createdAt;

        return $this;
    }

    #[Groups(['treasure:read'])]
    public function getPlunderedAtAgo(): ? string
    {
        return Carbon::instance($this->plunderedAt)->diffForHumans();
    }

    

    public function getIsPublished(): ?bool
    {
        return $this->isPublished;
    }

    public function setIsPublished(bool $isPublished): static
    {
        $this->isPublished = $isPublished;

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): static
    {
        $this->owner = $owner;

        return $this;
    }
}
