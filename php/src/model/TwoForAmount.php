<?php

declare(strict_types=1);

namespace Supermarket\model;

use SplObjectStorage;

class TwoForAmount implements SpecialOffer
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
        if ($productQuantities[$product] < 2) {
            return null;
        }
        return new Discount(
            $product,
            '2 for ' . $offer->argument,
            -($catalog->getUnitPrice($product) * $productQuantities[$product]
                - $offer->argument * ((int) $productQuantities[$product] / 2)
            + (int) $productQuantities[$product] % 2 * $catalog->getUnitPrice($product))
        );
    }
}
