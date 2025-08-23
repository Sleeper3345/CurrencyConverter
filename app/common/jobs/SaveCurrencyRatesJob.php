<?php

declare(strict_types=1);

namespace common\jobs;

use common\components\FreeCurrencyComponent;
use common\services\CurrencyCacheService;
use common\services\CurrencyRateService;
use Yii;
use yii\base\BaseObject;
use yii\queue\JobInterface;
use yii\queue\Queue;

class SaveCurrencyRatesJob extends BaseObject implements JobInterface
{
    private const MAX_ATTEMPTS = 15;
    private const PAUSE_SECONDS = 65;

    public array $currenciesFrom;
    public int $attempts;

    private CurrencyCacheService $cacheService;
    private CurrencyRateService $rateService;
    private FreeCurrencyComponent $freeCurrencyComponent;

    public function init(): void
    {
        $this->cacheService = Yii::$container->get(CurrencyCacheService::class);
        $this->rateService = Yii::$container->get(CurrencyRateService::class);
        $this->freeCurrencyComponent = Yii::$container->get(FreeCurrencyComponent::class);

        parent::init();
    }

    /**
     * @param array $currenciesFrom
     * @param int $attempts
     * @param bool $withDelay
     */
    public static function push(array $currenciesFrom, int $attempts, bool $withDelay = false): void
    {
        $data = [
            'currenciesFrom' => $currenciesFrom,
            'attempts' => $attempts,
        ];

        if ($withDelay) {
            Yii::$app->queue->delay(self::PAUSE_SECONDS)->push(new static($data));
        } else {
            Yii::$app->queue->push(new static($data));
        }
    }

    /**
     * @param Queue $queue
     * @return mixed|void
     * @throws \yii\db\Exception
     */
    public function execute($queue)
    {
        $transaction = Yii::$app->db->beginTransaction();

        foreach ($this->currenciesFrom as $i => $currencyFrom) {
            $rates = $this->freeCurrencyComponent->getClient()->getRates($currencyFrom, $this->cacheService->getAll());

            if (!$rates['data']) {
                $transaction->commit();

                if ($rates['status'] === 429 && $this->attempts < self::MAX_ATTEMPTS) {
                    $remaining = array_slice($this->currenciesFrom, $i);

                    static::push($remaining, $this->attempts + 1, true);
                }

                return;
            }

            $this->rateService->save($rates['data'], $currencyFrom);
        }

        $transaction->commit();
    }
}
