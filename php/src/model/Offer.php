<?php

declare(strict_types=1);

namespace Supermarket\model;

class Offer
{
    /**
     * @var SpecialOffer
     */
    public $offerType;

    /**
     * @var float
     */
    public $argument;

    public function __construct(SpecialOffer $offerType, float $argument)
    {
        $this->offerType = $offerType;
        $this->argument = $argument;
    }
}
