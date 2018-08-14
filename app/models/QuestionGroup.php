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
 * @property UserAnswer[] $userAnswers
 */
class QuestionGroup extends \yii\db\ActiveRecord
{
    const MISSED = 'missed';
    const DISABLED = 'disabled';
    const ACTIVE = 'active';
    const ANSWERED = 'answered';

    const USER_BLOCK_QUESTIONS = 2;

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

    /**
     * @return array|null|\yii\db\ActiveRecord
     */
    public function getUserAnswers()
    {
        return  UserAnswer::find()
            ->alias('qa')
            ->innerJoin(Question::tableName() . ' q', 'q.id = qa.question_id')
            ->innerJoin(self::tableName() . ' g', 'q.group_id = g.id')
            ->where([
                'qa.user_id' => \Yii::$app->siteUser->id,
                'g.id' => $this->id,
            ])
            ->all();
    }

    /**
     * @return array
     */
    public static function getGroups()
    {
        $groups = self::find()->all();

        $result = [];
        /** @var QuestionGroup $group */
        foreach ($groups as $group) {
            $result[$group->id] = $group->name;
        }

        return $result;
    }
}
