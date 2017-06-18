<?php
namespace common\models;
use Yii;
use yii\db\ActiveRecord;
use backend\models\User;
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
    const STATUS_NEW = 'new';
    const STATUS_PROCCESSING = 'proccessing';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';
    /**
     * Константы местонахождения заказа. 
     * Используются в backend\controllers\OrdersController.php
     */
    const LOCATION_MANAGER_NEW = 10;
    const LOCATION_MANAGER_ACCEPTED = 20;
    const LOCATION_EXECUTOR_NEW = 30;
    const LOCATION_EXECUTOR_ACCEPTED = 40;
    const LOCATION_COURIER_NEW = 50;
    const LOCATION_COURIER_ACCEPTED = 60;
    const LOCATION_COURIER_COMPLETED = 70;
    const LOCATION_EXECUTOR_COMPLETED = 80; // Исполнитель сделал заказ, курьер не был назначен
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'orders';
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
            [['order_status', 'client_name', 'address'], 'string', 'max' => 32],
            [['comment'], 'string', 'max' => 1000],
            ['phone', 'match', 'pattern' => '/9\d{9}/']
        ];
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
        $answ = "";
        // Менеджер 
        if (Yii::$app->user->identity->role == User::ROLE_MANAGER){
            $records = self::find()
                ->where("order_status='new'")->all();
            $answ .= count($records) > 0 ? count($records) : "";
        } 
       // Исполнитель
        elseif (Yii::$app->user->identity->role == User::ROLE_EXECUTOR){
            $records = Orders::find()
                ->where("order_status='proccessing' AND executor_id="
                    . Yii::$app->user->identity->id
                    . " AND location="
                    . Orders::LOCATION_EXECUTOR_NEW)
                ->all();
            $answ .= count($records) > 0 ? count($records) : "";
        }
        // Курьер
        elseif (Yii::$app->user->identity->role == User::ROLE_COURIER){
            $records = Orders::find()
                ->where("order_status='proccessing' AND courier_id="
                    . Yii::$app->user->identity->id
                    . " AND location="
                    . Orders::LOCATION_COURIER_NEW)
                ->all();
            $answ .= count($records) > 0 ? count($records) : "";
        }

        return $answ;
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
        $user = User::findIdentity($id);

        return $user;
    }
}
