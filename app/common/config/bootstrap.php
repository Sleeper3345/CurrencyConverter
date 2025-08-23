<?php

Yii::setAlias('@common', dirname(__DIR__));
Yii::setAlias('@frontend', dirname(dirname(__DIR__)) . '/frontend');
Yii::setAlias('@backend', dirname(dirname(__DIR__)) . '/backend');
Yii::setAlias('@console', dirname(dirname(__DIR__)) . '/console');

Dotenv\Dotenv::createImmutable(dirname(__DIR__, 2))->safeLoad();
Dotenv\Dotenv::createImmutable(dirname(__DIR__), '/config/serv.env')->safeLoad();