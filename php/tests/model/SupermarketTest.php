<?php

declare(strict_types=1);

namespace Tests\model;

use ApprovalTests\Approvals;
use PHPUnit\Framework\TestCase;
use Supermarket\model\Product;
use Supermarket\model\ProductUnit;
use Supermarket\model\Receipt;
use Supermarket\model\ReceiptItem;
use Supermarket\model\ShoppingCart;
use Supermarket\model\SpecialOfferType;
use Supermarket\model\SupermarketCatalog;
use Supermarket\model\Teller;
use Supermarket\ReceiptPrinter;

class SupermarketTest extends TestCase
{
    /**
     * @var SupermarketCatalog
     */
    private $catalog;

    /**
     * @var Teller
     */
    private $teller;

    /**
     * @var ShoppingCart
     */
    private $theCart;

    /**
     * @var Product
     */
    private $toothbrush;

    /**
     * @var Product
     */
    private $rice;

    /**
     * @var Product
     */
    private $apples;

    /**
     * @var Product
     */
    private $cherryTomatoes;

    /**
     * @var Receipt
     */
    private $receipt;

    protected function setUp(): void
    {
        parent::setUp();
        $this->catalog = new FakeCatalog();
        $this->teller = new Teller($this->catalog);
        $this->theCart = new ShoppingCart();

        $this->toothbrush = new Product('toothbrush', ProductUnit::EACH);
        $this->catalog->addProduct($this->toothbrush, 0.99);
        $this->rice = new Product('rice', ProductUnit::EACH);
        $this->catalog->addProduct($this->rice, 2.99);
        $this->apples = new Product('apples', ProductUnit::KILO);
        $this->catalog->addProduct($this->apples, 1.99);
        $this->cherryTomatoes = new Product('cherry tomato box', ProductUnit::EACH);
        $this->catalog->addProduct($this->cherryTomatoes, 0.69);
    }

    public function testAnEmptyShoppingCartShouldCostNothing(): void
    {
        /** @var Receipt $receipt */
        $this->receipt = $this->teller->checksOutArticlesFrom($this->theCart);
        Approvals::verifyString($this->getPrintReceipt());
    }

    public function testOneNormalItem(): void
    {
        $this->theCart->addItem($this->toothbrush);
        /** @var Receipt $receipt */
        $this->receipt = $this->teller->checksOutArticlesFrom($this->theCart);
        Approvals::verifyString($this->getPrintReceipt());
    }

    public function testTwoNormalItems(): void
    {
        $this->theCart->addItem($this->toothbrush);
        $this->theCart->addItem($this->rice);
        /** @var Receipt $receipt */
        $this->receipt = $this->teller->checksOutArticlesFrom($this->theCart);
        Approvals::verifyString($this->getPrintReceipt());
    }

    public function testBuyTwoGetOneFree(): void
    {
        $this->theCart->addItem($this->toothbrush);
        $this->theCart->addItem($this->toothbrush);
        $this->theCart->addItem($this->toothbrush);
        $this->teller->addSpecialOffer(
            SpecialOfferType::THREE_FOR_TWO,
            $this->toothbrush,
            $this->catalog->getUnitPrice($this->toothbrush)
        );
        /** @var Receipt $receipt */
        $this->receipt = $this->teller->checksOutArticlesFrom($this->theCart);
        Approvals::verifyString($this->getPrintReceipt());
    }

    public function testBuyTwoGetOneFreeButInsufficientInBasket(): void
    {
        $this->theCart->addItem($this->toothbrush);
        $this->teller->addSpecialOffer(
            SpecialOfferType::THREE_FOR_TWO,
            $this->toothbrush,
            $this->catalog->getUnitPrice($this->toothbrush)
        );
        /** @var Receipt $receipt */
        $this->receipt = $this->teller->checksOutArticlesFrom($this->theCart);
        Approvals::verifyString($this->getPrintReceipt());
    }

    public function testBuyFiveGetOneFree(): void
    {
        $this->theCart->addItem($this->toothbrush);
        $this->theCart->addItem($this->toothbrush);
        $this->theCart->addItem($this->toothbrush);
        $this->theCart->addItem($this->toothbrush);
        $this->theCart->addItem($this->toothbrush);
        $this->teller->addSpecialOffer(
            SpecialOfferType::THREE_FOR_TWO,
            $this->toothbrush,
            $this->catalog->getUnitPrice($this->toothbrush)
        );
        /** @var Receipt $receipt */
        $this->receipt = $this->teller->checksOutArticlesFrom($this->theCart);
        Approvals::verifyString($this->getPrintReceipt());
    }

