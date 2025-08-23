<?php

namespace common\jobs;

use common\services\CurrencyCacheService;
use common\services\CurrencyService;
use Yii;
use yii\base\BaseObject;
use yii\queue\JobInterface;
use yii\queue\Queue;

class SaveCurrenciesJob extends BaseObject implements JobInterface
{
    private const CHUNK = 5;

    private CurrencyService $service;
    private CurrencyCacheService $cacheService;

    public function init(): void
    {
        $this->service = Yii::$container->get(CurrencyService::class);
        $this->cacheService = Yii::$container->get(CurrencyCacheService::class);
        parent::init();
    }

    public static function push(): void
    {
        Yii::$app->queue->push(new static());
    }

    /**
     * @param Queue $queue
     * @return mixed|void
     * @throws \yii\db\Exception
     */
    public function execute($queue)
    {
        $this->service->save();

        $currencies = $this->cacheService->getAll();

        $chunks = array_chunk($currencies, self::CHUNK);

        foreach ($chunks as $chunk) {
            SaveCurrencyRatesJob::push($chunk, 0);
        }
    }
}
