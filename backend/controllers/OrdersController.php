<?php

namespace backend\controllers;

use Yii;
use common\models\Orders;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use common\components\AccessRule;
use yii\filters\AccessControl;
use yii\helpers\Html;
use backend\models\User;

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
                        'actions' => ['accept-executor', 'complete-executor', 'complete'],
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
                        'actions' => ['accept', 'pick-courier', 'pick-executor'],
                        'allow' => true,
                        // Allow manager
                        'roles' => [
                            User::ROLE_MANAGER
                        ],
                    ],
                    [
                        // TODO: убрать "delete" - админ не может удалять заказы
                        'actions' => ['create', 'delete', 'cancelled', 'index', 'deleted', 'update', 'cancel'],
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
     *
     * Доступно только админам
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Orders::find(),
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
        // Менеджер и админ
        if (Yii::$app->user->identity->role == User::ROLE_MANAGER
            || Yii::$app->user->identity->role == User::ROLE_ADMIN){
            $records = Orders::find()
                ->where("order_status='new'");
        } 
        // Исполнитель
        elseif (Yii::$app->user->identity->role == User::ROLE_EXECUTOR){
            $records = Orders::find()
                ->where("order_status='proccessing' AND executor_id="
                    . Yii::$app->user->identity->id
                    . " AND location="
                    . Orders::LOCATION_EXECUTOR_NEW);
        }
        // Курьер
        elseif (Yii::$app->user->identity->role == User::ROLE_COURIER){
            $records = Orders::find()
                ->where("order_status='proccessing' AND courier_id="
                    . Yii::$app->user->identity->id);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $records,
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'ordersTitle' => 'Новые заказы',
        ]);
    }

    /**
     * Lists only Orders with status "proccessing"
     */
    public function actionProccessing()
    {
        $records = [];
        // Менеджер и админ
        if (Yii::$app->user->identity->role == User::ROLE_MANAGER
            || Yii::$app->user->identity->role == User::ROLE_ADMIN){
            $records = Orders::find()
                ->where("order_status='proccessing'");
        } 
        // Исполнитель
        elseif (Yii::$app->user->identity->role == User::ROLE_EXECUTOR){
            $records = Orders::find()
                ->where("order_status='proccessing' AND executor_id="
                    . Yii::$app->user->identity->id
                    . " AND location="
                    . Orders::LOCATION_EXECUTOR_ACCEPTED);
        }
        // Курьер
        elseif (Yii::$app->user->identity->role == User::ROLE_COURIER){
            $records = Orders::find()
                ->where("order_status='proccessing' AND courier_id="
                    . Yii::$app->user->identity->id
                    . " AND location="
                    . Orders::LOCATION_COURIER_ACCEPTED);
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
        // Менеджер и админ
        if (Yii::$app->user->identity->role == User::ROLE_MANAGER
            || Yii::$app->user->identity->role == User::ROLE_ADMIN){
            $records = Orders::find()
                ->where("order_status='completed'");
        } 
        // Исполнитель
        elseif (Yii::$app->user->identity->role == User::ROLE_EXECUTOR){
            $records = Orders::find()
                ->where("order_status='completed' AND executor_id="
                    . Yii::$app->user->identity->id);
        }
        // Курьер
        elseif (Yii::$app->user->identity->role == User::ROLE_COURIER){
            $records = Orders::find()
                ->where("order_status='completed' AND courier_id="
                    . Yii::$app->user->identity->id);
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
     *
     * Действие доступно только админам
     */
    public function actionCancelled()
    {
        $records = Orders::find()
            ->where("order_status='cancelled'");

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
     *
     * Действие доступно только админам
     * 
     * @param integer $id
     */
    public function actionCancel($id, $comment = '')
    {
        $model = $this->findModel($id);
        $model->order_status = Orders::STATUS_CANCELLED;
        $model->comment = $comment . ' / ' . $model->comment;
        $model->save();

        return $this->redirect('index');
    }

    /**
     * Заказу присваивается статус "В обработке",
     * менеджером присваивается текущий пользователь
     * и рендерится страница orders/view
     *
     * Действие доступно только менеджерам
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
     * Действие при нажатии "Принять" исполнителем
     *    
     * @param  integer $id - id заказа
     */
    public function actionAcceptExecutor($id)
    {
        $model = $this->findModel($id);
        if (!(Yii::$app->user->identity->role == User::ROLE_EXECUTOR
                && (Yii::$app->user->identity->id == $model->executor_id) )){
            return $this->renderContent(Html::tag('h1','Ошибка - у вас нет доступа к этому заказу'));
        }

        $model->location = Orders::LOCATION_EXECUTOR_ACCEPTED;
        $model->save();

        return $this->redirect('proccessing');
    }

    /**
     * Действие при нажатии "Завершить" исполнителем
     *    
     * @param  integer $id - id заказа
     */
    public function actionCompleteExecutor($id)
    {
        $model = $this->findModel($id);
        if (!(Yii::$app->user->identity->role == User::ROLE_EXECUTOR
                && (Yii::$app->user->identity->id == $model->executor_id) )){
            return $this->renderContent(Html::tag('h1','Ошибка - у вас нет доступа к этому заказу'));
        }

        $model->location = Orders::LOCATION_COURIER_NEW;
        $model->save();

        // Если нет курьера, заказ готов
        if ($model->courier_id == NULL){
            $model->order_status = Orders::STATUS_COMPLETED;
            $model->location = Orders::LOCATION_EXECUTOR_COMPLETED;
            $model->save();
        }

        return $this->redirect('proccessing');
    }

    /**
     * Действие при нажатии "Принять" курьером
     *    
     * @param  integer $id - id заказа
     */
    public function actionAcceptCourier($id)
    {
        $model = $this->findModel($id);
        if (!(Yii::$app->user->identity->role == User::ROLE_COURIER
                && (Yii::$app->user->identity->id == $model->courier_id) )){
            return $this->renderContent(Html::tag('h1','Ошибка - у вас нет доступа к этому заказу'));
        }

        $model->location = Orders::LOCATION_COURIER_ACCEPTED;
        $model->save();

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
            return $this->renderContent(Html::tag('h1','Ошибка - это действие вам не доступно'));
        } else {
            $model->order_status = Orders::STATUS_PROCCESSING;
            $model->courier_id = $courier_id;
            $model->comment = $comment;
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
            return $this->renderContent(Html::tag('h1','Ошибка - это действие вам не доступно'));
        } else {
            $model->order_status = Orders::STATUS_PROCCESSING;
            $model->executor_id = $executor_id;
            $model->location = Orders::LOCATION_EXECUTOR_NEW;
            $model->save();
        }

        return $this->redirect('proccessing');
    }

    /**
     * Displays a single Orders model.
     * 
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $forbidden = false;

        // Исполнитель
        if (Yii::$app->user->identity->role == User::ROLE_EXECUTOR){
            if ($model->executor_id != Yii::$app->user->identity->id)
                $forbidden = true;
        }
        // Курьер
        elseif (Yii::$app->user->identity->role == User::ROLE_COURIER){
            if ($model->courier_id != Yii::$app->user->identity->id)
                $forbidden = true;
        }

        if ($forbidden)
            return $this->renderContent(Html::tag('h1','Ошибка - у вас нет доступа к этому заказу'));

        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Orders model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * Доступно только админу
     * 
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Orders();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['new']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Orders model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * Доступно только админу
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Orders model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * Доступно только админу
     * 
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
     * 
     * @param integer $id
     * @return Orders the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Orders::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
