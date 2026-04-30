<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use App\State\OrderProcessor;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Delete;

#[ApiResource(
    operations: [
        new Get(),
        new GetCollection(),
        new Post(processor: OrderProcessor::class),
        new Delete(),
    ],
    normalizationContext: ['groups' => ['order:read']],
    denormalizationContext: ['groups' => ['order:write']]
)]
#[ORM\Entity]
#[ORM\Table(name: '`order`')]
class Order
{
    public const PAYMENT_CARD = 'card';
    public const PAYMENT_CASH = 'cash';
    public const PAYMENT_LATER = 'later';

    #[Groups(['order:read'])]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups(['order:read'])]
    #[ORM\Column]
    private \DateTimeImmutable $createdAt;

    #[Groups(['order:read', 'order:write'])]
    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $agent = null;

    #[Groups(['order:read', 'order:write'])]
    #[ORM\ManyToOne(targetEntity: DiscountBeneficiary::class)]
    #[ORM\JoinColumn(nullable: true)]
    private ?DiscountBeneficiary $discountBeneficiary = null;

    #[Groups(['order:read', 'order:write'])]
    #[ORM\ManyToOne(targetEntity: CustomerDebtAccount::class)]
    #[ORM\JoinColumn(nullable: true)]
    private ?CustomerDebtAccount $debtAccount = null;

    #[Groups(['order:read', 'order:write'])]
    #[ORM\Column(length: 20)]
    private string $paymentMethod = self::PAYMENT_CASH;

    #[Groups(['order:read', 'order:write'])]
    #[ORM\Column]
    private bool $paid = true;

    #[Groups(['order:read'])]
    #[ORM\Column(type: 'integer')]
    private int $subtotalCents = 0;

    #[Groups(['order:read'])]
    #[ORM\Column(type: 'integer')]
    private int $discountCents = 0;

    #[Groups(['order:read'])]
    #[ORM\Column(type: 'integer')]
    private int $totalCents = 0;

    #[Groups(['order:read', 'order:write'])]
    #[ORM\OneToMany(mappedBy: 'orderRef', targetEntity: OrderItem::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $items;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->items = new ArrayCollection();
    }

    public function getId(): ?int { return $this->id; }

    public function getCreatedAt(): \DateTimeImmutable { return $this->createdAt; }

    public function getAgent(): ?User { return $this->agent; }
    public function setAgent(?User $agent): static { $this->agent = $agent; return $this; }

    public function getDiscountBeneficiary(): ?DiscountBeneficiary { return $this->discountBeneficiary; }
    public function setDiscountBeneficiary(?DiscountBeneficiary $discountBeneficiary): static
    {
        $this->discountBeneficiary = $discountBeneficiary;
        return $this;
    }

    public function getDebtAccount(): ?CustomerDebtAccount { return $this->debtAccount; }
    public function setDebtAccount(?CustomerDebtAccount $debtAccount): static
    {
        $this->debtAccount = $debtAccount;
        return $this;
    }

    public function getPaymentMethod(): string { return $this->paymentMethod; }
    public function setPaymentMethod(string $paymentMethod): static
    {
        $this->paymentMethod = $paymentMethod;

        if ($paymentMethod === self::PAYMENT_LATER) {
            $this->paid = false;
        }

        return $this;
    }

    public function isPaid(): bool { return $this->paid; }
    public function setPaid(bool $paid): static { $this->paid = $paid; return $this; }

    public function getSubtotalCents(): int { return $this->subtotalCents; }
    public function setSubtotalCents(int $subtotalCents): static { $this->subtotalCents = $subtotalCents; return $this; }

    public function getDiscountCents(): int { return $this->discountCents; }
    public function setDiscountCents(int $discountCents): static { $this->discountCents = $discountCents; return $this; }

    public function getTotalCents(): int { return $this->totalCents; }
    public function setTotalCents(int $totalCents): static { $this->totalCents = $totalCents; return $this; }

    public function getItems(): Collection { return $this->items; }

    public function addItem(OrderItem $item): static
    {
        if (!$this->items->contains($item)) {
            $this->items->add($item);
            $item->setOrderRef($this);
        }

        return $this;
    }

    public function removeItem(OrderItem $item): static
    {
        if ($this->items->removeElement($item)) {
            if ($item->getOrderRef() === $this) {
                $item->setOrderRef(null);
            }
        }

        return $this;
    }
}
