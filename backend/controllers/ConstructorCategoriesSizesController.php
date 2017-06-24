<?php

namespace backend\controllers;

use Yii;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

use common\models\ConstructorCategories;
use common\models\ConstructorSizes;
use common\models\ConstructorProductMaterials;

use yii\widgets\ActiveForm;

use backend\models\User;
use common\components\AccessRule;
use yii\filters\AccessControl;

class ConstructorCategoriesSizesController extends Controller
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
        ];
    }

    public function actionIndex() {

        // возьмем все категории и размеры
        $categories = ConstructorCategories::find()->asArray()->orderBy('sequence')->all();
        $sizes = ConstructorSizes::find()->asArray()->orderBy('sequence')->all();
        $materials = ConstructorProductMaterials::find()->asArray()->all();

        return $this->render('index', [
            'categories' => $categories,
            'sizes' => $sizes,
            'materials' => $materials,
        ]);
    }

    // изменение категорий
    public function actionChangeCategories() {
        if (Yii::$app->request->isAjax) {

            Yii::$app->response->format = Response::FORMAT_JSON;

            $data = Yii::$app->request->post('data');
            $new_ids = [];

            for ($i = 0; $i < count($data); $i++) {

                $id = $data[$i]['id'];
                $value = $data[$i]['value'];

                // если нужен новый размер
                if ($id == 'new') {
                    $model = new ConstructorCategories();
                    $model->name = $value;
                    $model->sequence = $i;

                    if (!$model->save())
                        return ['response' => false];

                    $new_ids[] = $model->getPrimaryKey();

                } else {

                    // если нужно изменить старый
                    $model = ConstructorCategories::find()->where(['id' => +$id])->one();

                    if ($model == null) return ['response' => false];

                    $model->name = $value;
                    $model->sequence = $i;

                    if (!$model->save())
                        return ['response' => false];
                }
            }

            return ['response' => true, 'new' => $new_ids];

        } 
        
        throw new NotFoundHttpException();
    }

    // удаление категорий
    public function actionRemoveCategory() {
        if (Yii::$app->request->isAjax) {

            Yii::$app->response->format = Response::FORMAT_JSON;
            $cat_id = Yii::$app->request->post('id');

            $model = ConstructorCategories::find()->where(['id' => +$cat_id])->one();

            if ($model == null) return ['response' => false];

            return ['response' => $model->delete()];


        } 
        
        throw new NotFoundHttpException();
    }

    // измененеие размеров
    public function actionChangeSizes() {
        if (Yii::$app->request->isAjax) {

            Yii::$app->response->format = Response::FORMAT_JSON;

            $data = Yii::$app->request->post('data');
            $new_ids = [];

            for ($i = 0; $i < count($data); $i++) {

                $id = $data[$i]['id'];
                $size = $data[$i]['size'];

                if ($id == 'new') {
                    $model = new ConstructorSizes();
                    $model->size = $size;
                    $model->sequence = $i;

                    if (!$model->save())
                        return ['response' => false];

                    $new_ids[] = $model->getPrimaryKey();

                } else {
                    $model = ConstructorSizes::find()->where(['id' => +$id])->one();

                    if ($model == null) return ['response' => false];

                    $model->size = $size;
                    $model->sequence = $i;

                    if (!$model->save())
                        return ['response' => false];
                }
            }

            return ['response' => true, 'new' => $new_ids];

        } 

        throw new NotFoundHttpException();
    }

    // удаление размеров
    public function actionRemoveSize() {
        if (Yii::$app->request->isAjax) {

            Yii::$app->response->format = Response::FORMAT_JSON;
            $size_id = Yii::$app->request->post('id');

            $model = ConstructorSizes::find()->where(['id' => +$size_id])->one();

            if ($model == null) return ['response' => false];

            return ['response' => $model->delete()];


        } 
            
        throw new NotFoundHttpException();
    }


    // сохранение материала
    public function actionSaveMaterial()
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;

            $id = Yii::$app->request->post('id');
            $name = Yii::$app->request->post('name');

            if ($id == 'new') {
                $model = new ConstructorProductMaterials();
            } else {
                $model = ConstructorProductMaterials::findOne(['id' => (int)$id]);
                if ($model == null) return ['status' => 'fail'];
            }

            $model->name = $name;
            return $model->save() ? ['status' => 'ok', 'id' => $model->getPrimaryKey()] : ['status' => 'fail'];
        }

        throw new NotFoundHttpException();
    }



    // удаление материала
    public function actionRemoveMaterial()
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;

            $id = (int)Yii::$app->request->post('id');

            $model = ConstructorProductMaterials::findOne(['id' => $id]);
            if ($model == null) return ['status' => 'fail'];
            
            return $model->delete() ? ['status' => 'ok'] : ['status' => 'fail'];
        }

        throw new NotFoundHttpException();
    }

}
