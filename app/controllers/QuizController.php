<?php

namespace app\controllers;

use app\components\helpers\QuestionsSetter;
use app\models\Question;
use app\models\QuestionGroup;
use app\models\SiteUser;
use app\models\UserAnswer;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\Response;

/**
 * Class QuizController
 * @package app\controllers
 */
class QuizController extends Controller
{
    /**
     * @return array
     * @throws \Throwable
     */
    public function actionAgreement()
    {
        $errorResponse = ['status' => 'error', 'message' => 'Щось пішло не так..'];

        try {
            \Yii::$app->response->format = Response::FORMAT_JSON;

            if (!\Yii::$app->request->isPost || \Yii::$app->siteUser->isGuest) {
                throw new BadRequestHttpException();
            }

            $request = \Yii::$app->request;
            $token = $request->post(\Yii::$app->request->csrfParam, '');

            if (!\Yii::$app->request->validateCsrfToken($token)) {
                throw new BadRequestHttpException();
            }

            $userId = \Yii::$app->siteUser->identity->id;
            $user = SiteUser::findOne($userId);

            if ($user) {
                if (!$user->answers || count($user->answers) !== 6) {
                    UserAnswer::deleteAll(['user_id' => $userId]);
                    QuestionsSetter::setUserQuestions();
                }

                if (!$user->agreement_read) {
                    $user->agreement_read = SiteUser::AGREEMENT_READ;
                    if (!$user->update()) {
                        return $errorResponse;
                    }
                }
            }

            return ['status' => 'success', 'profileUrl' => '/profile'];
        } catch (BadRequestHttpException $exception) {
            return $errorResponse;
        } catch (\Exception $exception) {
            return $errorResponse;
        }
    }

    /**
     * @return array
     * @throws \Throwable
     */
    public function actionStartBlock()
    {
        $errorResponse = ['status' => 'error', 'message' => 'Щось пішло не так..'];

        try {
            \Yii::$app->response->format = Response::FORMAT_JSON;

            if (!\Yii::$app->request->isPost || \Yii::$app->siteUser->isGuest) {
                throw new BadRequestHttpException();
            }

            $request = \Yii::$app->request;
            $token = $request->post(\Yii::$app->request->csrfParam, '');

            if (!\Yii::$app->request->validateCsrfToken($token)) {
                throw new BadRequestHttpException();
            }

            $userId = \Yii::$app->siteUser->identity->id;
            $user = SiteUser::findOne($userId);

            if ($user) {
                $hash = \Yii::$app->request->post('hash');
                $group = QuestionGroup::findOne(['hash' => $hash]);

                if ($group) {
                    /** @var UserAnswer[] $userAnswers */
                    $userAnswers = UserAnswer::find()
                        ->alias('qa')
                        ->innerJoin(Question::tableName() . ' q', 'qa.question_id = q.id')
                        ->where([
                            'qa.user_id' => \Yii::$app->siteUser->id,
                            'qa.answer_id' => null,
                            'q.group_id' => $group->id,
                        ])
                        ->all();

                    $alreadyStarted = false;

                    foreach ($userAnswers as $answer) {
                        if ($answer->started_at === null) {
                            $answer->started_at = date('Y-m-d H:i:s');
                        } elseif ($answer->started_at !== null &&
                            ($answer->answered_at === null || $answer->answer_id === null) &&
                            strtotime($answer->started_at) + Question::TIME_FOR_ANSWER < time()) {
                            $answer->answer_id = -1;
                        }
                        elseif ($answer->started_at !== null && $answer->answered_at === null) {
                            $alreadyStarted = true;
                        }

                        $answer->update();
                    }

                    return [
                        'status' => !$alreadyStarted ? 'success' : 'warning',
                        'message' => !$alreadyStarted ? 'Відлік часу розпочато!' : 'Ти втрачаєш час, таймер не чекатиме',
                        'answerBlockUrl' => '/answer/' . $hash,
                    ];
                }

                return ['status' => 'error', 'message' => 'Блок питань не знайдено'];
            }

            return ['status' => 'error', 'message' => 'Користувача не знайдено'];
        } catch (BadRequestHttpException $exception) {
            return $errorResponse;
        } catch (\Exception $exception) {
            return $errorResponse;
        }
    }

