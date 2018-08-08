<?php

namespace app\models\forms;

use app\components\BaseDefinition;
use app\components\DataException;
use app\models\definitions\DefSiteUser;
use app\models\SiteUser;
use Yii;
use yii\base\Model;

/**
 * Login form
 */
class LoginForm extends Model
{
    /**
     * @var string
     */
    public $nickname;
    /**
     * @var string
     */
    public $password;
    /**
     * @var SiteUser
     */
    private $_user;
    /**
     * @var string
     */
    public $captchaUser;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nickname', 'password', 'captchaUser'], 'required'],
            ['captchaUser', 'captcha', 'captchaAction' => '/site/captcha'],
            ['password', 'validatePassword'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'nickname' => 'Логін/Нік',
            'password' => 'Пароль',
            'captchaUser' => 'Капча',
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();

            $errorMsg = 'Ник или пароль введены неверно.';

            try {
                if (!$user) {
                    throw new DataException($errorMsg);
                }

                if (empty($user->password)) {
                    throw new DataException($errorMsg);
                }

                if (!$user->validatePassword($this->password)) {
                    throw new DataException($errorMsg);
                }
            } catch (DataException $e) {
                $this->addError($attribute, $e->getMessage());
            }
        }
    }

    /**
     * @param $attribute
     * @param $params
     */
    public function checkStatus($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();

            $errorMsg = 'Вход на сайт запрещен.';

            try {
                if (!$user) {
                    throw new DataException($errorMsg);
                }

                if ($user->status === DefSiteUser::STATUS_BLOCKED) {
                    throw new DataException($errorMsg);
                }
            } catch (DataException $e) {
                $this->addError($attribute, $e->getMessage());
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->siteUser->login($this->getUser(), BaseDefinition::getSessionExpiredTime());
        }

        return false;
    }

    /**
     * Finds user by [[nickname]]
     *
     * @return SiteUser|null
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = SiteUser::findIdentityByNick($this->nickname);
        }

        return $this->_user;
    }
}