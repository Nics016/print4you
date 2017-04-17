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
                        'actions' => ['view', 'new', 'proccessing', 'completed', 'accept', 'complete'],
                        'allow' => true,
                        // Allow manager and admin
                        'roles' => [
                            User::ROLE_MANAGER,
                            User::ROLE_ADMIN
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
     * @return mixed
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
        $records = Orders::find()
            ->where("order_status='new'");

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
        $records = Orders::find()
            ->where("order_status='proccessing'");

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
        $records = Orders::find()
            ->where("order_status='completed'");

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
     * @param integer $id
     */
    public function actionCancel($id)
    {
        $model = $this->findModel($id);
        $model->order_status = Orders::STATUS_CANCELLED;
        $model->save();

        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Заказу присваивается статус "В обработке",
     * менеджером присваивается текущий пользователь
     * и рендерится страница orders/view
     * @param integer $id
     */
    public function actionAccept($id, $comment)
    {
        $model = $this->findModel($id);
        $model->order_status = Orders::STATUS_PROCCESSING;
        $model->comment = $comment;
        $model->manager_id = Yii::$app->user->identity->id;
        $model->save();

        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Заказу присваивается статус "Завершен",
     * если менеджер - текущий пользователь
     * @param integer $id
     */
    public function actionComplete($id)
    {
        $model = $this->findModel($id);
        if ($model['manager_id'] != Yii::$app->user->identity->id){
            return $this->renderContent(Html::tag('h1','Ошибка - этот заказ был создан не вами!'));
        } else {
            $model->order_status = Orders::STATUS_COMPLETED;
            $model->save();
        }

        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Displays a single Orders model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Orders model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Orders();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
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
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
