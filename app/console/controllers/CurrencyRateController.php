<?php

namespace console\controllers;

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
            $amountFormatted = $this->getFormattedNumber($amount);
            $rateFormatted = $this->getFormattedNumber($rate['rate']);

            $this->stdout(sprintf('По состоянию на %s %s %s = %s %s', $rate['last_updated_at'], $amountFormatted, $currencyFrom, $rateFormatted, $currencyTo));
        } else {
            $this->stderr('Курс не найден!');
        }
    }

    /**
     * @param float $number
     * @return string
     */
    private function getFormattedNumber(float $number): string
    {
        $amountFormatted = number_format($number, 10, '.', '');
        return rtrim(rtrim($amountFormatted, '0'), '.');
    }
}
