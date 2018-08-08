<?php

namespace app\models\forms;

use app\models\SiteUser;
use Yii;
use yii\base\Model;

/**
 * Register form
 */
class RegisterForm extends Model
{
    /**
     * @var string
     */
    public $name;
    /**
     * @var string
     */
    public $surname;
    /**
     * @var string
     */
    public $nickname;
    /**
     * @var string
     */
    public $userPassword;
    /**
     * @var string
     */
    public $passwordRepeat;
    /**
     * @var string
     */
    public $captchaUser;
    /**
     * @var SiteUser
     */
    private $_user;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'surname', 'nickname', 'passwordRepeat', 'userPassword', 'captchaUser'], 'required'],
            ['captchaUser', 'captcha', 'captchaAction' => '/site/captcha'],
            ['userPassword', 'string', 'min' => 6],
            ['passwordRepeat', 'compare', 'compareAttribute' => 'userPassword'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => "Ім'я",
            'surname' => 'Прізвище',
            'nickname' => 'Логін/Нік',
            'userPassword' => 'Пароль',
            'passwordRepeat' => 'Пароль ще раз',
            'captchaUser' => 'Капча',
        ];
    }

    /**
     * @return SiteUser
     */
    public function getUser()
    {
        return $this->_user;
    }

    /**
     * @return bool
     * @throws \yii\base\Exception
     * @throws \yii\db\Exception
     */
    public function register()
    {
        $user = new SiteUser();
        $user->name = $this->name;
        $user->surname = $this->surname;
        $user->nickname = $this->nickname;
        $user->userPassword = $this->userPassword;
        $user->passwordRepeat = $this->passwordRepeat;

        $user->generateAuthKey();

        $this->_user = $user;

        if ($this->validate() && $user->validate()) {
            $transaction = Yii::$app->db->beginTransaction();

            try {
                $user->save(false);

                $transaction->commit();
            } catch (\Throwable $e) {
                $transaction->rollBack();
            }

            return true;
        }

        $this->addErrors($this->_user->getErrors());

        return false;
    }
}