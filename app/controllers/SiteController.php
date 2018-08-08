<?php

namespace app\controllers;

use app\components\BaseDefinition;
use app\models\forms\LoginForm;
use app\models\forms\RegisterForm;
use app\models\SiteUser;
use yii\captcha\CaptchaAction;
use yii\easyii\models\Admin;
use yii\helpers\VarDumper;
use yii\web\BadRequestHttpException;
use yii\web\Controller;

/**
 * Class SiteController
 * @package app\controllers
 */
class SiteController extends Controller
{
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => CaptchaAction::className(),
                'height' => 60,
                'maxLength' => 4,
                'minLength' => 4,
            ],
        ];
    }

    /**
     * @return string
     */
    public function actionIndex()
    {
        \Yii::$app->seo->setTitle('Головна');
        \Yii::$app->seo->setDescription('Intellias quiz');
        \Yii::$app->seo->setKeywords('intellias, quiz');

        return $this->render('index', [

        ]);
    }

    public function testDataUser() {
        $params = [
            'name' => '',
            'points' => '',
        ];

        if (!\Yii::$app->siteUser->isGuest) {
            /** @var SiteUser|Admin $user */
            $user = \Yii::$app->siteUser->identity ?: \Yii::$app->user->identity;

            if ($user instanceof SiteUser) {
                $params = [
                    'name' => $user->nickname,
                    'points' => $user->total_smart,
                ];
            } else {
                $params = [
                    'name' => $user->username,
                    'points' => '',
                ];
            }
        }

        return $params;
    }

    /**
     * @return string
     */
    public function actionRules()
    {
        \Yii::$app->seo->setTitle('Rules');
        \Yii::$app->seo->setDescription('Intellias quiz');
        \Yii::$app->seo->setKeywords('intellias, quiz');

        return $this->render('rules', $this->testDataUser());
    }

    /**
     * @return string
     */
    public function actionProfile()
    {
        \Yii::$app->seo->setTitle('Profile');
        \Yii::$app->seo->setDescription('Intellias quiz');
        \Yii::$app->seo->setKeywords('intellias, quiz');

        return $this->render('profile', $this->testDataUser());
    }

    /**
     * @return string|\yii\web\Response
     * @throws \yii\base\Exception
     */
    public function actionRegister()
    {
        if (!\Yii::$app->siteUser->isGuest) {
            return $this->redirect(['/profile']);
        }

        if(!\Yii::$app->mutex->acquire('multiple-registration')) {
            \Yii::info('Пользователь попытался выполнить несколько раз подряд регистрацию');

            throw new BadRequestHttpException();
        }

        $model = new RegisterForm();

        if ($model->load(\Yii::$app->request->post()) && $model->register()) {
            \Yii::$app->siteUser->login($model->getUser(), BaseDefinition::getSessionExpiredTime());

            \Yii::$app->siteUser->identity->updateLoginCount();

            if (\Yii::$app->siteUser->identity->login_count === 1) {
                return $this->redirect('/rules');
            }

            return $this->redirect(['/profile']);
        }

        return $this->render('register', [
            'model' => $model,
        ]);
    }

    /**
     * @return string|\yii\web\Response
     * @throws BadRequestHttpException
     */
    public function actionLogin() {
        if(!\Yii::$app->siteUser->isGuest) {
            return $this->redirect(['/profile']);
        }

        if(!\Yii::$app->mutex->acquire('multiple-login')) {
            \Yii::info('Пользователь попытался выполнить несколько раз подряд вход');

            throw new BadRequestHttpException();
        }

        $model = new LoginForm();

        if($model->load(\Yii::$app->request->post())) {
            if($model->login()) {
                return $this->redirect(['/profile']);
            }
        }

        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * @return \yii\web\Response
     */
    public function actionLogout() {
        \Yii::$app->siteUser->logout();

        return parent::goHome();
    }
}
