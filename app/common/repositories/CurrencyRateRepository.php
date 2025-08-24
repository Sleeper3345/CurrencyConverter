<?php

declare(strict_types=1);

namespace common\repositories;

use common\models\CurrencyRate;

class CurrencyRateRepository
{
    /**
     * @param string $currencyFrom
     * @param string $currencyTo
     * @return CurrencyRate|null
     */
    public function getRate(string $currencyFrom, string $currencyTo): ?CurrencyRate
    {
        return CurrencyRate::find()
            ->select([CurrencyRate::tableName() . '.rate', CurrencyRate::tableName() . '.last_updated_at'])
            ->innerJoinWith(['currencyFrom cf', 'currencyTo ct'])
            ->where(['cf.code' => $currencyFrom])
            ->andWhere(['ct.code' => $currencyTo])
            ->one();
    }
}
