<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    normalizationContext: ['groups' => ['discount:read']],
    denormalizationContext: ['groups' => ['discount:write']]
)]
#[ORM\Entity]
class DiscountBeneficiary
{
    #[Groups(['discount:read', 'order:read'])]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups(['discount:read', 'discount:write', 'order:read'])]
    #[Assert\NotBlank]
    #[ORM\Column(length: 255)]
    private ?string $title = null; // Issam, Bénévole...

    #[Groups(['discount:read', 'discount:write', 'order:read'])]
    #[Assert\Range(min: 0, max: 100)]
    #[ORM\Column]
    private int $percentage = 0; // 50, 100...

    #[Groups(['discount:read', 'discount:write'])]
    #[ORM\Column]
    private bool $active = true;

    public function getId(): ?int { return $this->id; }

    public function getTitle(): ?string { return $this->title; }
    public function setTitle(string $title): static { $this->title = $title; return $this; }

    public function getPercentage(): int { return $this->percentage; }
    public function setPercentage(int $percentage): static { $this->percentage = $percentage; return $this; }

    public function isActive(): bool { return $this->active; }
    public function setActive(bool $active): static { $this->active = $active; return $this; }
}
