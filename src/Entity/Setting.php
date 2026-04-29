<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ApiResource(
    normalizationContext: ['groups' => ['setting:read']],
    denormalizationContext: ['groups' => ['setting:write']]
)]
#[ORM\Entity]
class Setting
{
    #[Groups(['setting:read'])]
    #[ORM\Id]
    #[ORM\Column]
    private int $id = 1;

    #[Groups(['setting:read', 'setting:write'])]
    #[ORM\Column(length: 255)]
    private ?string $shopName = null;

    #[Groups(['setting:read', 'setting:write'])]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $address = null;

    #[Groups(['setting:read', 'setting:write'])]
    #[ORM\Column(length: 50, nullable: true)]
    private ?string $phone = null;

    #[Groups(['setting:read', 'setting:write'])]
    #[ORM\Column(type: 'decimal', precision: 5, scale: 2)]
    private string $taxRate = '0.00';

    #[Groups(['setting:read', 'setting:write'])]
    #[ORM\Column]
    private bool $enableTax = false;

    #[Groups(['setting:read', 'setting:write'])]
    #[ORM\Column(length: 10)]
    private string $currency = 'EUR';

    #[Groups(['setting:read', 'setting:write'])]
    #[ORM\Column]
    private int $nextTicketNumber = 1;

    public function getId(): int { return $this->id; }

    public function getShopName(): ?string { return $this->shopName; }
    public function setShopName(string $shopName): static { $this->shopName = $shopName; return $this; }

    public function getAddress(): ?string { return $this->address; }
    public function setAddress(?string $address): static { $this->address = $address; return $this; }

    public function getPhone(): ?string { return $this->phone; }
    public function setPhone(?string $phone): static { $this->phone = $phone; return $this; }

    public function getTaxRate(): string { return $this->taxRate; }
    public function setTaxRate(string $taxRate): static { $this->taxRate = $taxRate; return $this; }

    public function isEnableTax(): bool { return $this->enableTax; }
    public function setEnableTax(bool $enableTax): static { $this->enableTax = $enableTax; return $this; }

    public function getCurrency(): string { return $this->currency; }
    public function setCurrency(string $currency): static { $this->currency = $currency; return $this; }

    public function getNextTicketNumber(): int { return $this->nextTicketNumber; }
    public function setNextTicketNumber(int $nextTicketNumber): static
    {
        $this->nextTicketNumber = $nextTicketNumber;
        return $this;
    }
}
