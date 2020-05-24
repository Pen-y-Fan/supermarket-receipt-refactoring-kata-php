<?php

declare(strict_types=1);

namespace Supermarket\model;

use SplObjectStorage;

class Teller
{
    /**
     * @var SupermarketCatalog
     */
    private $catalog;

    /**
     * @var SplObjectStorage
     */
    private $offers;

    public function __construct(SupermarketCatalog $catalog)
    {
        $this->catalog = $catalog;
        $this->offers = new SplObjectStorage();
    }

    public function addSpecialOffer(SpecialOffer $offerType, Product $product, float $argument): void
    {
        $this->offers[$product] = new Offer($offerType, $argument);
    }

    public function checksOutArticlesFrom(ShoppingCart $theCart): Receipt
    {
        $receipt = new Receipt();
        $this->addItems($theCart, $receipt);

        $theCart->handleOffers($receipt, $this->offers, $this->catalog);

        return $receipt;
    }

    private function addItems(ShoppingCart $theCart, Receipt $receipt): void
    {
        /** @var ProductQuantity $productQuantity */
        foreach ($theCart->getItems() as $productQuantity) {
            $receipt->addProduct(
                $productQuantity->getProduct(),
                $productQuantity->getQuantity(),
                $this->catalog->getUnitPrice($productQuantity->getProduct())
            );
        }
    }
}
