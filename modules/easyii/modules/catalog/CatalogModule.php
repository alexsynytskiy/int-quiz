<?php
namespace yii\easyii\modules\catalog;

class CatalogModule extends \yii\easyii\components\Module
{

    public static $installConfig = [
        'title' => [
            'en' => 'Site Users',
            'ru' => 'Пользователи',
        ],
        'icon' => 'list-alt',
        'order_num' => 100,
    ];
}