<?php
Yii::setAlias('@common', dirname(__DIR__));
Yii::setAlias('@frontend', dirname(dirname(__DIR__)) . '/frontend');
Yii::setAlias('@backend', dirname(dirname(__DIR__)) . '/backend');
Yii::setAlias('@console', dirname(dirname(__DIR__)) . '/console');

/* storage aliases */
Yii::setAlias('@storage', dirname(dirname(__DIR__)) . '/storage');

Yii::setAlias('@storage_link', 'http://storage-print4you.tk');
