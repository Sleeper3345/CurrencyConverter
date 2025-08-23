<?php

declare(strict_types=1);

namespace common\services;

use Yii;
use yii\caching\CacheInterface;

class CurrencyCacheService
{
    private const KEY_ALL = 'currency:all';

    private readonly CacheInterface $cache;

    public function __construct()
    {
        $this->cache = Yii::$app->cache;
    }

    /**
     * @param array<int, string> $currencies
     * @param int $ttl
     */
    public function saveAll(array $currencies, int $ttl = 36000): void
    {
        $this->cache->set(self::KEY_ALL, $currencies, $ttl);
    }

    /**
     * @return array<int, string>
     */
    public function getAll(): array
    {
        return $this->cache->get(self::KEY_ALL) ?? [];
    }

    public function clearAll(): void
    {
        $this->cache->delete(self::KEY_ALL);
    }
}
