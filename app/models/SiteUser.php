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
 * @property integer $agreement_read
 * @property integer $total_smart
 * @property string $status
 * @property string $auth_key
 * @property string $created_at
 * @property string $updated_at
 *
 * @property IdentityInterface|null|SiteUser $identity The identity object associated with the currently logged-in
 * user. `null` is returned if the user is not logged in (not authenticated).
 * @property string $passwordWithSalt
 * @property Answer[] $answers
 *
 */
class SiteUser extends \yii\db\ActiveRecord implements IdentityInterface
{
    /**
     * Salt uses to hash user ID
     */
    const HASH_ID_SALT = 'UfI6m8gqwriLDLvi9W5G';
    /**
     * Salt uses to hash user Password
     */
    const PASSWORD_SALT = 'aowherw34rywherfghweifhso';

    const AGREEMENT_READ = 1;
    /**
     * @var string
     */
    public $userPassword;
    /**
     * @var string
     */
    public $passwordRepeat;

    /**
     * @return string
     */
    public static function answersTableName() {
        return 'question_answer_user';
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'site_user';
    }

    /**
     * @param bool $insert
     *
     * @return bool
     * @throws \yii\base\Exception
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->userPassword) {
                $this->password = \Yii::$app->security->generatePasswordHash($this->passwordWithSalt);
            }

            if ($insert) {
                $this->status = DefSiteUser::STATUS_ACTIVE;
                $this->total_smart = 0;
            }

            return true;
        }

        return false;
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
            [['agreement_read', 'login_count', 'total_smart'], 'integer'],
            [['name', 'surname'], 'unique', 'targetAttribute' => ['name', 'surname']],
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
            'name' => AppMsg::t("Ім'я"),
            'surname' => AppMsg::t('Прізвище'),
            'nickname' => AppMsg::t('Логін/Нік'),
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

    /**
     * @return string
     */
    public function getPasswordWithSalt()
    {
        return $this->userPassword . self::PASSWORD_SALT;
    }

    /**
     * @param mixed $token
     * @param null $type
     * @return void|IdentityInterface
     * @throws NotSupportedException
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByNick($nickname)
    {
        return static::findOne(['nickname' => $nickname]);
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * @return string
     */
    public function hashId()
    {
        return md5($this->id . self::HASH_ID_SALT);
    }

    /**
     * @param $hash
     *
     * @return bool
     */
    public function validHashId($hash)
    {
        return $this->hashId() === $hash;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     *
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return \Yii::$app->security->validatePassword($password . self::PASSWORD_SALT, $this->password);
    }

    /**
     * @throws \yii\base\Exception
     */
    public function generateAuthKey()
    {
        $this->auth_key = \Yii::$app->security->generateRandomString();
    }

    /**
     * @return void
     */
    public function updateLoginCount()
    {
        $this->updateCounters(['login_count' => 1]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAnswers() {
        return $this->hasMany(Answer::className(), ['id' => 'answer_id'])
            ->viaTable(static::answersTableName(), ['user_id' => 'id']);
    }
}
