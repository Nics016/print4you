<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;

use common\components\AccessRule;
use backend\models\User;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * @property string $layout 
     *
     * По умолчанию стоит значение 'adminPanel'.
     * В действиях Login, Logout и внутри view Error стоит $layout = 'main',
     * так как эти страницы доступны неавторизованным пользователям.
     */
    public $layout = 'adminPanel';
    
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
                        'actions' => ['login', 'error', 'can'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['index', 'test', 'logout'],
                        'allow' => true,
                        // Allow managers and admin
                        'roles' => [
                            User::ROLE_MANAGER,
                            User::ROLE_ADMIN
                        ],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Displays test page.
     */
    public function actionTest()
    {
        return $this->renderContent("<h1>Вот так будет отображаться</h1> <h4>контент, находящийся в \$content</h4>");
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        $this->layout = 'main';
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        $this->layout = 'main';
        Yii::$app->user->logout();

        return $this->goHome();
    }
}
