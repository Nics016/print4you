<?php

namespace backend\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\data\SqlDataProvider;
use yii\data\ArrayDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\db\Query;

use common\components\AccessRule;
use yii\filters\AccessControl;
use backend\models\User;
use common\models\CommonUser;
use common\models\Orders;
use common\models\Office;
use yii\web\Response;
use yii\widgets\ActiveForm;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
{
    public $layout = 'adminPanel';

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST', 'GET'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'ruleConfig' => [
                    'class' => AccessRule::className(),
                ],
                'rules' => [
                    [
                        'actions' => ['create', 'index', 'update', 'view', 'delete', 'statistics'],
                        'allow' => true,
                        // Allow only admin
                        'roles' => [
                            User::ROLE_ADMIN
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     *
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => User::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Отображает статистику по
     * - приросту пользователей
     * - список ничего не заказавших пользователей
     * - возможность отсылки им сообщений
     */
    public function actionStatistics()
    {
        // clients
        $clients = CommonUser::find()
            ->where(['status' => CommonUser::STATUS_ACTIVE])
            ->all();
        $clientsByDate = [];
        foreach($clients as $client) {
            $clientRegistered = Yii::$app->formatter->asDate($client->created_at);
            if (!array_key_exists($clientRegistered, $clientsByDate)) {
                $clientsByDate[$clientRegistered] = 1;
            } else {
                $clientsByDate[$clientRegistered]++;
            }
        }
        $queryNoOrders = Yii::$app->db->createCommand("
            SELECT username, common_user.phone as phone FROM common_user
            INNER JOIN orders ON common_user.id <> orders.client_id
        ");
        $clientsWithoutOrders = $queryNoOrders->queryAll();

        // orders
        $ordersAll = Orders::find()
            ->all();
        $ordersCompleted = Orders::find()
            ->where(['order_status' => Orders::STATUS_COMPLETED])
            ->all();

        // orders - data providers
        $queryManagers = (new Query())
            ->select([
                'manager_name' => 'u.username', 
                'num_orders' => 'COUNT(orders.id)'
            ])
            ->from('user u')
            ->where(['u.role' => User::ROLE_MANAGER])
            ->join('LEFT JOIN', 'orders', 'orders.manager_id=u.id')
            ->groupBy('manager_name');
        $commandManagers = $queryManagers->createCommand();
        $dataProviderManagers = new SqlDataProvider([
            'sql' => $commandManagers->sql,
            'params' => $commandManagers->params,
            'totalCount' => $queryManagers->count(),
        ]);

        $modelsOffices = Office::find()->asArray()->all();
        $arrayOffices = [];
        foreach ($modelsOffices as $office) {
            $arrayOffices[$office['id']] = [
                'office_address' => $office['address'],
                'num_orders' => 0,
            ];
        }
        foreach ($ordersCompleted as $order) {
            if ($order->office_id) {
                $arrayOffices[$order->office_id]['num_orders']++;
            }
        }
        $dataProviderOffices = new ArrayDataProvider([
            'allModels' => $arrayOffices,
        ]);

        return $this->render('statistics', [
            'totalClients' => count($clients),
            'clientsByDate' => $clientsByDate,
            'clientsWithoutOrders' => $clientsWithoutOrders,
            'totalOrdersAll' => count($ordersAll),
            'totalOrdersCompleted' => count($ordersCompleted),
            'dataProviderManagers' => $dataProviderManagers,
            'dataProviderOffices' => $dataProviderOffices,
        ]);
    }

    /**
     * Displays a single User model.
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
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new User();
        $model->scenario = User::CREATE_SCENARIO;

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post())) {
            $model->generatePasswordHash($model['password']);
            $model->generateAuthKey();
            if ($model->save())
                return $this->redirect(['view', 'id' => $model->id]);
            else 
                print_r($model->getErrors());
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post())) {
            if ($model['password'] != ''){
                $model->generatePasswordHash($model['password']);
                $model->generateAuthKey();
            }
            
            if ($model->save())
                return $this->redirect(['view', 'id' => $model->id]);
            else 
                print_r($model->getErrors());
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing User model.
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
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * Performs AJAX validation.
     *
     * @param array|Model $model
     *
     * @throws ExitException
     */
    protected function performAjaxValidation($model)
    {
        if (\Yii::$app->request->isAjax && !\Yii::$app->request->isPjax) {
            if ($model->load(\Yii::$app->request->post())) {
                \Yii::$app->response->format = Response::FORMAT_JSON;
                echo json_encode(ActiveForm::validate($model));
                \Yii::$app->end();
            }
        }
    }
}
