<?php

namespace backend\controllers;

use Yii;

use common\models\ConstructorPrintPrices;
use common\models\ConstructorPrintTypes;
use common\models\ConstructorProductMaterials;
use common\models\ConstructorPrintSizes;
use common\models\ConstructorPrintAttendance;

use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;


class ConstructorPrintPricesController extends Controller
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

  
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => ConstructorPrintPrices::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

   
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }


    public function actionCreate()
    {
        $model = new ConstructorPrintPrices();
        $materials = ConstructorProductMaterials::find()->asArray()->all();
        $types = ConstructorPrintTypes::find()->asArray()->all();
        $sizes = ConstructorPrintSizes::find()->asArray()->all();
        $attendances = ConstructorPrintAttendance::find()->asArray()->all();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'materials' => $materials,
                'types' => $types,
                'sizes' => $sizes,
                'attendances' => $attendances,
            ]);
        }
    }

  
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            
            $model->checkAttendaces();
            $materials = ConstructorProductMaterials::find()->asArray()->all();
            $types = ConstructorPrintTypes::find()->asArray()->all();
            $sizes = ConstructorPrintSizes::find()->asArray()->all();
            $attendances = ConstructorPrintAttendance::find()->asArray()->all();

            return $this->render('update', [
                'model' => $model,
                'materials' => $materials,
                'types' => $types,
                'sizes' => $sizes,
                'attendances' => $attendances,
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
        if (($model = ConstructorPrintPrices::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
