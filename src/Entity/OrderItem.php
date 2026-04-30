<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ApiResource(
    normalizationContext: ['groups' => ['order_item:read']],
    denormalizationContext: ['groups' => ['order_item:write']]
)]
#[ORM\Entity]
class OrderItem
{
    #[Groups(['order:read', 'order_item:read'])]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Order::class, inversedBy: 'items')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?Order $orderRef = null;

    #[Groups(['order:read', 'order:write', 'order_item:read', 'order_item:write'])]
    #[ORM\ManyToOne(targetEntity: Product::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Product $product = null;

    #[Groups(['order:read', 'order:write', 'order_item:read', 'order_item:write'])]
    #[ORM\Column]
    private int $quantity = 1;

    #[Groups(['order:read', 'order_item:read'])]
    #[ORM\Column(type: 'integer')]
    private int $unitPriceCents = 0;

    #[Groups(['order:read', 'order_item:read'])]
    #[ORM\Column(type: 'integer')]
    private int $totalCents = 0;

    public function getId(): ?int { return $this->id; }

    public function getOrderRef(): ?Order { return $this->orderRef; }
    public function setOrderRef(?Order $orderRef): static { $this->orderRef = $orderRef; return $this; }

    public function getProduct(): ?Product { return $this->product; }
    public function setProduct(?Product $product): static { $this->product = $product; return $this; }

    public function getQuantity(): int { return $this->quantity; }
    public function setQuantity(int $quantity): static { $this->quantity = $quantity; return $this; }

    public function getUnitPriceCents(): int { return $this->unitPriceCents; }
    public function setUnitPriceCents(int $unitPriceCents): static { $this->unitPriceCents = $unitPriceCents; return $this; }

    public function getTotalCents(): int { return $this->totalCents; }
    public function setTotalCents(int $totalCents): static { $this->totalCents = $totalCents; return $this; }
}
