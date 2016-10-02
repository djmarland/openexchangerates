<?php
namespace Djmarland\OpenExchangeRates;

use DateTime;
use DateTimeImmutable;
use DateTimeInterface;

class Result
{
    /**
     * @var DateTimeImmutable
     */
    private $date;

    /**
     * @var string
     */
    private $base;

    /**
     * @var array
     */
    private $rates;

    public function __construct(
        DateTimeInterface $date,
        string $base,
        array $rates
    ) {
        if ($date instanceof DateTime) {
            $date = DateTimeImmutable::createFromMutable($date);
        }

        $this->date = $date;
        $this->base = $base;
        $this->rates = $rates;
    }

    public function getDate(): DateTimeInterface
    {
        return $this->date;
    }

    public function getBase(): string
    {
        return $this->base;
    }

    public function getRates(): array
    {
        return $this->rates;
    }

    public function getRate(string $currencyCode)
    {
        return $this->rates[$currencyCode] ?? null;
    }
}