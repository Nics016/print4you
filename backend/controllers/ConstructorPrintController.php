<?php 

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\filters\VerbFilter;

use backend\models\User;
use common\components\AccessRule;
use yii\filters\AccessControl;

use common\models\ConstructorPrintSizes;
use common\models\ConstructorPrintTypes;
use common\models\ConstructorPrintAttendance;

class ConstructorPrintController extends Controller 
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
                    'save-size' => ['POST'],
                    'remove-size' => ['POST'],
                ],
            ],
        ];
    }


    public function actionIndex()
    {	
    	$print_sizes = ConstructorPrintSizes::find()->asArray()->all();
        $print_types = ConstructorPrintTypes::find()->asArray()->all();
        $attendances = ConstructorPrintAttendance::find()->asArray()->all();

    	return $this->render('index', [
            'print_sizes' => $print_sizes,
            'print_types' => $print_types,
    		'attendances' => $attendances,
    	]);
    }

    // сохранение размера печати
    public function actionSaveSize()
    {
    	Yii::$app->response->format = Response::FORMAT_JSON;
    	$id = Yii::$app->request->post('id');
    	$name = Yii::$app->request->post('name');
    	$percent = (int)Yii::$app->request->post('percent');

    	if ($id == 'new') {
    		$model = new ConstructorPrintSizes();
    	} else {
    		$model = ConstructorPrintSizes::findOne(['id' => (int)$id]);
    		if ($model == null) return ['status' => 'fail'];
    	}

    	$model->name = $name;
    	$model->percent = $percent;

    	if ($model->save()) 
			return ['status' => 'ok', 'id' => $model->getPrimaryKey()];
		else
			return ['status' => 'fail'];

    }

    // удаление размера печати
    public function actionRemoveSize()
    {
    	Yii::$app->response->format = Response::FORMAT_JSON;
    	$id = (int)Yii::$app->request->post('id');

    	$model = ConstructorPrintSizes::findOne(['id' => $id]);
    	
    	if ($model == null) return ['status' => 'fail'];

    	return $model->delete() ? ['status' => 'ok'] : ['status' => 'fail'];
    }

    // сохранение типа
    public function actionSaveType()
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;

            $id = Yii::$app->request->post('id');
            $name = Yii::$app->request->post('name');

            if ($id == 'new') {
                $model = new ConstructorPrintTypes();
            } else {
                $model = ConstructorPrintTypes::findOne(['id' => (int)$id]);
                if ($model == null) return ['status' => 'fail'];
            }

            $model->name = $name;
            return $model->save() ? ['status' => 'ok', 'id' => $model->getPrimaryKey()] : ['status' => 'fail'];
        }

        throw new NotFoundHttpException();
    }



    // удаление типа
    public function actionRemoveType()
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;

            $id = (int)Yii::$app->request->post('id');

            $model = ConstructorPrintTypes::findOne(['id' => $id]);
            if ($model == null) return ['status' => 'fail'];
            
            return $model->delete() ? ['status' => 'ok'] : ['status' => 'fail'];
        }

        throw new NotFoundHttpException();
    }


    // сохранение услуги
    public function actionSaveAttendance()
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;

            $id = Yii::$app->request->post('id');
            $name = Yii::$app->request->post('name');
            $percent = (int)Yii::$app->request->post('percent');

            if ($id == 'new') {
                $model = new ConstructorPrintAttendance();
            } else {
                $model = ConstructorPrintAttendance::findOne(['id' => (int)$id]);
                if ($model == null) return ['status' => 'fail'];
            }

            $model->name = $name;
            $model->percent = $percent;
            return $model->save() ? ['status' => 'ok', 'id' => $model->getPrimaryKey()] : ['status' => 'fail'];
        }

        throw new NotFoundHttpException();
    }



    // удаление типа
    public function actionRemoveAttendance()
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;

            $id = (int)Yii::$app->request->post('id');

            $model = ConstructorPrintAttendance::findOne(['id' => $id]);
            if ($model == null) return ['status' => 'fail'];
            
            return $model->delete() ? ['status' => 'ok'] : ['status' => 'fail'];
        }

        throw new NotFoundHttpException();
    }
}