<?php

declare(strict_types=1);

namespace common\services;

use common\models\CurrencyRate;
use common\repositories\CurrencyRepository;
use Yii;
use yii\db\Expression;

class CurrencyRateService
{
    public function __construct(private readonly CurrencyRepository $repository)
    {
    }

    /**
     * @param array<string, float> $rates
     * @param string $currencyFrom
     * @throws \yii\db\Exception
     */
    public function save(array $rates, string $currencyFrom): void
    {
        unset($rates[$currencyFrom]);

        $currencyFromId = $this->repository->getCurrencyId($currencyFrom);

        CurrencyRate::deleteAll([
            'currency_from_id' => $currencyFromId,
        ]);

        $idsByCode = $this->repository->getCurrencyIds(array_keys($rates));

        $this->insertData($idsByCode, $rates, $currencyFromId);
    }

    /**
     * @param array<string, int> $idsByCode
     * @param array<string, float> $rates
     * @param int $currencyFromId
     * @throws \yii\db\Exception
     */
    private function insertData(array $idsByCode, array $rates, int $currencyFromId): void
    {
        $now = new Expression('NOW()');
        $data = [];

        foreach ($rates as $toCode => $rate) {
            if (!isset($idsByCode[$toCode])) {
                continue;
            }

            $data[] = [$currencyFromId, $idsByCode[$toCode], $rate, $now];
        }

        Yii::$app->db->createCommand()
            ->batchInsert(
                CurrencyRate::tableName(),
                ['currency_from_id', 'currency_to_id', 'rate', 'last_updated_at'],
                $data
            )
            ->execute();
    }
}
