<?php

declare(strict_types=1);

namespace Supermarket\model;

use SplObjectStorage;

class PercentOff implements SpecialOffer
{
    public function calculateDiscount(
        Product $product,
        Offer $offer,
        SplObjectStorage $productQuantities,
        SupermarketCatalog $catalog
    ): ?Discount {
        if (! isset($productQuantities[$product])) {
            return null;
        }
        return new Discount(
            $product,
            sprintf('%.1f', $offer->argument) . '% off',
            -$productQuantities[$product] * $catalog->getUnitPrice($product) * $offer->argument / 100.0
        );
    }
}
