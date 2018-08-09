<?php

namespace app\controllers;

use app\components\helpers\QuestionsSetter;
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
                    if ($user->update()) {
                        return $errorResponse;
                    }
                }
            }

            return ['status' => 'success', 'message' => 'Дякуємо за погодження!'];
        } catch (BadRequestHttpException $exception) {
            return $errorResponse;
        } catch (\Exception $exception) {
            return $errorResponse;
        }
    }
}
