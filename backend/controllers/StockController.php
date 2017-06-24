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
                        // Allow only executor
                        'roles' => [
                            User::ROLE_ADMIN,
                            User::ROLE_MANAGER,
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

    /**
     * Lists all StockColors models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProviderColors = new ActiveDataProvider([
            'query' => StockColors::find(),
        ]);
        $dataProviderItems = new ActiveDataProvider([
            'query' => ConstructorStorage::find(),
        ]);

        return $this->render('index', [
            'dataProviderColors' => $dataProviderColors,
            'dataProviderItems' => $dataProviderItems,
        ]);
    }
}
