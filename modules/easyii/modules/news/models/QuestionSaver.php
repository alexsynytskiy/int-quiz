<?php

namespace yii\easyii\modules\news\models;

use app\models\Question;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * Class QuestionSaver
 * @package yii\easyii\modules\news\models
 */
class QuestionSaver extends Question
{
    public $answerOneText = '';
    public $answerTwoText = '';
    public $answerThreeText = '';
    public $answerFourText = '';

    public $answerOneCorrect = false;
    public $answerTwoCorrect = false;
    public $answerThreeCorrect = false;
    public $answerFourCorrect = false;

    /**
     * @return array
     */
    public function behaviors()
    {
        return parent::behaviors();
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
            [['answerOneText', 'answerTwoText', 'answerThreeText', 'answerFourText',
                'answerOneCorrect', 'answerTwoCorrect', 'answerThreeCorrect', 'answerFourCorrect'], 'safe'],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'answerOneText' => 'Відповідь 1',
            'answerTwoText' => 'Відповідь 2',
            'answerThreeText' => 'Відповідь 3',
            'answerFourText' => 'Відповідь 4',
            'answerOneCorrect' => 'Відповідь 1 правильна',
            'answerTwoCorrect' => 'Відповідь 2 правильна',
            'answerThreeCorrect' => 'Відповідь 3 правильна',
            'answerFourCorrect' => 'Відповідь 4 правильна',
        ]);
    }
}