<?php

namespace yii\easyii\components;
use yii\base\Object;
use yii\web\View;

/**
 * Class Seo
 * @package yii\easyii\components
 */
class Seo extends Object
{
    /**
     * @var null|\yii\web\View
     */
    protected $view = null;

    /**
     * @param $view
     */
    public function setView(View $view) {
        $this->view = $view;
    }

    /**
     * @param $title
     */
    public function setTitle($title) {
        $this->view->title = \Yii::t('app', 'Intellias 6th Element | ').$title;
    }

    /**
     * @param $description
     */
    public function setDescription($description) {
        $this->view->registerMetaTag([
            'name'    => 'description',
            'content' => $description,
        ]);
    }

    /**
     * @param $keywords
     */
    public function setKeywords($keywords) {
        $this->view->registerMetaTag([
            'name'    => 'keywords',
            'content' => $keywords,
        ]);
    }
}