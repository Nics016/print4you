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

    	return $this->render('index', [
    		'print_sizes' => $print_sizes,
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

}