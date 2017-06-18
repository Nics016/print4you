<?php
namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

use common\models\Orders;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * 
 * @property string $firstname
 * @property string $secondname
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
        return $this->sum_purchased_retail;
    }

    /**
     * Возвращает сумму оптовых покупок клиента
     * 
     * @return integer $sumGross
     */
    public function getGrossPurchasedSum()
    {
        return $this->sum_purchased_gross;
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
            [['username', 'auth_key', 'password_hash', 'email', 'firstname', 'phone'], 'required'],
            [['status', 'created_at', 'updated_at', 'sum_purchased_retail', 'sum_purchased_gross'], 'integer'],
            [['username', 'firstname', 'secondname', 'address', 'password', 'password_hash', 'password_reset_token', 'email'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
            [['email'], 'unique'],
            [['password_reset_token'], 'unique'],
            [['username'], 'unique'],
            [['password'], 'required', 'on' => self::CREATE_SCENARIO],
            ['phone', 'match', 'pattern' => '/9\d{9}/'],
        ];
    }

    /**
     * Labels
     */
    public function attributeLabels()
    {
        return [
            'username' => 'Имя пользователя',
            'password' => 'Пароль',
            'firstname' => 'Имя',
            'secondname' => 'Фамилия',
            'email' => 'Email',
            'phone' => 'Номер телефона',
            'address' => 'Адрес (город, улица, дом)',
            'sum_purchased_retail' => 'Сумма покупок в розницу',
            'sum_purchased_gross' => 'Сумма покупок оптом',
        ];
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

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
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
}
