<?php

namespace backend\controllers;

use Yii;
use common\models\ConstructorColors;
use common\models\ConstructorProducts;
use common\models\ConstructorSizes;
use common\models\ConstructorStorage;
use common\models\ConstructorAdditionalSides;
use common\models\ConstructorColorsSides;

use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\filters\VerbFilter;

use backend\models\User;
use common\components\AccessRule;
use yii\filters\AccessControl;

class ConstructorColorsController extends Controller
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


    public function actionIndex($id)
    {   

        $product = ConstructorProducts::find()->where(['id' => +$id])->one();

        if ($product != null) {
            $dataProvider = new ActiveDataProvider([
                'query' => ConstructorColors::find()->where(['product_id' => +$id]),
            ]);

            return $this->render('index', [
                'dataProvider' => $dataProvider,
                'product' => $product,
            ]);
        }

        throw new NotFoundHttpException("Продукта не найдено");
        

        
    }

    
    public function actionView($id)
    {   

        $model = ConstructorColors::find()->where(['id' => +$id])->with('sizes')->with('product')->one();

        if ($model != null)
            return $this->render('view', [
                'model' => $model,
                'product' => ConstructorProducts::find()->where(['id' => $model->product->id])->one(),
            ]);

        throw new NotFoundHttpException("Продукта не найдено");
    }

  
    public function actionCreate($product_id)
    {
        $model = new ConstructorColors();
        $product = ConstructorProducts::find()->where(['id' => +$product_id])->one();
        $sizes = ConstructorSizes::find()->asArray()->orderBy('sequence')->all(); 

        if ($product != null) {
            if ($model->load(Yii::$app->request->post())) {
                $model->product_id = +$product_id;
                if($model->saveAll())
                    return $this->redirect(['view', 'id' => $model->id]);
            } 

            return $this->render('create', [
                'model' => $model,
                'product' => $product,
                'sizes' => $sizes,
            ]);
        }

        throw new NotFoundHttpException("Продукта не найдено");
        
    }

   
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $sizes = ConstructorSizes::find()->asArray()->orderBy('sequence')->all(); 

        
        if ($model->load(Yii::$app->request->post())) {
            if($model->saveAll())
                return $this->redirect(['view', 'id' => $model->id]);
        } 

        $model->checkSizes();
        $color_storage = ConstructorStorage::find()->where(['color_id' => $model->id])->orderBy('office_id')
                                            ->with('office')->with('size')->asArray()->all();

        return $this->render('update', [
            'model' => $model,
            'sizes' => $sizes,
            'color_storage' => $color_storage,
        ]);
        

    }

    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->delete();

        return $this->redirect(['index', 'id' => $model->product_id]);
    }

    public function actionAdditionalSides($id) 
    {
        $color = $this->findModel($id);

        $models = ConstructorColorsSides::find()->where(['color_id' => (int)$id])->all();
        $sides = ConstructorAdditionalSides::find()->asArray()->all();

        return $this->render('additional-sides', [
            'models' => $models, 
            'color' => $color,
            'sides' => $sides,
        ]);
    }

    // выводит форму для изменения стороны
    public function actionSideForm()
    {
        if (Yii::$app->request->isAjax && Yii::$app->request->isPost) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $sides = ConstructorAdditionalSides::find()->asArray()->all();
            return [
                'html' => $this->renderAjax('_side-form', ['sides' => $sides]), 
            ];
        }

        throw new NotFoundHttpException();
    }

    public function actionEditSide()
    {
        if (Yii::$app->request->isAjax && Yii::$app->request->isPost) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            switch (Yii::$app->request->post('action')) {
                case 'save':
                    return ConstructorColorsSides::uploadFromAjax();
                    break;
                
                case 'remove':
                    $id = (int)Yii::$app->request->post('id');
                    $model = ConstructorColorsSides::findOne($id);

                    if ($model == null) return ['status' => 'fail'];
                    return $model->delete() ? ['status' => 'ok'] : ['status' => 'fail'];

                    break;

                default:
                    throw new NotFoundHttpException();
                    break;
            }
            
        }

        throw new NotFoundHttpException();
    }

    protected function findModel($id)
    {
        if (($model = ConstructorColors::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('Цвета не найдено');
        }
    }
}
