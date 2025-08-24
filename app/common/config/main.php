<?php

use common\components\FreeCurrencyComponent;
use yii\caching\CacheInterface;
use yii\di\Instance;

return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => false,
            'rules' => [
                '' => 'site/index',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
            ],
        ],
        'cache' => [
            'class' => \yii\redis\Cache::class,
            'redis' => 'redis',
        ],
        'queue' => [
            'class' => \yii\queue\redis\Queue::class,
            'redis' => 'redis',
            'channel' => 'queue',
        ],
    ],
    'container' => [
        'definitions' => [
            FreeCurrencyComponent::class => [
                'url' => $_ENV['freecurrency_url'],
                'apiKey' => $_ENV['freecurrency_api_key'],
            ],
        ],
    ],
];
