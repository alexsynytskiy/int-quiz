<?php
namespace yii\easyii\modules\news;

class NewsModule extends \yii\easyii\components\Module
{
    public $settings = [
        'enableThumb' => true,
        'enablePhotos' => true,
        'enableShort' => true,
        'shortMaxLength' => 256,
        'enableTags' => true
    ];

    public static $installConfig = [
        'title' => [
            'en' => 'Questions',
            'ru' => 'Вопросы',
        ],
        'icon' => 'bullhorn',
        'order_num' => 70,
    ];
}