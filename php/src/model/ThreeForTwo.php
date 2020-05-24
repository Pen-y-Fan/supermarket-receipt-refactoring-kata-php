<?php

declare(strict_types=1);

namespace Supermarket\model;

use SplObjectStorage;

class ThreeForTwo implements SpecialOffer
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
        if ($productQuantities[$product] < 3) {
            return null;
        }
        return new Discount(
            $product,
            '3 for 2',
            -($productQuantities[$product] * $catalog->getUnitPrice($product)
                - ((floor((int) $productQuantities[$product] / 3) * 2 * $catalog->getUnitPrice($product))
                    + ((int) $productQuantities[$product] % 3) * $catalog->getUnitPrice($product)))
        );
    }
}
