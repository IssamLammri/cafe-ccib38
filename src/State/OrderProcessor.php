<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Order;
use App\Entity\OrderItem;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

final class OrderProcessor implements ProcessorInterface
{
    public function __construct(
        #[Autowire(service: 'api_platform.doctrine.orm.state.persist_processor')]
        private ProcessorInterface $persistProcessor,
    ) {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): mixed
    {
        if (!$data instanceof Order) {
            return $this->persistProcessor->process($data, $operation, $uriVariables, $context);
        }

        if ($data->getItems()->isEmpty()) {
            throw new BadRequestHttpException('Une commande doit contenir au moins un produit.');
        }

        $subtotalCents = 0;

        foreach ($data->getItems() as $item) {
            if (!$item instanceof OrderItem) {
                continue;
            }

            $product = $item->getProduct();

            if (!$product) {
                throw new BadRequestHttpException('Chaque ligne de commande doit avoir un produit.');
            }

            if ($item->getQuantity() <= 0) {
                throw new BadRequestHttpException('La quantité doit être supérieure à 0.');
            }

            $unitPriceCents = $product->getPriceCents();
            $lineTotalCents = $unitPriceCents * $item->getQuantity();

            $item->setUnitPriceCents($unitPriceCents);
            $item->setTotalCents($lineTotalCents);
            $item->setOrderRef($data);

            $subtotalCents += $lineTotalCents;
        }

        $discountCents = 0;

        if ($data->getDiscountBeneficiary()) {
            $percentage = $data->getDiscountBeneficiary()->getPercentage();
            $discountCents = (int) round($subtotalCents * $percentage / 100);
        }

        $totalCents = max(0, $subtotalCents - $discountCents);

        $data->setSubtotalCents($subtotalCents);
        $data->setDiscountCents($discountCents);
        $data->setTotalCents($totalCents);

        if ($data->getPaymentMethod() === Order::PAYMENT_LATER) {
            $data->setPaid(false);

            if (!$data->getDebtAccount()) {
                throw new BadRequestHttpException('Une personne doit être sélectionnée pour un paiement plus tard.');
            }
        } else {
            $data->setPaid(true);
            $data->setDebtAccount(null);
        }

        return $this->persistProcessor->process($data, $operation, $uriVariables, $context);
    }
}
