<?php

namespace app\models;

use app\components\AppMsg;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "question_answer".
 *
 * @property integer $id
 * @property string $text
 * @property integer $is_correct
 * @property integer $question_id
 * @property string $params
 * @property string $created_at
 *
 * @property Question $question
 *
 */
class Answer extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'question_answer';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at', 'question_id', 'text', 'is_correct'], 'safe'],
            [['text'], 'string', 'min' => 1, 'max' => 512],
            [['question_id', 'is_correct'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'text' => AppMsg::t('Відповідь'),
            'created_at' => AppMsg::t('Створено'),
        ];
    }

    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at'],
                ],
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuestion()
    {
        return $this->hasOne(Question::className(), ['id' => 'question_id']);
    }
}
