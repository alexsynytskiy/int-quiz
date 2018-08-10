<?php

namespace app\models;

use app\components\AppMsg;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "question".
 *
 * @property integer $id
 * @property string $text
 * @property integer $group_id
 * @property integer $reward
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Answer[] $answers
 * @property QuestionGroup $group
 *
 */
class Question extends \yii\db\ActiveRecord
{
    const TIME_FOR_ANSWER = 600;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'question';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at', 'updated_at', 'text', 'group_id', 'reward'], 'safe'],
            [['text'], 'string', 'min' => 1, 'max' => 1028],
            [['group_id', 'reward'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'text' => AppMsg::t('Питання'),
            'reward' => AppMsg::t('Нагорода'),
            'created_at' => AppMsg::t('Створено'),
            'updated_at' => AppMsg::t('Оновлено'),
        ];
    }

    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAnswers()
    {
        return $this->hasMany(Answer::className(), ['question_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGroup()
    {
        return $this->hasOne(QuestionGroup::className(), ['id' => 'group_id']);
    }
}
