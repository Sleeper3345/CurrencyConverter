<?php

namespace common\services;

use common\models\Currency;
use Yii;

class CurrencyService
{
    public function __construct(private readonly CurrencyCacheService $cacheService)
    {
    }

    /**
     * @throws \yii\db\Exception
     */
    public function save(): void
    {
        $codes = $this->cacheService->getAll();

        $transaction = Yii::$app->db->beginTransaction();

        Currency::deleteAll(['not in', 'code', $codes]);
        $this->addCurrencies($codes);

        $transaction->commit();
    }

    /**
     * @param array $codes
     * @throws \yii\db\Exception
     */
    private function addCurrencies(array $codes): void
    {
        $existCodes = Currency::find()
            ->select(['code'])
            ->column();

        $existCodes = array_diff($codes, $existCodes);

        array_walk($existCodes, static function (string &$elem) {
            $elem = [$elem];
        });

        Yii::$app->db->createCommand()
            ->batchInsert(
                Currency::tableName(),
                ['code'],
                $existCodes
            )
            ->execute();
    }
}
