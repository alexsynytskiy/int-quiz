<?php

namespace app\controllers;

use app\components\BaseDefinition;
use app\components\helpers\QuestionsSetter;
use app\components\helpers\StartBlock;
use app\models\forms\LoginForm;
use app\models\forms\RegisterForm;
use app\models\Question;
use app\models\QuestionGroup;
use app\models\SiteUser;
use app\models\UserAnswer;
use yii\captcha\CaptchaAction;
use yii\db\Exception;
use yii\easyii\models\Admin;
use yii\helpers\ArrayHelper;
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
     * @return bool|\yii\web\Response
     */
    private function checkUserStatus()
    {
        $user = \Yii::$app->siteUser;

        if ($user->isGuest) {
            return $this->redirect('/login');
        }

        if (!$user->identity->agreement_read) {
            return $this->redirect('/rules');
        }

        return true;
    }

    /**
     * @return string
     */
    public function actionIndex()
    {
        $this->checkUserStatus();
        return $this->redirect(['/profile']);
    }

    public function testDataUser()
    {
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
    public function actionHelp()
    {
        \Yii::$app->seo->setTitle('Help');
        \Yii::$app->seo->setDescription('Intellias quiz');
        \Yii::$app->seo->setKeywords('intellias, quiz');

        return $this->render(\Yii::$app->siteUser->isGuest ? 'help-guest' : 'help-logged-in');
    }

    /**
     * @return string
     */
    public function actionRules()
    {
        if (\Yii::$app->siteUser->isGuest) {
            return $this->redirect('/login');
        }

        \Yii::$app->seo->setTitle('Rules');
        \Yii::$app->seo->setDescription('Intellias quiz');
        \Yii::$app->seo->setKeywords('intellias, quiz');

        return $this->render('rules');
    }

    /**
     * @return bool|string|\yii\web\Response
     */
    public function actionEventInfo()
    {
        $status = $this->checkUserStatus();

        if ($status !== true) {
            return $status;
        }

        \Yii::$app->seo->setTitle('Event info');
        \Yii::$app->seo->setDescription('Intellias quiz');
        \Yii::$app->seo->setKeywords('intellias, quiz');

        return $this->render('simple-info');
    }

    /**
     * @return bool|string|\yii\web\Response
     */
    public function actionGameRules()
    {
        $status = $this->checkUserStatus();

        if ($status !== true) {
            return $status;
        }

        \Yii::$app->seo->setTitle('Game rules');
        \Yii::$app->seo->setDescription('Intellias quiz');
        \Yii::$app->seo->setKeywords('intellias, quiz');

        return $this->render('simple-rules');
    }

    /**
     * @return string
     */
    public function actionProfile()
    {
        $status = $this->checkUserStatus();

        if ($status !== true) {
            return $status;
        }

        \Yii::$app->seo->setTitle('Profile');
        \Yii::$app->seo->setDescription('Intellias quiz');
        \Yii::$app->seo->setKeywords('intellias, quiz');

        $questionGroups = QuestionGroup::find()->all();
        $currentTime = time();

        $userAnswers = UserAnswer::find()->where(['user_id' => \Yii::$app->siteUser->identity->id])->all();

        /** @var QuestionGroup $group */
        foreach ($questionGroups as $group) {
            $answersCount = 0;
            /** @var UserAnswer $answer */
            foreach ($userAnswers as $answer) {
                if ($answer->question->group_id === $group->id && $answer->answer_id) {
                    $answersCount++;
                }
            }

            if ($answersCount < QuestionGroup::USER_BLOCK_QUESTIONS &&
                $currentTime >= strtotime($group->starting_at) && $currentTime <= strtotime($group->ending_at)) {
                $group->active = QuestionGroup::ACTIVE;
            } elseif (($answersCount === 1 && $currentTime > strtotime($group->ending_at)) ||
                $answersCount === QuestionGroup::USER_BLOCK_QUESTIONS) {
                $group->active = QuestionGroup::ANSWERED;
            } elseif ($answersCount === 0 && $currentTime > strtotime($group->ending_at)) {
                $group->active = QuestionGroup::MISSED;
            }
        }

        $params = ArrayHelper::merge($this->testDataUser(), [
            'questionGroups' => $questionGroups,
        ]);

        return $this->render('profile', $params);
    }

    /**
     * @param string $hash
     *
     * @return bool|string|\yii\web\Response
     *
     * @throws \Exception
     * @throws \Throwable
     */
    public function actionAnswer($hash)
    {
        $status = $this->checkUserStatus();

        if ($status !== true) {
            return $status;
        }

        $group = QuestionGroup::findOne(['hash' => $hash]);

        if ($group) {
            /** @var UserAnswer[] $userAnswers */
            $userAnswers = UserAnswer::find()
                ->alias('qa')
                ->innerJoin(Question::tableName() . ' q', 'qa.question_id = q.id')
                ->where([
                    'qa.user_id' => \Yii::$app->siteUser->id,
                    'qa.answer_id' => null,
                    'qa.started_at' => null,
                    'q.group_id' => $group->id,
                ])
                ->all();

            if ($userAnswers) {
                StartBlock::startBlockLogic($hash);
            }

            $currentTime = time();

            if ($currentTime >= strtotime($group->starting_at) && $currentTime <= strtotime($group->ending_at)) {
                $group->active = QuestionGroup::ACTIVE;
            }

            $blockQuestion = Question::findNextQuestion($group->id);
        } else {
            throw new Exception('Такого блоку питань не існує');
        }

        if (!$blockQuestion) {
            return $this->redirect('/profile');
        }

        return $this->render('answer', ['blockQuestion' => $blockQuestion]);
    }

    /**
     * @param string $hash
     * @return bool|string|\yii\web\Response
     */
    public function actionBlockFinished($hash)
    {
        $status = $this->checkUserStatus();

        if ($status !== true) {
            return $status;
        }

        $params = $this->testDataUser();

        $group = QuestionGroup::findOne(['hash' => $hash]);

        if ($group) {
            $userAnswers = $group->userAnswers;
            $answersCount = 0;
            $wrongAnswers = [];

            foreach ($userAnswers as $answer) {
                if ($answer->question->group_id === $group->id && $answer->answer_id) {
                    $answersCount++;
                }
            }

            if ($answersCount === QuestionGroup::USER_BLOCK_QUESTIONS) {
                foreach ($userAnswers as $userAnswer) {
                    if (!$userAnswer->answer->is_correct) {
                        $wrongAnswers[] = $userAnswer->question->correct_answer;
                    }
                }

                $params = ArrayHelper::merge($params, ['wrongAnswers' => $wrongAnswers, 'group' => $group]);
            } else {
                return $this->redirect('/profile');
            }
        }

        return $this->render('block-finished', $params);
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

        if (!\Yii::$app->mutex->acquire('multiple-registration')) {
            \Yii::info('Пользователь попытался выполнить несколько раз подряд регистрацию');

            throw new BadRequestHttpException();
        }

        $model = new RegisterForm();

        if ($model->load(\Yii::$app->request->post()) && $model->register()) {
            \Yii::$app->siteUser->login($model->getUser(), BaseDefinition::getSessionExpiredTime());

            \Yii::$app->siteUser->identity->updateLoginCount();

            if (\Yii::$app->siteUser->identity->login_count === 1) {
                QuestionsSetter::setUserQuestions();

                if (!\Yii::$app->siteUser->identity->agreement_read) {
                    return $this->redirect('/rules');
                }
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
    public function actionLogin()
    {
        if(!\Yii::$app->user->isGuest) {
            \Yii::$app->user->logout();
        }

        if (!\Yii::$app->siteUser->isGuest && \Yii::$app->siteUser->identity->agreement_read) {
            return $this->redirect(['/profile']);
        }

        if (!\Yii::$app->mutex->acquire('multiple-login')) {
            \Yii::info('Пользователь попытался выполнить несколько раз подряд вход');

            throw new BadRequestHttpException();
        }

        $model = new LoginForm();

        if ($model->load(\Yii::$app->request->post()) && $model->login()) {
            if (\Yii::$app->siteUser->identity->agreement_read) {
                return $this->redirect(['/profile']);
            }

            return $this->redirect('/rules');
        }

        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * @return \yii\web\Response
     */
    public function actionLogout()
    {
        \Yii::$app->siteUser->logout();

        return $this->redirect(['/login']);
    }
}
