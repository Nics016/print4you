<?php

namespace backend\controllers;

use Yii;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

use common\models\ConstructorCategories;
use common\models\ConstructorSizes;

use yii\widgets\ActiveForm;

class ConstructorCategoriesSizesController extends Controller
{  

    public $layout = 'adminPanel';

    public function actionIndex() {

        // возьмем все категории и размеры
        $categories = ConstructorCategories::find()->asArray()->orderBy('sequence')->all();
        $sizes = ConstructorSizes::find()->asArray()->orderBy('sequence')->all();

        return $this->render('index', [
            'categories' => $categories,
            'sizes' => $sizes,
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

        } else {
            throw new NotFoundHttpException();
        }
    }

    // удаление категорий
    public function actionRemoveCategory() {
        if (Yii::$app->request->isAjax) {

            Yii::$app->response->format = Response::FORMAT_JSON;
            $cat_id = Yii::$app->request->post('id');

            $model = ConstructorCategories::find()->where(['id' => +$cat_id])->one();

            if ($model == null) return ['response' => false];

            return ['response' => $model->delete()];


        } else {
            throw new NotFoundHttpException();
        }
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

        } else {
            throw new NotFoundHttpException();
        }
    }

    // удаление размеров
    public function actionRemoveSize() {
        if (Yii::$app->request->isAjax) {

            Yii::$app->response->format = Response::FORMAT_JSON;
            $size_id = Yii::$app->request->post('id');

            $model = ConstructorSizes::find()->where(['id' => +$size_id])->one();

            if ($model == null) return ['response' => false];

            return ['response' => $model->delete()];


        } else {
            throw new NotFoundHttpException();
        }
    }

}
