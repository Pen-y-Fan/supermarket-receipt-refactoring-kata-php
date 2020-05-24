<?php

declare(strict_types=1);

namespace Supermarket\model;

class ReceiptItem
{
    /**
     * @var Product
     */
    private $product;

    /**
     * @var float
     */
    private $price;

    /**
     * @var float
     */
    private $quantity;

    public function __construct(Product $product, float $quantity, float $price)
    {
        $this->product = $product;
        $this->quantity = $quantity;
        $this->price = $price;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getProduct(): Product
    {
        return $this->product;
    }

    public function getQuantity(): float
    {
        return $this->quantity;
    }

    public function getTotalPrice(): float
    {
        return $this->quantity * $this->price;
    }
}
