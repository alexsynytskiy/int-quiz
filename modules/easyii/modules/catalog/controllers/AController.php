<?php

namespace yii\easyii\modules\catalog\controllers;

use app\models\SiteUser;
use Yii;
use yii\data\ActiveDataProvider;
use yii\easyii\components\Controller;

class AController extends Controller
{
    public function actionIndex()
    {
        $data = new ActiveDataProvider([
            'query' => SiteUser::find()->orderBy('created_at')
        ]);

        return $this->render('index', [
            'data' => $data,
        ]);
    }
}