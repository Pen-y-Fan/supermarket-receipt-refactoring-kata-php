<?php

declare(strict_types=1);

namespace Supermarket\model;

class Receipt
{
    /**
     * @var array
     */
    private $items = [];

    /**
     * @var array
     */
    private $discounts = [];

    public function getTotalPrice(): float
    {
        return array_sum(array_map(function ($item) {
            return $item->getTotalPrice();
        }, $this->items))
        + array_sum(array_map(function ($discount) {
            return $discount->getDiscountAmount();
        }, $this->discounts));
    }

    public function addProduct(Product $product, float $quantity, float $price): void
    {
        $this->items[] = new ReceiptItem($product, $quantity, $price);
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function addDiscount(Discount $discount): void
    {
        $this->discounts[] = $discount;
    }

    public function getDiscounts(): array
    {
        return $this->discounts;
    }
}
