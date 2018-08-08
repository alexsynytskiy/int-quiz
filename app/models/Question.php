<?php

namespace app\models;

use app\components\AppMsg;
use app\models\definitions\DefSiteUser;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "site_user".
 *
 * @property integer $id
 * @property string $name
 * @property string $surname
 * @property string $nickname
 * @property string $password
 * @property integer $login_count
 * @property integer $total_smart
 * @property string $status
 * @property string $auth_key
 * @property string $created_at
 * @property string $updated_at
 *
 */
class Question extends \yii\db\ActiveRecord
{
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
            [['created_at', 'updated_at', 'passwordRepeat', 'userPassword'], 'safe'],
            [['password'], 'string', 'min' => 4, 'max' => 60],
            [['userPassword'], 'string', 'min' => 4, 'max' => 60],
            [['name'], 'string', 'max' => 255],
            ['passwordRepeat', 'compare', 'compareAttribute' => 'userPassword'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => AppMsg::t('Имя'),
            'surname' => AppMsg::t('Фамилия'),
            'nickname' => AppMsg::t('Ник'),
            'password' => AppMsg::t('Пароль'),
            'created_at' => AppMsg::t('Создан'),
            'updated_at' => AppMsg::t('Обновлен'),
            'login_count' => AppMsg::t('Количество авторизаций'),
            'status' => AppMsg::t('Статус'),
            'total_smart' => AppMsg::t('Всего очков'),
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
}
