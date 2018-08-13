<?php

namespace app\components\helpers;

use app\models\Question;
use app\models\QuestionGroup;
use app\models\UserAnswer;

/**
 * Class StartBlock
 * @package app\components\helpers
 */
class StartBlock
{
    /**
     * @param string $hash
     * @return array
     * @throws \Exception
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public static function startBlockLogic($hash) {
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
}