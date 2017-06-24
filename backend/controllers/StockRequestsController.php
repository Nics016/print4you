<?php

namespace backend\controllers;

use Yii;
use backend\models\StockRequests;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use backend\models\User;
use common\models\Office;
use common\components\AccessRule;
use yii\filters\AccessControl;

use backend\models\Model;
use backend\models\StockColors;
use common\models\ConstructorStorage;
use backend\models\StockRequestItems;
use common\models\ConstructorProducts;
use common\models\ConstructorColors;
use common\models\ConstructorSizes;

/**
 * StockRequestsController implements the CRUD actions for StockRequests model.
 */
class StockRequestsController extends Controller
{
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
                        'allow' => true,
                        // Allow only executor
                        'roles' => [
                            User::ROLE_ADMIN,
                            User::ROLE_MANAGER,
                        ],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST', 'GET'],
                ],
            ],
        ];
    }

    /**
     * Lists all StockRequests models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => StockRequests::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new StockRequests model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new StockRequests();
        $modelsColors = [new StockColors];
        $modelsGoods = [new ConstructorStorage];

        if ($model->load(Yii::$app->request->post())) {
            $modelsColors = Model::createMultiple(StockColors::classname());
            $modelsGoods = Model::createMultiple(ConstructorStorage::classname());
            Model::loadMultiple($modelsColors, Yii::$app->request->post());
            Model::loadMultiple($modelsGoods, Yii::$app->request->post());

            // AJAX validation ?
            // ...
            
            $valid = $model->validate();
            // $valid = Model::validateMultiple($modelsColors) && $valid;

            if ($valid) {
                $model->user_id = Yii::$app->user->identity->id;
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    if ($flag = $model->save(false)) {
                        $curRequestId = $model->id;

                        foreach($modelsColors as $modelColor) {
                            $modelRequestItem = new StockRequestItems();
                            $modelRequestItem->stock_request_id = $curRequestId;
                            $modelRequestItem->office_id = $model->office_id;
                            $modelRequestItem->stock_color_id = $modelColor->color_id;
                            $modelRequestItem->stock_color_litres = $modelColor->liters;
                            $modelRequestItem->save();
                        }

                        foreach($modelsGoods as $modelGood) {
                            $modelConstructorStorage = ConstructorStorage::findOne([
                                'id' => $modelGood->constructor_storage_id,
                            ]);

                            $modelRequestItem = new StockRequestItems();
                            $modelRequestItem->stock_request_id = $curRequestId;
                            $modelRequestItem->office_id = $model->office_id;
                            $modelRequestItem->constructor_storage_id = $modelConstructorStorage->id;
                            $modelRequestItem->constructor_storage_count = $modelGood->count;
                            $modelRequestItem->save();
                        }
                    }
                    if ($flag) {
                        $transaction->commit();
                        return $this->redirect(['stock-requests/index']);
                    }

                } catch (Exception $e) {
                    $transaction->rollBack();
                }                
            } else { // not valid
                return $this->renderContent(Html::tag('h1', 'Validation failed'));
            }
            
        } else { // not post
            $modelsOffices = Office::find()->all();
            $mapOffices = [];
            foreach ($modelsOffices as $modelOffice) {
                $mapOffices[$modelOffice->id] = $modelOffice->id . ' - ' . $modelOffice->address;
            }

            $modelsStockColors = StockColors::find()->orderBy('office_id')->all();
            foreach ($modelsStockColors as $modelStockColor) {
                $mapStockColors[$modelStockColor->id] = $modelStockColor->office_id . ' - ' . $modelStockColor->name;
            }

            /*$query = Yii::$app->db->createCommand(
                'SELECT products.id as product_id, colors.id as color_id,
                 sizes.id as size_id,   
                 products.name as product_name, colors.name as color_name,
                 sizes.size as size_name
                 FROM constructor_products products
                 INNER JOIN constructor_colors colors ON colors.product_id = products.id
                 INNER JOIN constructor_color_sizes colors_sizes ON colors_sizes.color_id = colors.id
                 INNER JOIN constructor_sizes sizes ON colors_sizes.size_id = sizes.id'
            );
            $goods = $query->queryAll();
            $mapGoods = [];
            foreach ($goods as $good){
                $key = [
                        'product_id' => $good['product_id'], 
                        'color_id' => $good['color_id'],
                        'size_id' => $good['size_id'],
                ];
                $val = $good['product_name'] 
                        . " - " . $good['color_name']
                        . " - " . $good['size_name'];
                $mapGoods[json_encode($key)] = $val;
            }*/

            $goods = ConstructorStorage::find()->orderBy('office_id')->all();
            $mapGoods = [];
            foreach ($goods as $good){
                $modelContructorStorage = ConstructorStorage::findOne(['id' => $good->id]);
                $modelColor = ConstructorColors::FindOne(['id' => $modelContructorStorage['color_id']]);
                $modelProduct = ConstructorProducts::FindOne(['id' => $modelColor['product_id']]);
                $modelSize = ConstructorSizes::FindOne(['id' => $modelContructorStorage['size_id']]);
                $key = $good->id;
                $val = $good->office_id 
                    . ' - ' . $modelProduct['name'] 
                    . ' - ' . $modelColor['name'] 
                    . ' - ' . $modelSize['size'];
                $mapGoods[$key] = $val;
            }

            return $this->render('create', [
                'model' => $model,
                'mapOffices' => $mapOffices,
                'modelsColors' => (empty($modelsColors)) ? [new StockColors] : $modelsColors,
                'modelsGoods' => (empty($modelsGoods)) ? [new ConstructorStorage] : $modelsGoods,
                'mapStockColors' => $mapStockColors,
                'mapGoods' => $mapGoods,
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

        return $this->redirect(['stock-requests/index']);
    }

    /**
     * Действие, когда админ нажимает на кнопку "Одобрить"
     * 
     * @param  integer $id - id заявки
     */
    public function actionAcceptRequest($id)
    {
        $model = $this->findModel($id);
        $items = StockRequestItems::find()
            ->where(['stock_request_id' => $id])
            ->all();

        // Добавляем краску и товары на склад
        foreach ($items as $item) {
            if ((!$item->applied) && $item->stock_color_id && $item->stock_color_litres) {
                $modelColor = StockColors::findOne(['id' => $item->stock_color_id]);
                $modelColor->liters += $item->stock_color_litres;
                $modelColor->save(false);
                $item->applied = true;
                $item->save(false);
            }
            if ((!$item->applied) && $item->constructor_storage_id && $item->constructor_storage_count) {
                $modelConstructorStorage = ConstructorStorage::findOne(['id' => $item->constructor_storage_id]);
                $modelConstructorStorage->count += $item->constructor_storage_count;
                $modelConstructorStorage->save(false);
                $item->applied = true;
                $item->save(false);
            }
        }

        $model->applied = true;
        $model->save(false);

        return $this->redirect(['stock-requests/index']);
    }

    /**
     * Finds the StockRequests model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return StockRequests the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = StockRequests::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
