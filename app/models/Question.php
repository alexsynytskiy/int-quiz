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
 * @property string $correct_answer
 * @property integer $group_id
 * @property integer $image
 * @property integer $reward
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Answer[] $answers
 * @property QuestionGroup $group
 * @property int $emptyQuestionsCount
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
            [['created_at', 'updated_at', 'text', 'group_id', 'reward', 'correct_answer'], 'safe'],
            ['image', 'image'],
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
            'image' => AppMsg::t('Піктограма'),
            'group_id' => AppMsg::t('Група питань'),
            'correct_answer' => AppMsg::t('Вірна відповідь'),
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
     * @param bool $insert
     *
     * @return bool
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if(!$insert && $this->image !== $this->oldAttributes['image'] && $this->oldAttributes['image']){
                @unlink(\Yii::getAlias('@webroot').$this->oldAttributes['image']);
            }
            return true;
        }

        return false;
    }


    public function afterDelete()
    {
        parent::afterDelete();

        if($this->image){
            @unlink(\Yii::getAlias('@webroot').$this->image);
        }
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

    /**
     * @param int $groupId
     *
     * @return array|null|ActiveRecord
     */
    public static function findNextQuestion($groupId)
    {
        return self::find()
            ->alias('q')
            ->innerJoin(UserAnswer::tableName() . ' qa', 'qa.question_id = q.id')
            ->where([
                'qa.user_id' => \Yii::$app->siteUser->id,
                'qa.answer_id' => null,
                'q.group_id' => $groupId,
            ])
            ->limit(1)
            ->one();
    }

    /**
     * @return int|string
     */
    public function getEmptyQuestionsCount()
    {
        return UserAnswer::find()
            ->alias('qa')
            ->innerJoin(Question::tableName() . ' q', 'qa.question_id = q.id')
            ->where([
                'qa.user_id' => \Yii::$app->siteUser->id,
                'qa.answer_id' => null,
                'q.group_id' => $this->group_id,
            ])
            ->count();
    }
}
