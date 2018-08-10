<?php

namespace app\models;

/**
 * This is the model class for table "question_group".
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property string $hash
 * @property string $starting_at
 * @property string $ending_at
 *
 * @property Question[] $questions
 */
class QuestionGroup extends \yii\db\ActiveRecord
{
    const DISABLED = 'disabled';
    const ACTIVE = 'active';
    const ANSWERED = 'answered';

    public $active = self::DISABLED;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'question_group';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['starting_at', 'ending_at', 'name'], 'safe'],
            [['name', 'description', 'hash'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Назва',
            'description' => 'Опис',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuestions()
    {
        return $this->hasMany(Question::className(), ['group_id' => 'id']);
    }
}