    /**
     * @return array
     * @throws \Throwable
     */
    public function actionAnswerCheck()
    {
        $errorResponse = ['status' => 'error', 'message' => 'Щось пішло не так..'];

        try {
            \Yii::$app->response->format = Response::FORMAT_JSON;

            if (!\Yii::$app->request->isPost || \Yii::$app->siteUser->isGuest) {
                throw new BadRequestHttpException();
            }

            $request = \Yii::$app->request;
            $token = $request->post(\Yii::$app->request->csrfParam, '');

            if (!\Yii::$app->request->validateCsrfToken($token)) {
                throw new BadRequestHttpException();
            }

            $userId = \Yii::$app->siteUser->identity->id;
            $questionId = \Yii::$app->request->post('questionId');
            $answerId = (int)\Yii::$app->request->post('answerId');
            $groupId = \Yii::$app->request->post('groupId');

            if (!is_int($answerId)) {
                return [
                    'status' => 'error',
                    'message' => 'Ти звичайно крутий програміст, але відповідь має бути одна',
                ];
            }

            /** @var UserAnswer $userAnswer */
            $userAnswer = UserAnswer::find()
                ->where([
                    'user_id' => $userId,
                    'question_id' => $questionId,
                    'answer_id' => null
                ])
                ->one();

            $currentTime = time();
            $timeEnded = false;

            if ($userAnswer) {
                if ($currentTime - strtotime($userAnswer->started_at) <= Question::TIME_FOR_ANSWER) {
                    $userAnswer->answer_id = $answerId;
                    $userAnswer->answered_at = date('Y-m-d H:i:s');
                } else {
                    $userAnswer->answer_id = -1;
                    $timeEnded = true;
                }

                if ($userAnswer->update()) {
                    if (!$timeEnded && $userAnswer->answer->is_correct) {
                        $user = SiteUser::findOne(\Yii::$app->siteUser->identity->id);

                        if ($user) {
                            $user->total_smart += $userAnswer->question->reward;

                            if (!$user->update()) {
                                return ['status' => 'error', 'message' => 'Смарти не зараховано'];
                            }
                        } else {
                            return ['status' => 'error', 'message' => 'Користувача не знайдено'];
                        }
                    }

                    /** @var Question $blockQuestion */
                    $blockQuestion = Question::findNextQuestion($groupId);

                    if ($blockQuestion) {
                        if (!$timeEnded) {
                            return [
                                'status' => 'success',
                                'message' => 'Відповідь зараховано! Наступне питання вже перед тобою',
                                'newQuestion' => $this->renderAjax('/_blocks/question-body',
                                    ['blockQuestion' => $blockQuestion]),
                            ];
                        }

                        /** @var UserAnswer[] $otherBlockAnswers */
                        $otherBlockAnswers = UserAnswer::find()->where([
                            'question_id' => $blockQuestion->id,
                            'user_id' => \Yii::$app->siteUser->identity->id,
                        ])
                            ->all();

                        foreach ($otherBlockAnswers as $failedAnswer) {
                            $failedAnswer->answer_id = -1;
                            $failedAnswer->update();
                        }
                    }

                    if ($timeEnded) {
                        return [
                            'status' => 'error',
                            'message' => 'Нажаль ти не встиг за відведений час..',
                            'blockFinishedUrl' => '/profile',
                        ];
                    }

                    return [
                        'status' => 'success',
                        'message' => 'Молодець! Відповіді зараховано вчасно',
                        'blockFinishedUrl' => '/block-finished/' . QuestionGroup::findOne($groupId)->hash,
                    ];
                }

                return ['status' => 'error', 'message' => 'Відповідь не опрацьовано'];
            }

            return ['status' => 'error', 'message' => 'Питання не знайдено'];
        } catch (BadRequestHttpException $exception) {
            return $errorResponse;
        } catch (\Exception $exception) {
            return $errorResponse;
        }
    }
}
