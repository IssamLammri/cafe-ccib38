<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    normalizationContext: ['groups' => ['product:read']],
    denormalizationContext: ['groups' => ['product:write']],
    paginationEnabled: false
)]
#[ORM\Entity]
class Product
{
    #[Groups(['product:read', 'order:read'])]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups(['product:read', 'product:write', 'order:read'])]
    #[Assert\NotBlank]
    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[Groups(['product:read', 'product:write'])]
    #[ORM\Column(length: 100)]
    private ?string $category = null; // café, thé, sandwich, boisson, gâteau...

    #[Groups(['product:read', 'product:write', 'order:read'])]
    #[ORM\Column(type: 'integer')]
    private int $priceCents = 0;

    #[Groups(['product:read', 'product:write'])]
    #[ORM\Column]
    private bool $active = true;

    public function getId(): ?int { return $this->id; }

    public function getName(): ?string { return $this->name; }
    public function setName(string $name): static { $this->name = $name; return $this; }

    public function getCategory(): ?string { return $this->category; }
    public function setCategory(string $category): static { $this->category = $category; return $this; }

    public function getPriceCents(): int { return $this->priceCents; }
    public function setPriceCents(int $priceCents): static { $this->priceCents = $priceCents; return $this; }

    public function isActive(): bool { return $this->active; }
    public function setActive(bool $active): static { $this->active = $active; return $this; }
}
