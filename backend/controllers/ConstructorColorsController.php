<?php

namespace backend\controllers;

use Yii;
use common\models\ConstructorColors;
use common\models\ConstructorProducts;
use common\models\ConstructorSizes;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;


class ConstructorColorsController extends Controller
{
    

    public function behaviors()
    {
        return [
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

        return $this->render('update', [
            'model' => $model,
            'sizes' => $sizes,
        ]);
        

    }

    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->delete();

        return $this->redirect(['index', 'id' => $model->product_id]);
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
