<?php

namespace backend\controllers;

use Yii;
use common\models\Orders;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\filters\VerbFilter;

use common\components\AccessRule;
use yii\filters\AccessControl;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use backend\models\StockColors;
use backend\models\User;
use common\models\OrdersProduct;
use common\models\ConstructorStorage;

use frontend\components\Sms;

/**
 * OrdersController implements the CRUD actions for Orders model.
 */
class OrdersController extends Controller
{
    public $layout = 'adminPanel';

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'ruleConfig' => [
                    'class' => AccessRule::className(),
                ],
                'rules' => [
                     [
                        'actions' => ['view', 'new', 'proccessing', 'completed'],
                        'allow' => true,
                        // Allow courier, executor, manager and admin
                        'roles' => [
                            User::ROLE_COURIER,
                            User::ROLE_EXECUTOR,
                            User::ROLE_MANAGER,
                            User::ROLE_ADMIN
                        ],
                    ],
                    [
                        'actions' => ['accept-executor', 'complete-executor', 'complete', 'create'],
                        'allow' => true,
                        // Allow executor
                        'roles' => [
                            User::ROLE_EXECUTOR
                        ],
                    ],
                    [
                        'actions' => ['accept-courier', 'complete'],
                        'allow' => true,
                        // Allow courier
                        'roles' => [
                            User::ROLE_COURIER
                        ],
                    ],
                    [
                        'actions' => ['accept', 'pick-courier', 'pick-executor', 'change-product-data', 'create'],
                        'allow' => true,
                        // Allow manager
                        'roles' => [
                            User::ROLE_MANAGER
                        ],
                    ],
                    [
                        // TODO: убрать "delete" - админ не может удалять заказы
                        'actions' => ['create', 'delete', 'cancelled', 'index', 'deleted', 'update', 'cancel', 'change-product-data'],
                        'allow' => true,
                        // Allow only admin
                        'roles' => [
                            User::ROLE_ADMIN
                        ],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Orders models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Orders::find()->orderBy('id DESC'),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'ordersTitle' => 'Все заказы',
        ]);
    }

    /**
     * Lists only Orders with status "new"
     */
    public function actionNew()
    {
        $records = [];
        $mapColors = [];

        switch (Yii::$app->user->identity->role) {

            case User::ROLE_ADMIN:
                $records = Orders::find() 
                            ->where(['order_status'=> [Orders::STATUS_NEW, Orders::STATUS_NOT_PAID]])
                            ->orderBy('id DESC');
                break;
            
            case User::ROLE_MANAGER:
                $records = Orders::find() 
                            ->where(['order_status'=> [Orders::STATUS_NEW, Orders::STATUS_NOT_PAID]])
                            ->orderBy('id DESC');
                break;

            case User::ROLE_EXECUTOR:
                $records = Orders::find()->where([
                    'order_status' => Orders::STATUS_PROCCESSING,
                    'executor_id' => Yii::$app->user->identity->id,
                    'location' => Orders::LOCATION_EXECUTOR_NEW,
                ])->orderBy('id DESC');

                $colors = StockColors::find()
                    ->where(['office_id' => Yii::$app->user->identity->office_id])
                    ->asArray()->all();
                $mapColors = ArrayHelper::map($colors, 'id', 'name');

                break;

            case User::ROLE_COURIER:
                $records = Orders::find()->where([
                    'order_status' => Orders::STATUS_PROCCESSING,
                    'courier_id' => Yii::$app->user->identity->id,
                    'location' => Orders::LOCATION_COURIER_NEW,
                ])->orderBy('id DESC');
                break;
            
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $records,
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'ordersTitle' => 'Новые заказы',
            'mapColors' => $mapColors,
        ]);
    }

    /**
     * Lists only Orders with status "proccessing"
     */
    public function actionProccessing()
    {
        $records = [];

        switch (Yii::$app->user->identity->role) {

            case User::ROLE_ADMIN:
                $records = Orders::find()->where(['order_status' => Orders::STATUS_PROCCESSING])
                                    ->orderBy('id DESC');
                break;
            
            case User::ROLE_MANAGER:
                $records = Orders::find()->where([
                    'order_status' => Orders::STATUS_PROCCESSING,
                    'manager_id' => Yii::$app->user->identity->id,
                ])->orderBy('id DESC');
                break;

            case User::ROLE_EXECUTOR:
                $records = Orders::find()->where([
                    'order_status' => Orders::STATUS_PROCCESSING,
                    'executor_id' => Yii::$app->user->identity->id,
                    'location' => Orders::LOCATION_EXECUTOR_ACCEPTED,
                ])->orderBy('id DESC');
                break;

            case User::ROLE_COURIER:
                $records = Orders::find()->where([
                    'order_status' => Orders::STATUS_PROCCESSING,
                    'courier_id' => Yii::$app->user->identity->id,
                    'location' => Orders::LOCATION_COURIER_ACCEPTED,
                ])->orderBy('id DESC');
                break;
            
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $records,
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'ordersTitle' => 'Заказы в обработке',
        ]);
    }

    /**
     * Lists only Orders with status "new"
     */
    public function actionCompleted()
    {
        $records = [];


        switch (Yii::$app->user->identity->role) {

            case User::ROLE_ADMIN:
                $records = Orders::find()->where(['order_status' => Orders::STATUS_COMPLETED])
                                    ->orderBy('id DESC');
                break;
            
            case User::ROLE_MANAGER:
                $records = Orders::find()->where([
                    'order_status' => Orders::STATUS_COMPLETED,
                    'manager_id' => Yii::$app->user->identity->id,
                ])->orderBy('id DESC');
                break;

            case User::ROLE_EXECUTOR:
                $records = Orders::find()->where([
                    'location' => [
                        Orders::LOCATION_COURIER_NEW,
                        Orders::LOCATION_COURIER_ACCEPTED,
                        Orders::LOCATION_COURIER_COMPLETED,
                        Orders::LOCATION_EXECUTOR_COMPLETED,
                    ],
                    'executor_id' => Yii::$app->user->identity->id,
                ])->orderBy('id DESC');
                break;

            case User::ROLE_COURIER:
                $records = Orders::find()->where([
                    'location' => [
                        Orders::LOCATION_COURIER_COMPLETED,
                    ],
                    'courier_id' => Yii::$app->user->identity->id,
                ])->orderBy('id DESC');
                break;
            
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $records,
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'ordersTitle' => 'Завершенные заказы',
        ]);
    }

    /**
     * Lists only Orders with status "new"
     */
    public function actionCancelled()
    {
        $records = Orders::find()
            ->where(['order_status' => Orders::STATUS_CANCELLED])->orderBy('id DESC');

        $dataProvider = new ActiveDataProvider([
            'query' => $records,
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'ordersTitle' => 'Отмененные заказы',
        ]);
    }

    /**
     * Заказу присваивается статус "Отменен"
     * и рендерится страница orders/view
     * @param integer $id
     */
    public function actionCancel($id, $comment = '')
    {
        $model = $this->findModel($id);
        $model->order_status = Orders::STATUS_CANCELLED;
        $model->comment = $comment . ' / ' . $model->comment;
        $model->save(false);

        return $this->redirect('index');
    }

    /**
     * Заказу присваивается статус "В обработке",
     * менеджером присваивается текущий пользователь
     * и рендерится страница orders/view
     * 
     * @param integer $id
     */
    public function actionAccept($id, $comment='нет')
    {
        $model = $this->findModel($id);
        $model->order_status = Orders::STATUS_PROCCESSING;
        $model->location = Orders::LOCATION_MANAGER_ACCEPTED;
        $model->comment = $comment;
        $model->manager_id = Yii::$app->user->identity->id;
        $model->save();

        return $this->redirect('proccessing');
    }

    /**
     * При нажатии на кнопку "принять" исполнителем.
     * Происходит вычет краски со склада и меняется статус заказа
     */
    public function actionAcceptExecutor($id, array $stock_color_id, array $liters)
    {
        $i = 0;
        foreach ($stock_color_id as $color_id) {
            if ($liters[$i] && $liters[$i] >= 0) {
                $fLiters = 0;
                try {
                    $fLiters = (float)$liters[$i];
                } catch(Exception $e) {
                    return $this->renderContent(Html::tag('h1', $e->getMessage()));
                }

                $stockColor = StockColors::findOne(['id' => $color_id]);
                if ($stockColor) {
                    $stockColor->liters -= $fLiters;
                    $stockColor->save();
                }
                $i++;
            }
        }
        $model = $this->findModel($id);
        $model->stock_color_id = json_encode($stock_color_id);
        $model->stock_color_liters = json_encode($liters);
        $model->location = Orders::LOCATION_EXECUTOR_ACCEPTED;
        $model->save();

        return $this->redirect('proccessing');
    }

    /**
     * При нажатии на кнопку "завершить" исполнителем
     */
    public function actionCompleteExecutor($id)
    {
        $model = $this->findModel($id);

        if (!$model->delivery_required){
            $model->location = Orders::LOCATION_EXECUTOR_COMPLETED;
            $model->order_status = Orders::STATUS_COMPLETED;
            $model->save();

            // отправим СМС
            $message = 'Ваш заказ №' . $model->id . ' готов. Заберите его из точки самовывоза!';
            Sms::message($model->phone, $message);
            return $this->redirect('completed');

        } 

        $model->location = Orders::LOCATION_COURIER_NEW;
        $model->save();
        return $this->redirect('proccessing');
         
    }

    /**
     * При нажатии на кнопку "принять" курьером
     */
    public function actionAcceptCourier($id)
    {
        $model = $this->findModel($id);
        $model->location = Orders::LOCATION_COURIER_ACCEPTED;
        $model->save();

        $message = 'Ваш заказ №' . $model->id . ' уже выехал к Вам!';
        Sms::message($model->phone, $message);

        return $this->redirect('proccessing');
    }

    /**
     * Заказу присваивается статус "Завершен",
     * если курьер - текущий пользователь,
     * либо, если у заказа нет курьера, и 
     * исполнитель - текущий пользователь
     * 
     * @param integer $id - id заказа
     */
    public function actionComplete($id)
    {
        $model = $this->findModel($id);
        if ($model['courier_id'] == Yii::$app->user->identity->id){
            $model->order_status = Orders::STATUS_COMPLETED;
            $model->location = Orders::LOCATION_COURIER_COMPLETED;
            $model->save();

            return $this->redirect('completed');

        } else {
            return $this->renderContent(Html::tag('h1','Ошибка - этот заказ был создан не вами!'));
        }

        return $this->redirect('proccessing');
    }


    /**
     * Назначить курьера для заказа
     * 
     * @param  integer $id - id заказа
     * @param  integer $courier_id - id курьера
     * @param  string $comment - комментарий для курьера
     */
    public function actionPickCourier($id, $courier_id, $comment = '')
    {
        $model = $this->findModel($id);
        if ($model['manager_id'] != Yii::$app->user->identity->id){
            return $this->renderContent(Html::tag('h1','Ошибка - этот заказ был создан не вами!'));
        } else {
            $model->order_status = Orders::STATUS_PROCCESSING;
            $model->courier_id = $courier_id;
            $model->courier_comment = $comment;
            $model->save();
        }

        return $this->redirect('proccessing');
    }

    /**
     * Назначить исполнителя для заказа
     * 
     * @param  integer $id - id заказа
     * @param  integer $executor_id - id исполнителя
     */
    public function actionPickExecutor($id, $executor_id)
    {
        $model = $this->findModel($id);
        if ($model['manager_id'] != Yii::$app->user->identity->id){
            return $this->renderContent(Html::tag('h1','Ошибка - этот заказ был создан не вами!'));
        } else {
            $office_id = User::findIdentity($executor_id)->office_id;
            // Вычитаем товары со склада
            $products = OrdersProduct::find()
                ->where(['order_id' => $id])
                ->all();
            $transaction = Yii::$app->db->beginTransaction();
            foreach($products as $product){
                $storage = ConstructorStorage::findOne([
                    'color_id' => $product->color_id,
                    'size_id' => $product->size_id,
                    'office_id' => $office_id,
                ]);
                if ($storage){
                    if ($storage->count <= 0) {
                        $transaction->rollBack();
                        return $this->renderContent(Html::tag('h1', 'На складе недостаточно товаров!'));
                    }
                    $storage->count -= $product->count;
                    $storage->save();
                }
            }

            $model->order_status = Orders::STATUS_PROCCESSING;
            $model->executor_id = $executor_id;
            $model->location = Orders::LOCATION_EXECUTOR_NEW;
            $model->office_id = $office_id;
            $model->save();
            $transaction->commit();
        }

        return $this->redirect('proccessing');
    }

    /**
     * Displays a single Orders model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        // Курьер
        if (Yii::$app->user->identity->role == User::ROLE_COURIER){
            if ($model->courier_id != Yii::$app->user->identity->id) {
                return $this->renderContent(Html::tag('h1','Ошибка - у вас нет доступа к этому заказу'));
            }
        }

        return $this->render('view', [
            'model' => $model,
        ]);
    }


    /**
     * Change order product data as type print id or total price
     */

    public function actionChangeProductData()
    {
        if (Yii::$app->request->isAjax && Yii::$app->request->isPost) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return OrdersProduct::ajaxChangeProductData();
        }

        throw new NotFoundHttpException();
    }

    /**
     * Creates a new Orders model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Orders();

        if ($model->load(Yii::$app->request->post())) {
            $model->scenario = Orders::ORDER_CREATE_SCENARIO;
            $model->delivery_required = $model->address == '' ? false : true;
            if ($model->save()) 
              return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
        
    }

    /**
     * Updates an existing Orders model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Orders model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Orders model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Orders the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Orders::findOne($id)) !== null) {
            $model->scenario = $model::ADMIN_EDIT_SCENARIO;
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
