<?php

namespace console\controllers;

use common\helpers\NumberFormatterHelper;
use common\services\CurrencyConverterService;
use yii\base\Module;
use yii\console\Controller;

class CurrencyRateController extends Controller
{
    public function __construct(string $id, Module $module, private readonly CurrencyConverterService $service, array $config = [])
    {
        parent::__construct($id, $module, $config);
    }

    /**
     * @param string $currencyFrom
     * @param string $currencyTo
     * @param float $amount
     */
    public function actionConvert(string $currencyFrom, string $currencyTo, float $amount): void
    {
        $rate = $this->service->convert($currencyFrom, $currencyTo, $amount);

        if ($rate) {
            $amountFormatted = NumberFormatterHelper::getFormattedNumber($amount);
            $rateFormatted = NumberFormatterHelper::getFormattedNumber($rate['rate']);

            $this->stdout(sprintf('По состоянию на %s %s %s = %s %s', $rate['last_updated_at'], $amountFormatted, $currencyFrom, $rateFormatted, $currencyTo));
        } else {
            $this->stderr('Курс не найден!');
        }
    }
}
