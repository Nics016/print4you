<?php

namespace backend\controllers;

use Yii;
use common\models\ConstructorProducts;
use common\models\ConstructorColors;
use common\models\ConstructorColorSizes;
use common\models\ConstructorStorage;
use backend\models\SearchConstructorProducts;
use yii\web\Controller;
use yii\web\Response;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;

use backend\models\User;
use common\components\AccessRule;
use yii\filters\AccessControl;

class ConstructorSkladController extends Controller
{
    
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
                            User::ROLE_EXECUTOR
                        ],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'get-modal' => ['POST'],
                    'set-modal' => ['POST'],
                ],
            ],
        ];
    }


    public function actionIndex()
    {
        $searchModel = new SearchConstructorProducts();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

  


    public function actionColors($product_id)
    {   

        $product = ConstructorProducts::find()->where(['id' => +$product_id])->one();

        if ($product != null) {
            $dataProvider = new ActiveDataProvider([
                'query' => ConstructorColors::find()->where(['product_id' => +$product_id]),
            ]);

            return $this->render('colors', [
                'dataProvider' => $dataProvider,
                'product' => $product,
            ]);
        }

        throw new NotFoundHttpException("Продукта не найдено");
        
    }

    public function actionGetModal()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $color_id = (int)Yii::$app->request->post('color_id');
        $office_id = Yii::$app->user->identity->office_id;
        $avaliable_sizes = ConstructorColorSizes::getAvaliableSizes($color_id, $office_id);

        if ($avaliable_sizes !== false) {

            for ($i = 0; $i < count($avaliable_sizes); $i++) {
                $current = &$avaliable_sizes[$i];
                $storage =  ConstructorStorage::find()
                                ->where(['color_id' => $color_id, 'size_id' => $current['id'], 'office_id' => $office_id])
                                ->limit(1)->one();

                $current['count'] = $storage != null ? $storage->count : null;
            }

            return [
                'status' => 'ok',
                'html' => $this->renderAjax('modal', [
                    'avaliable_sizes' => $avaliable_sizes,
                    'color_id' => $color_id,
                ]),
            ];
        }

        return ['status' => 'fail'];
        
    }

    // устанавливается значения для склада
    public function actionSetData()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $color_id = (int)Yii::$app->request->post('color_id');
        $office_id = Yii::$app->user->identity->office_id;
        $data = json_decode(Yii::$app->request->post('data'), true);
        $data_count = count($data);

        if ($data_count > 0) {

            for ($i = 0; $i < $data_count; $i++) {
                $size_id = (int)$data[$i]['size_id'];
                $count = (int)$data[$i]['count'];
                if (!ConstructorStorage::setData($color_id, $size_id, $office_id, $count))
                    return ['status' => 'fail'];
            }

            return ['status' => 'ok'];
        }

        return ['status' => 'fail'];
    }

}
