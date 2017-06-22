<?php

namespace backend\controllers;

use Yii;

use common\models\ConstructorProducts;
use common\models\ConstructorCategories;

use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\widgets\ActiveForm;

class ConstructorProductsController extends Controller
{
    
    public $layout = 'adminPanel';

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
            'query' => ConstructorProducts::find()->with('category')->orderBy('category_id'),
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
        $model = new ConstructorProducts();

        if ($model->load(Yii::$app->request->post())) {
            $model->uploadImage();
            if ($model->save())
                return $this->redirect(['view', 'id' => $model->id]);
        }

    
        $categories = ConstructorCategories::find()->asArray()->all();

        return $this->render('create', [
            'model' => $model,
            'categories' => $categories,
        ]);
        
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            $model->uploadImage();
            if ($model->save())
                return $this->redirect(['view', 'id' => $model->id]);
        }

        $categories = ConstructorCategories::find()->asArray()->all();
        return $this->render('update', [
            'model' => $model,
            'categories' => $categories,
        ]);
    }

  
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = ConstructorProducts::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
