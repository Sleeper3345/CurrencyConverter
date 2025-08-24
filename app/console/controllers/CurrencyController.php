<?php

namespace console\controllers;

use common\components\FreeCurrencyComponent;
use common\enums\CurrencyEnum;
use common\jobs\SaveCurrenciesJob;
use common\services\CurrencyCacheService;
use yii\base\Module;
use yii\console\Controller;

class CurrencyController extends Controller
{
    /**
     * CurrencyController constructor.
     * @param string $id
     * @param Module $module
     * @param CurrencyCacheService $service
     * @param FreeCurrencyComponent $freeCurrencyComponent
     * @param array $config
     */
    public function __construct(string $id, Module $module, private readonly CurrencyCacheService $service, private readonly FreeCurrencyComponent $freeCurrencyComponent, array $config = [])
    {
        parent::__construct($id, $module, $config);
    }

    public function actionUploadRates(): void
    {
        $this->service->saveAll(CurrencyEnum::CURRENCIES);
        SaveCurrenciesJob::push();
    }
}
