<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    normalizationContext: ['groups' => ['debt_account:read']],
    denormalizationContext: ['groups' => ['debt_account:write']],
    paginationEnabled: false
)]
#[ORM\Entity]
class CustomerDebtAccount
{
    #[Groups(['debt_account:read', 'order:read'])]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups(['debt_account:read', 'debt_account:write', 'order:read'])]
    #[Assert\NotBlank]
    #[ORM\Column(length: 255)]
    private ?string $fullName = null;

    #[Groups(['debt_account:read', 'debt_account:write', 'order:read'])]
    #[ORM\Column(length: 50, nullable: true)]
    private ?string $phone = null;

    public function getId(): ?int { return $this->id; }

    public function getFullName(): ?string { return $this->fullName; }
    public function setFullName(string $fullName): static { $this->fullName = $fullName; return $this; }

    public function getPhone(): ?string { return $this->phone; }
    public function setPhone(?string $phone): static { $this->phone = $phone; return $this; }
}
