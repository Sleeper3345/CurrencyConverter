<?php

namespace common\models;

use common\models\queries\CurrencyRateQuery;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * @property int $currency_from_id Исходная валюта
 * @property int $currency_to_id Целевая валюта
 * @property string $rate Курс
 * @property string $last_updated_at Актуальная дата курса
 *
 * @property-read Currency $currencyFrom
 * @property-read Currency $currencyTo
 */
class CurrencyRate extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%currency_rate}}';
    }

    /**
     * @return CurrencyRateQuery
     */
    public static function find(): CurrencyRateQuery
    {
        return new CurrencyRateQuery(static::class);
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['currency_from_id', 'currency_to_id', 'rate', 'last_updated_at'], 'required'],
            [['currency_from_id', 'currency_to_id'], 'integer'],
            [['rate'], 'number'],
            [['last_updated_at'], 'safe'],
            [['currency_from_id', 'currency_to_id'], 'unique', 'targetAttribute' => ['currency_from_id', 'currency_to_id']],
            [
                ['currency_from_id'],
                'exist',
                'skipOnError' => false,
                'targetClass' => Currency::class,
                'targetAttribute' => ['currency_from_id' => 'id'],
            ],
            [
                ['currency_to_id'],
                'exist',
                'skipOnError' => false,
                'targetClass' => Currency::class,
                'targetAttribute' => ['currency_to_id' => 'id'],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'currency_from_id' => 'Исходная валюта',
            'currency_to_id' => 'Целевая валюта',
            'rate' => 'Курс',
            'last_updated_at' => 'Актуальная дата курса',
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getCurrencyFrom(): ActiveQuery
    {
        return $this->hasOne(Currency::class, ['id' => 'currency_from_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getCurrencyTo(): ActiveQuery
    {
        return $this->hasOne(Currency::class, ['id' => 'currency_to_id']);
    }
}
