<?php
namespace common\models;
use Yii;
use yii\db\ActiveRecord;
use backend\models\User;
use frontend\components\Sms;

/**
 * This is the model class for table "orders".
 *
 * @property integer $id
 * @property string $order_status
 * @property integer $price
 * @property integer $manager_id
 * @property string $comment
 * @property integer $created_at
 * @property integer $updated_at
 */
class Orders extends \yii\db\ActiveRecord
{
    /**
     * Константы статусов заказа. 
     * Используются в backend\controllers\OrdersController.php
     */

    const YANDEX_CARD = '410011435962196';

    const STATUS_NOT_PAID = 'not_paid';
    const STATUS_NEW = 'new';
    const STATUS_PROCCESSING = 'proccessing';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';

    // сценарий для подтверждения менеджера
    const MANAGER_WORKING_SCENARIO = 'manager_working_scenario';
    const VALIDATE_SCENARIO = 'validate';
    const ADMIN_EDIT_SCENARIO = 'admin_edit';
    const ORDER_CREATE_SCENARIO = 'order_create';


    /**
     * Константы местонахождения заказа. 
     * Используются в backend\controllers\OrdersController.php
     */
    const LOCATION_MANAGER_NEW = 10;
    const LOCATION_MANAGER_ACCEPTED = 20;
    const LOCATION_EXECUTOR_NEW = 30;
    const LOCATION_EXECUTOR_ACCEPTED = 40;
    const LOCATION_EXECUTOR_COMPLETED_ORDER = 45; // Исполнитель сделал заказ
    const LOCATION_COURIER_NEW = 50;
    const LOCATION_COURIER_ACCEPTED = 60;
    const LOCATION_COURIER_COMPLETED = 70;
    const LOCATION_EXECUTOR_COMPLETED = 80; // Исполнитель сделал заказ, курьер не был назначен

