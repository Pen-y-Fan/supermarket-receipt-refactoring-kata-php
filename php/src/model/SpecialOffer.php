<?php

declare(strict_types=1);

namespace Supermarket\model;

use SplObjectStorage;

interface SpecialOffer
{
    public function calculateDiscount(
        Product $product,
        Offer $offer,
        SplObjectStorage $productQuantities,
        SupermarketCatalog $catalog
    ): ?Discount;
}
