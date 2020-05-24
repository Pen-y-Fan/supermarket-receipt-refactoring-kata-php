<?php

declare(strict_types=1);

namespace Supermarket\model;

use SplObjectStorage;

class FiveForAmount implements SpecialOffer
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
        if ($productQuantities[$product] < 5) {
            return null;
        }
        return new Discount(
            $product,
            5 . ' for ' . $offer->argument,
            -($catalog->getUnitPrice($product) * $productQuantities[$product]
                - ($offer->argument * floor((int) $productQuantities[$product] / 5)
                    + ((int) $productQuantities[$product] % 5) * $catalog->getUnitPrice($product)))
        );
    }
}
