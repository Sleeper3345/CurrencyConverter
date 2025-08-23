<?php

declare(strict_types=1);

namespace common\repositories;

use common\models\Currency;

class CurrencyRepository
{
    /**
     * @param string $code
     * @return int
     */
    public function getCurrencyId(string $code): int
    {
        return Currency::find()
            ->select(['id'])
            ->where(['code' => $code])
            ->scalar();
    }

    /**
     * @param array<string> $codes
     * @return array<string, int>
     */
    public function getCurrencyIds(array $codes): array
    {
        return Currency::find()
            ->select(['id', 'code'])
            ->where(['code' => $codes])
            ->indexBy('code')
            ->column();
    }
}
