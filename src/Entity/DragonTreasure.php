<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Odm\Filter\BooleanFilter;
use ApiPlatform\Metadata\ApiFilter;
use DateTime;
use Carbon\Carbon;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Post;
use Doctrine\DBAL\Types\Types;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Delete;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use App\Repository\DragonTreasureRepository;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;

#[ApiResource(
    shortName: "Treasure",
    description : "A rare and valuable treasure.",
    operations: [
        new Get(),
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
    
)]
#[ORM\Entity(repositoryClass: DragonTreasureRepository::class)]
#[ApiFilter(BooleanFilter::class, properties: ['isPublished'])]
class DragonTreasure
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['treasure:read', 'treasure:write'])]
    #[ApiFilter(SearchFilter::class, strategy: 'partial')]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['treasure:read', 'treasure:write'])]

     /**
     * Undocumented variable
     *
     * @var integer|null
     */
    private ?string $description = null;

    #[ORM\Column]
    #[Groups(['treasure:read', 'treasure:write'])]
    private ?int $value = null;

    #[ORM\Column]
    #[Groups(['treasure:read', 'treasure:write'])]
    private ?int $coolFactor = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $plunderedAt = null;

    #[ORM\Column]
    #[ApiFilter(BooleanFilter::class)]
    private ?bool $isPublished = false;

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
    #[Groups('treasure:write')]
    public function setTextDescription(string $description): static
    {
        $this->description = nl2br($description);

        return $this;
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

    public function setPlunderedAt(DateTime $createdAt): static
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
}
