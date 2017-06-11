<?php

namespace backend\controllers;

use Yii;
use common\models\ConstructorColors;
use common\models\ConstructorProducts;
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
                'query' => ConstructorColors::find()->where(['id' => +$id]),
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
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

  
    public function actionCreate()
    {
        $model = new ConstructorColors();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

   
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

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }


    protected function findModel($id)
    {
        if (($model = ConstructorColors::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
