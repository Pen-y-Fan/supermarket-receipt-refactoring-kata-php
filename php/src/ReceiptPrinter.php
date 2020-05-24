<?php

declare(strict_types=1);

namespace Supermarket;

use Supermarket\model\Discount;
use Supermarket\model\ProductUnit;
use Supermarket\model\Receipt;
use Supermarket\model\ReceiptItem;

class ReceiptPrinter
{
    /**
     * @var int
     */
    private $columns;

    public function __construct(int $columns = 40)
    {
        $this->columns = $columns;
    }

    public function printReceipt(Receipt $receipt): string
    {
        return $this->addItems($receipt) . $this->addDiscount($receipt) . $this->addTotal($receipt);
    }

    private function presentReceiptItem(ReceiptItem $item): string
    {
        return $this->isSingleItem($item)
            ? $this->formatWithItem($item)
            : $this->formatWithItem($item) . $this->formatWithQuantity($item);
    }

    private function presentDiscount(Discount $discount): string
    {
        return $this->formatLineWithWhitespace(
            $discount->getDescription() . '(' . $discount->getProduct()->getName() . ')',
            $this->presentPrice($discount->getDiscountAmount())
        );
    }

    private function presentTotal(Receipt $receipt): string
    {
        return $this->formatLineWithWhitespace('Total: ', $this->presentPrice($receipt->getTotalPrice()));
    }

    private function formatLineWithWhitespace(string $name, string $value): string
    {
        return $name . str_pad('', $this->columns - strlen($name) - strlen($value)) . $value . "\n";
    }

    private function presentPrice(float $price): string
    {
        return sprintf('%.2f', $price);
    }

    private function presentQuantity(ReceiptItem $item): string
    {
        return $item->getProduct()->getUnit() === ProductUnit::EACH
            ? (string) sprintf('%x', (int) $item->getQuantity())
            : (string) sprintf('%.3f', $item->getQuantity());
    }

    private function addItems(Receipt $receipt): string
    {
        return implode(
            '',
            array_map(function ($item) {
                return $this->presentReceiptItem($item);
            }, $receipt->getItems())
        );
    }

    private function addDiscount(Receipt $receipt): string
    {
        return implode(
            '',
            array_map(function ($discount) {
                return $this->presentDiscount($discount);
            }, $receipt->getDiscounts())
        );
    }

    private function addTotal(Receipt $receipt): string
    {
        return "\n" . $this->presentTotal($receipt);
    }

    private function formatWithItem(ReceiptItem $item): string
    {
        return $this->formatLineWithWhitespace(
            $item->getProduct()->getName(),
            $this->presentPrice($item->getTotalPrice())
        );
    }

    private function isSingleItem(ReceiptItem $item): bool
    {
        return $item->getProduct()->getUnit() === ProductUnit::EACH && $item->getQuantity() === 1.0;
    }

    private function formatWithQuantity(ReceiptItem $item): string
    {
        return '  ' . $this->presentPrice($item->getPrice()) . ' * ' . $this->presentQuantity($item) . "\n";
    }
}