    /**
     * Константы стоимости доставки товара
     */
    const DELIVERY_REQUIRED_PRICE = 300;
    const DELIVERY_NOT_REQUIRED_PRICE = 0;
    const GROSS_PRICE_PRODUCT_COUNT = 20;

    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'orders';
    }

    /**
     * Мя боюсь егоетод нах не нужен, но я боюсь его удалить))
     * 
     * @param  integer $price - начальная цена
     * @param  integer $discount - скидка в %
     * @return integer $discountedPrice - цена со скидкой
     */
    public static function calculateDiscountPrice($price, $discount = null)
    {
        return $price;
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['client_name', 'phone'], 'required'],
            [['price', 'manager_id', 'created_at', 'updated_at', 'client_id', 'delivery_office_id'], 'integer'],
            ['delivery_required', 'boolean'],
            [['order_status', 'client_name', 'address'], 'string', 'max' => 255],
            ['phone', 'phoneValidate', 'except' => [self::MANAGER_WORKING_SCENARIO, self::ADMIN_EDIT_SCENARIO, self::ORDER_CREATE_SCENARIO]],
            ['phone', 'match', 'pattern' => '/9\d{9}/','on' => self::ORDER_CREATE_SCENARIO],
            [['comment', 'courier_comment'], 'string', 'max' => 1000],
        ];
    }

    /**
     * Загрузка данных из аякс запроса
     */
    public function loadFromAjax()
    {
        $this->client_name = Yii::$app->request->post('firstname');
        $this->phone = Yii::$app->request->post('phone');
        $this->comment = Yii::$app->request->post('comment');
        $this->delivery_required = (boolean)Yii::$app->request->post('delivery_required');
        $this->order_status = self::STATUS_NOT_PAID;

        if ($this->delivery_required) {

            $this->delivery_office_id = null;
            $this->address = Yii::$app->request->post('address');
            $distance = (int)Yii::$app->request->post('distance');
            if (!isset(self::DELIVERY_DISTANCES[$distance])) return false;
            $this->delivery_price = self::DELIVERY_DISTANCES[$distance]['price'];

        } else {

            $this->delivery_office_id = (int)Yii::$app->request->post('office_id');
            $this->delivery_price = Orders::DELIVERY_NOT_REQUIRED_PRICE;
            $office = Office::findOne(["id" => $this->delivery_office_id]);
            if ($office == null) return false;
            $this->address = $office->address;

        }

        return true;
    }

    /**
     * Проверка телефона  в заказк
     */
    public static function checkPhone($phone)
    {
        // если пользователь залогинен и это его номер
        if (!Yii::$app->user->isGuest) {
            $user_phone = Yii::$app->user->identity->phone;
            if ($user_phone == $phone) return true;
        }
        
        // если нет, то проверим, существует ли логин по базе
        $exists = CommonUser::find()->where(['phone' => $phone])->exists();

        return !$exists;
    }

    public function phoneValidate()
    {
        if (!preg_match('/9\d{9}/', $this->phone)) {
            $this->addError('phone', 'Неправильный формат номера!');
            return false;
        }

        if (!self::checkPhone($this->phone)) {
            $this->addError('phone', 'Телефон уже используется!');
            return false;
        }

        return true;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_status' => 'Статус',
            'price' => 'Стоимость',
            'manager_id' => 'ID менеджера',
            'comment' => 'Комментарий',
            'client_name' => 'Имя',
            'address' => 'Адрес доставки',
            'phone' => 'Номер телефона',
            'created_at' => 'Дата',
            'updated_at' => 'Дата изменения',
            'delivery_required' => 'Доставка',
            'delivery_price' => 'Стоимость доставки',
        ];
    }
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
            ],
        ];
    }

    /**
     * Возвращает количество завершенных заказов клиента
     * 
     * @param  common\models\CommonUser $user - модель клиента
     * @return integer $numOrders
     */
    public static function getClientCompletedOrdersCount($user)
    {
        $numOrders = 0;

        $records = self::find()
            ->where('order_status=:status AND client_id=:client_id', [
                    ':status' => self::STATUS_COMPLETED,
                    ':client_id' => $user->identity->id,
                ])->all();
            
        if ($records){
            $numOrders = count($records);
        }

        return $numOrders;
    }

    public static function getClientCompletedOrdersCountNew($user)
    {

        $records = self::find()->select('id')
            ->where([
                    'order_status' => self::STATUS_COMPLETED,
                    'client_id' => $user->identity->id,
                ])->asArray()->all();
            

        return count($records);
    }

    /**
     * Возвращает количество новых заказов для юзера/
     * Используется в бэкэнде (!)
     * 
     * @param  backend\models\User $user - модель бэкэндного юзера
     */
    public static function getNewOrdersCount($user)
    {
        $records = [];

        switch (Yii::$app->user->identity->role) {

            case User::ROLE_ADMIN:
                $records = self::find() 
                            ->where(['order_status'=> [Orders::STATUS_NEW, Orders::STATUS_NOT_PAID]])
                            ->asArray()->all();
                break;
            
            case User::ROLE_MANAGER:
                $records = self::find() 
                            ->where(['order_status'=> [Orders::STATUS_NEW, Orders::STATUS_NOT_PAID]])
                            ->asArray()->all();
                break;

            case User::ROLE_EXECUTOR:
                $records = self::find()->where([
                    'order_status' => self::STATUS_PROCCESSING,
                    'executor_id' => Yii::$app->user->identity->id,
                    'location' => self::LOCATION_EXECUTOR_NEW,
                ])->asArray()->all();
                break;

            case User::ROLE_COURIER:
                $records = Orders::find()->where([
                    'order_status' => self::STATUS_PROCCESSING,
                    'courier_id' => Yii::$app->user->identity->id,
                    'location' => self::LOCATION_COURIER_NEW,
                ])->asArray()->all();
                break;
            
        }

        return count($records) > 0 ? count($records) : "";
    }

    public function getNewOrdersCountNew()
    {
        switch (Yii::$app->user->identity->role) {
            // Менеджер 
            case User::ROLE_MANAGER:

                // безопасный запрос
                $records = self::find()->where(['order_status' => self::STATUS_NEW])->all();
                break;
            // Исполнитель
            case User::ROLE_EXECUTOR:
                $records = Orders::find()
                            ->where([
                                'order_status' => self::STATUS_PROCCESSING,
                                'executor_id' => Yii::$app->user->identity->id,
                                'location' => self::LOCATION_EXECUTOR_NEW,
                            ])->all();
                break;
            // Курьер
            case User::ROLE_COURIER:
                $records = self::find()
                            ->where([
                                'order_status' => self::STATUS_PROCCESSING,
                                'courier_id'=> Yii::$app->user->identity->id,
                                'location' => self::LOCATION_COURIER_NEW,
                            ])->all();
                break;
        }

        // php считает только 1 раз
        $count = count($records);
        return  $count > 0 ? (string)$count : "";
    }

    public function getUser($id)
    { 
        return User::findIdentity($id);
    }

    public function getClient()
    {
        return $this->hasOne(CommonUser::className(), ['id' => 'client_id']);
    }

    public function orderCreatedSms()
    {   
        $message = "Спасибо, Ваш заказ №" . $this->id . " ожидает оплаты!\r\n";
        $message .= 'Сумма заказа - ' . $this->price . "р.";
        if ($this->delivery_price != Orders::DELIVERY_NOT_REQUIRED_PRICE) {
            $message .= " + " . $this->delivery_price . "р. (за доставку)";
        }

        $message .= "\r\n";
        $message .= 'Вы можете отлеживать его статус в личном кабинете.';

        Sms::message($this->phone, $message);
    }

    public function sucessSms() 
    {
        $message = "Спасибо за оплату, Ваш заказ №" . $this->id . " принят в обработку!\r\n";
        $message .= "\r\n";
        $message .= 'Вы можете отлеживать его статус в личном кабинете.';
        Sms::message($this->phone, $message);
    }


    const CARD_NUMBER = '5469 5500 2457 4003';

    const DELIVERY_DISTANCES = [
        [
            'name' => 'Выборгский район',
            'price' => 190,
        ],

        [
            'name' => 'Калининский район',
            'price' => 190,
        ],

        [
            'name' => 'Центральный район',
            'price' => 190,
        ],

        [
            'name' => 'Адмиралтейский район',
            'price' => 190,
        ],
        [
            'name' => 'Василеостровский район',
            'price' => 250,
        ],
        [
            'name' => 'Петроградский район',
            'price' => 250,
        ],
        [
            'name' => 'Невский район',
            'price' => 250,
        ],
        [
            'name' => 'Красногвардейский район',
            'price' => 250,
        ],
        [
            'name' => 'Приморский район',
            'price' => 250,
        ],
        [
            'name' => 'Фрунзенский район',
            'price' => 290,
        ],
        [
            'name' => 'Московский район',
            'price' => 290,
        ],
        [
            'name' => 'Красносельский район',
            'price' => 290,
        ],
        [
            'name' => 'Кировский район',
            'price' => 290,
        ],
        [
            'name' => 'За пределами Кад',
            'price' => 600,
        ],
        [
            'name' => 'По России',
            'price' => 350,
        ],
        [
            'name' => 'Страны СНГ',
            'price' => 550,
        ],
    ];
}
