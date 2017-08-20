<?php
namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

use common\models\Orders;

use frontend\components\Sms;

/**
 * User model
 *
 * @property integer $id
 * 
 * @property string $firstname
 * @property string $address
 * @property string $phone
 * @property text $profile_pic
 * @property integer $sum_purchased_retail
 * @property integer $sum_purchased_gross
 * 
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 */
class CommonUser extends ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;
    const CREATE_SCENARIO = 'create';
    const CREATE_BY_ADMIN_SCENARIO = 'create_by_admin';
    const CREATE_FROM_ORDER = 'create_from_order';

    public $password;

    /**
     * Возвращает текущую скидку клиента в %.
     *
     * Высчитывается на основе количества завершенных заказов 
     * и суммы стоимости заказов.
     *
     * Для гостя ($user->identity->isGuest) может быть только скидка 
     * когда он заказывает 10-19 товаров.
     *
     * @var integer $numItems - количество покупаемых товаров
     * @return integer $discountVal - итоговая скидка
     */
    public static function getDiscount($numItems = 0, $user = null)
    {
        $discountVal = 0;

        if (!$user){
            $user = Yii::$app->user;
        }

        // guest
        if ($user->isGuest){
            if ($numItems >= 10 && $numItems <= 19){
                $discountVal = 20;
            }
        }
        // registered user
        else{
            // retail
            if ($numItems < 20){
                // 10-19 items => 20%
                if ($numItems >= 10 && $numItems <= 19){
                    $discountVal = 20;
                } else { // 1-9 items
                    $numOrders = Orders::getClientCompletedOrdersCount($user);
                    if ($numOrders >= 1){
                        $discountVal = 5;
                    }
                    if ($numOrders >= 2){
                        $discountVal = 10;
                    }
                    if ($user->identity->getRetailPurchasedSum() >= 50000){
                        $discountVal = 15;
                    }
                    if ($user->identity->getRetailPurchasedSum() >= 150000){
                        $discountVal = 20;
                    }
                }
                
            } 
            // gross
            else {
                if ($user->identity->getTotalPurchasedSum() >= 50000){
                    $discountVal = 3;
                }
                if ($user->identity->getTotalPurchasedSum() >= 150000){
                    $discountVal = 5;
                }
            }
        }

        return $discountVal;
    }

    /**
     * Возвращает сумму розничных покупок клиента
     * 
     * @return integer $sumRetail
     */
    public function getRetailPurchasedSum()
    {
        $orders = Orders::find()->where(['is_gross' => false, 'order_status' => Orders::STATUS_COMPLETED])->all();
        $sum = 0;
        foreach($orders as $order){
            $sum += Orders::calculateDiscountPrice($order['price'], $order['discount_percent']);
        }
        return $sum;
    }

    /**
     * Возвращает сумму оптовых покупок клиента
     * 
     * @return integer $sumGross
     */
    public function getGrossPurchasedSum()
    {
        $orders = Orders::find()->where(['is_gross' => true, 'order_status' => Orders::STATUS_COMPLETED])->all();
        $sum = 0;
        foreach($orders as $order){
            $sum += Orders::calculateDiscountPrice($order['price'], $order['discount_percent']);
        }
        return $sum;
    }

    /**
     * Возвращает сумму заказов - оптом + розница
     *
     * Используется в методе getCurrentDiscount
     * @return integer $totalPurchasedSum
     */
    public function getTotalPurchasedSum()
    {
        return ($this->getRetailPurchasedSum() 
            + $this->getGrossPurchasedSum());
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%common_user}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['phone', 'auth_key', 'password_hash', 'firstname', 'phone'], 'required'],
            ['email', 'required', 'except' => [self::CREATE_BY_ADMIN_SCENARIO, self::CREATE_FROM_ORDER]],
            [['status', 'created_at', 'updated_at', 'sum_purchased_retail', 'sum_purchased_gross'], 'integer'],
            [['phone', 'firstname', 'address', 'password_hash', 'password_reset_token', 'email'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
            [['email'], 'unique'],
            [['password_reset_token'], 'unique'],
            [['phone'], 'unique'],
            ['phone', 'string'],
            [['password'], 'required', 'on' => self::CREATE_SCENARIO, 'except' => [self::CREATE_BY_ADMIN_SCENARIO, self::CREATE_FROM_ORDER]],
            ['password', 'string', 'min' => 8, 'max' => 16, 'message' => 'Пароль должен составлять от 8 до 16 символов!'],
            ['password', 'match', 'pattern' => '/^[a-zA-Z0-9]+$/', 'message' => 'Пароль должен содержать только латинские символы и/или цифры!'],
            ['phone', 'match', 'pattern' => '/^9[0-9]{9}$/', 'message' => 'Введите номер в формате +7 (9ХХ) ХХХ-ХХ-ХХ'],
        ];
    }

    /**
     * Labels
     */
    public function attributeLabels()
    {
        return [
            'password' => 'Пароль',
            'firstname' => 'Имя',
            'email' => 'Email',
            'phone' => 'Номер телефона',
            'address' => 'Адрес (город, улица, дом)',
            'sum_purchased_retail' => 'Сумма покупок в розницу',
            'sum_purchased_gross' => 'Сумма покупок оптом',
        ];
    }

    /**
     * Перформировывает номер телефона
     *
     * @return string
     */

    public function calculatePhone()
    {   
        // обрежем все ненужные символы
        $phone = preg_replace('/[^0-9]/', '', $this->phone);

        if (strlen($phone) < 11) return false;

        // вернем номер без 7
        $this->phone = substr($phone, 1);
        
        return true;
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * @inheritd
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    public static function findByUsername()
    {
        return null;
    }

    /**
     * Finds user by phone number
     *
     * @param string $phone
     * @return static|null
     */
    public static function findByPhone($phone)
    {
        return static::findOne(['phone' => $phone, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
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
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash and assigns it to a variable
     *
     * @param string $password
     */
    public function generatePasswordHash($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    /**
     * Проверяет, можно ли использовать email
     */

    public static function checkEmail($email) 
    {
        if (!Yii::$app->user->isGuest) {
            $user_email = Yii::$app->user->identity->email;
            if ($user_email == $email) return true;
        }

        $exists = self::find()->where(['email' => $email])->exists();
        return !$exists;
    }

    /**
     * Проверяет, можно ли использовать телефон
     */

    public static function checkPhone($phone)
    {
        if (!Yii::$app->user->isGuest) {
            $user_phone = Yii::$app->user->identity->phone;
            if ($user_phone == $phone) return true;
        }

        $exists = self::find()->where(['phone' => $phone])->exists();
        return !$exists;
    }

    /**
     * Изменяет данные в редакторе кабинета
     */
    public static function ajaxChangeUserData()
    {
        if (Yii::$app->user->isGuest) return false;
        $user = self::findIdentity(['id' => Yii::$app->user->identity->id]);
        $user->email = Yii::$app->request->post('email');
        $user->phone = Yii::$app->request->post('phone');
        $user->firstname = Yii::$app->request->post('firstname');
        $user->address = Yii::$app->request->post('address');

        if ($user->save()) {
           Yii::$app->user->identity->email = $user->email; 
           Yii::$app->user->identity->phone = $user->phone; 
           Yii::$app->user->identity->firstname = $user->firstname; 
           Yii::$app->user->identity->address = $user->address; 
           return true;
        }

        return false;
    }

    public function successSms($password) 
    {
        $message = "Здравствуйте, " . $this->firstname . "!\r\n";
        $message .= "Ваш логин и пароль для входа на сайт print4you.su: \r\n";
        $message .= '+7' . $this->phone . "\r\n" . $password;

        Sms::message($this->phone, $message);
    }
}
