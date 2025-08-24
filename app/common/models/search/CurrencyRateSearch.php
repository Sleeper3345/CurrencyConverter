<?php

namespace common\models\search;

use common\models\CurrencyRate;
use yii\data\ActiveDataProvider;

class CurrencyRateSearch
{
    /**
     * @return ActiveDataProvider
     */
    public function search(): ActiveDataProvider
    {
        $query = CurrencyRate::find()
            ->innerJoinWith(['currencyFrom cf', 'currencyTo ct']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'cf.code' => SORT_ASC,
                    'ct.code' => SORT_ASC,
                ],
                'attributes' => [
                    'cf.code',
                    'ct.code',
                ],
            ],
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        $dataProvider->sort->attributes['currency_from'] = [
            'asc' => ['cf.code' => SORT_ASC, 'ct.code' => SORT_ASC],
            'desc' => ['cf.code' => SORT_DESC, 'ct.code' => SORT_ASC],
        ];

        $dataProvider->sort->attributes['currency_to'] = [
            'asc' => ['ct.code' => SORT_ASC, 'cf.code' => SORT_ASC],
            'desc' => ['ct.code' => SORT_DESC, 'cf.code' => SORT_ASC],
        ];

        return $dataProvider;
    }
}
