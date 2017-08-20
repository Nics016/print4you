<?php
namespace frontend\models;

use Yii;
use yii\base\Model;
use common\models\CommonUser;

/**
 * Login form
 */
class LoginForm extends Model
{
    public $phone;
    public $password;
    public $rememberMe = true;

    private $_user;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // phone and password are both required
            [['phone', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            ['phone', 'string'],
            ['phone', 'match', 'pattern' => '/^\+7\s*\(9[0-9]{2}\)\s*[0-9]{3}\-[0-9]{2}\-[0-9]{2}$/', 'message' => 'Введите номер в формате +7 (9ХХ) ХХХ-ХХ-ХХ'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'phone' => 'Телефон',
            'password' => 'Пароль',
            'rememberMe' => 'Запомнить',
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
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Incorrect username or password.');
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
        if ($this->validate())
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        else 
            return false;
    }


    /**
     * Перформировывает номер телефона
     *
     * @return string
     */

    private function calculatePhone()
    {   
        // обрежем все ненужные символы
        $phone = preg_replace('/[^0-9]/', '', $this->phone);

        if (strlen($phone) < 11) return false;

        // вернем номер без 7
        return substr($phone, 1);
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            $phone = $this->calculatePhone();
            if ($phone == false) return false;
            $this->_user = CommonUser::findByPhone($phone);
        }

        return $this->_user;
    }
}
