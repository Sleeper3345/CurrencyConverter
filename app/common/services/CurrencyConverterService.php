<?php

declare(strict_types=1);

namespace common\services;

use common\repositories\CurrencyRateRepository;

class CurrencyConverterService
{
    public function __construct(private readonly CurrencyRateRepository $repository)
    {
    }

    /**
     * @param string $currencyFrom
     * @param string $currencyTo
     * @param float $amount
     * @return array
     */
    public function convert(string $currencyFrom, string $currencyTo, float $amount): array
    {
        $rate = $this->repository->getRate($currencyFrom, $currencyTo);

        if (!$rate) {
            return [];
        }

        return [
            'rate' => (float)$rate->rate * $amount,
            'last_updated_at' => $rate->last_updated_at,
        ];
    }
}
