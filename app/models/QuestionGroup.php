<?php

namespace app\models;

/**
 * This is the model class for table "question_group".
 *
 * @property integer $id
 * @property string $name
 * @property integer $starting_at
 * @property integer $ending_at
 *
 */
class QuestionGroup extends \yii\db\ActiveRecord
{
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
            [['name'], 'string'],
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