    public function testLooseWeightProduct(): void
    {
        $this->theCart->addItemQuantity($this->apples, 0.5);
        /** @var Receipt $receipt */
        $this->receipt = $this->teller->checksOutArticlesFrom($this->theCart);
        Approvals::verifyString($this->getPrintReceipt());
    }

    public function testPercentDiscount(): void
    {
        $this->theCart->addItem($this->rice);
        $this->teller->addSpecialOffer(SpecialOfferType::TEN_PERCENT_DISCOUNT, $this->rice, 10.0);
        /** @var Receipt $receipt */
        $this->receipt = $this->teller->checksOutArticlesFrom($this->theCart);
        Approvals::verifyString($this->getPrintReceipt());
    }

    public function testXForYDiscount(): void
    {
        $this->theCart->addItem($this->cherryTomatoes);
        $this->theCart->addItem($this->cherryTomatoes);
        $this->teller->addSpecialOffer(SpecialOfferType::TWO_FOR_AMOUNT, $this->cherryTomatoes, .99);

        /** @var Receipt $receipt */
        $this->receipt = $this->teller->checksOutArticlesFrom($this->theCart);
        Approvals::verifyString($this->getPrintReceipt());
    }

    public function testXForYDiscountWithInsufficientInBasket(): void
    {
        $this->theCart->addItem($this->cherryTomatoes);
        $this->teller->addSpecialOffer(SpecialOfferType::TWO_FOR_AMOUNT, $this->cherryTomatoes, .99);
        /** @var Receipt $receipt */
        $this->receipt = $this->teller->checksOutArticlesFrom($this->theCart);
        Approvals::verifyString($this->getPrintReceipt());
    }

    public function testFiveForYDiscount(): void
    {
        $this->theCart->addItemQuantity($this->apples, 5);
        $this->teller->addSpecialOffer(SpecialOfferType::FIVE_FOR_AMOUNT, $this->apples, 6.99);
        /** @var Receipt $receipt */
        $this->receipt = $this->teller->checksOutArticlesFrom($this->theCart);
        Approvals::verifyString($this->getPrintReceipt());
    }

    public function testFiveForYDiscountWithSix(): void
    {
        $this->theCart->addItemQuantity($this->apples, 6);
        $this->teller->addSpecialOffer(SpecialOfferType::FIVE_FOR_AMOUNT, $this->apples, 5.99);
        /** @var Receipt $receipt */
        $this->receipt = $this->teller->checksOutArticlesFrom($this->theCart);
        Approvals::verifyString($this->getPrintReceipt());
    }

    public function testFiveForYDiscountWithSixteen(): void
    {
        $this->theCart->addItemQuantity($this->apples, 16);
        $this->teller->addSpecialOffer(SpecialOfferType::FIVE_FOR_AMOUNT, $this->apples, 7.99);
        /** @var Receipt $receipt */
        $this->receipt = $this->teller->checksOutArticlesFrom($this->theCart);
        Approvals::verifyString($this->getPrintReceipt());
    }

    public function testFiveForYDiscountWithFour(): void
    {
        $this->theCart->addItemQuantity($this->apples, 4);
        $this->teller->addSpecialOffer(SpecialOfferType::FIVE_FOR_AMOUNT, $this->apples, 8.99);
        /** @var Receipt $receipt */
        $this->receipt = $this->teller->checksOutArticlesFrom($this->theCart);
        Approvals::verifyString($this->getPrintReceipt());
    }

    public function testTenPercentDiscount(): void
    {
        $this->teller->addSpecialOffer(SpecialOfferType::TEN_PERCENT_DISCOUNT, $this->toothbrush, 10.0);
        $this->theCart->addItemQuantity($this->apples, 2.5);

        // ACT
        $receipt = $this->teller->checksOutArticlesFrom($this->theCart);

        // ASSERT
        $this->assertSame(4.975, $receipt->getTotalPrice());
        $this->assertSame([], $receipt->getDiscounts());
        $this->assertSame(1, count($receipt->getItems()));

        /** @var ReceiptItem $receiptItem */
        $receiptItem = $receipt->getItems()[0];
        $this->assertSame('apples', $receiptItem->getProduct()->getName());
        $this->assertSame(1.99, $receiptItem->getPrice());
        $this->assertSame(2.5 * 1.99, $receiptItem->getTotalPrice());
        $this->assertSame(2.5, $receiptItem->getQuantity());
    }

    private function getPrintReceipt(): string
    {
        return (new ReceiptPrinter())->printReceipt($this->receipt);
    }
}
