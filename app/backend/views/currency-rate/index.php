<?php

use common\helpers\NumberFormatterHelper;
use common\models\CurrencyRate;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\web\View;

/** @var View $this */
/** @var ActiveDataProvider $dataProvider */

$this->title = 'Курсы валют';
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="currency-rate-index">
    <h1><?= $this->title ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'currency_from',
                'label' => 'Исходная валюта',
                'content' => static fn(CurrencyRate $model) => $model->currencyFrom->code,
            ],
            [
                'attribute' => 'currency_to',
                'label' => 'Целевая валюта',
                'content' => static fn(CurrencyRate $model) => $model->currencyTo->code,
            ],
            [
                'attribute' => 'rate',
                'content' => static fn(CurrencyRate $model) => NumberFormatterHelper::getFormattedNumber($model->rate),
            ],
            'last_updated_at',
        ],
    ]); ?>
</div>
