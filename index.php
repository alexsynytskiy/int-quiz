<?php
// comment out the following two lines when deployed to production
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

require(__DIR__ . '/vendor/autoload.php');
require(__DIR__ . '/vendor/yiisoft/yii2/Yii.php');

Yii::setAlias('@yii/easyii', __DIR__.'/modules/easyii');

$config = require(__DIR__ . '/app/config/web.php');

$application = new \yii\web\Application($config);

Yii::$app->setComponents([
    'seo' => [
        'class' => \yii\easyii\components\Seo::className(),
        'view'  => Yii::$app->getView(),
    ]
]);

$application->run();