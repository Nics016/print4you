<?php

namespace backend\controllers;

use Yii;
use backend\models\StockColors;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\ConstructorStorage;

use backend\models\User;
use common\components\AccessRule;
use yii\filters\AccessControl;

/**
 * StockColorsController implements the CRUD actions for StockColors model.
 */
class StockController extends Controller
{
    /**
     * @inheritdoc
     */
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
                        'roles' => [
                            User::ROLE_ADMIN,
                            User::ROLE_MANAGER,
                            User::ROLE_EXECUTOR,
                        ],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST', 'GET'],
                ],
            ],
        ];
    }

    /**
     * Lists all StockColors models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProviderColors = new ActiveDataProvider([
            'query' => StockColors::find()->orderBy('office_id'),
        ]);
        $dataProviderItems = new ActiveDataProvider([
            'query' => ConstructorStorage::find()->orderBy('office_id'),
        ]);

        return $this->render('index', [
            'dataProviderColors' => $dataProviderColors,
            'dataProviderItems' => $dataProviderItems,
        ]);
    }

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['stock/index']);
    }

    /**
     * Finds the StockRequests model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return StockRequests the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ConstructorStorage::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
