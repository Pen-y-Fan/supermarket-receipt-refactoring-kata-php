<?php

declare(strict_types=1);

namespace Tests;

use ApprovalTests\Approvals;
use PHPUnit\Framework\TestCase;
use Supermarket\model\Discount;
use Supermarket\model\Product;
use Supermarket\model\ProductUnit;
use Supermarket\model\Receipt;
use Supermarket\ReceiptPrinter;

class ReceiptPrinterTest extends TestCase
{
    /**
     * @var Receipt
     */
    private $receipt;

    /**
     * @var Product
     */
    private $apples;

    /**
     * @var Product
     */
    private $toothbrush;

    protected function setUp(): void
    {
        parent::setUp();
        $this->receipt = new Receipt();
        $this->apples = new Product('apples', ProductUnit::KILO);
        $this->toothbrush = new Product('toothbrush', ProductUnit::EACH);
    }

    public function testOneLineItem(): void
    {
        $this->receipt->addProduct($this->toothbrush, 1, 0.99, 0.99);
        Approvals::verifyString($this->getPrintReceipt());
    }

    public function testTwoLineItems(): void
    {
        $this->receipt->addProduct($this->toothbrush, 2, 0.99, 0.99 * 2);
        Approvals::verifyString($this->getPrintReceipt());
    }

    public function testLooseWeight(): void
    {
        $this->receipt->addProduct($this->apples, 2.3, 1.99, 1.99 * 2.3);
        Approvals::verifyString($this->getPrintReceipt());
    }

    public function testTotal(): void
    {
        $this->receipt->addProduct($this->toothbrush, 1, 0.99, 2 * 0.99);
        $this->receipt->addProduct($this->apples, 0.75, 1.99, 1.99 * 0.75);
        Approvals::verifyString($this->getPrintReceipt());
    }

    public function testDiscounts(): void
    {
        $this->receipt->addDiscount(new Discount($this->apples, '3 for 2', -0.99));
        Approvals::verifyString($this->getPrintReceipt());
    }

    public function testPrintWholeReceipt(): void
    {
        $this->receipt->addProduct($this->toothbrush, 1, 0.99, 0.99);
        $this->receipt->addProduct($this->toothbrush, 2, 0.99, 2 * 0.99);
        $this->receipt->addProduct($this->apples, 0.75, 1.99, 1.99 * 0.75);
        $this->receipt->addDiscount(new Discount($this->toothbrush, '3 for 2', -0.99));
        Approvals::verifyString($this->getPrintReceipt());
    }

    private function getPrintReceipt(): string
    {
        return (new ReceiptPrinter())->printReceipt($this->receipt);
    }
}