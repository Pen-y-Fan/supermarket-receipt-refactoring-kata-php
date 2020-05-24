<?php

declare(strict_types=1);

namespace Supermarket\model;

use SplObjectStorage;

class ShoppingCart
{
    /**
     * @var array
     */
    private $items = [];

    /**
     * @var SplObjectStorage
     */
    private $productQuantities;

    public function __construct()
    {
        $this->productQuantities = new SplObjectStorage();
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function addItem(Product $product): void
    {
        $this->addItemQuantity($product, 1.0);
    }

    public function addItemQuantity(Product $product, float $quantity): void
    {
        $this->items[] = new ProductQuantity($product, $quantity);
        $this->addProductQuantity($product, $quantity);
    }

    public function handleOffers(Receipt $receipt, SplObjectStorage $offers, SupermarketCatalog $catalog): void
    {
        /** @var Product $product */
        foreach ($offers as $product) {
            /** @var Offer $offer */
            $offer = $offers[$product];
            $discount = $offer->offerType->calculateDiscount($product, $offer, $this->productQuantities, $catalog);
            if ($discount === null) {
                break;
            }
            $receipt->addDiscount($discount);
        }
    }

    private function addProductQuantity(Product $product, float $quantity): void
    {
        if ($this->isExisting($product)) {
            $this->productQuantities[$product] += $quantity;
            return;
        }
        $this->productQuantities[$product] = $quantity;
    }

    private function isExisting(Product $product): bool
    {
        return isset($this->productQuantities[$product]);
    }
}
