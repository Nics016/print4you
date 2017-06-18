<?php
namespace frontend\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\Response;
use common\models\CommonUser;
use common\models\Orders;
use common\models\Requests;
use common\models\Office;
use frontend\models\RequestCallForm;
use frontend\components\Basket;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post', 'get'],
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
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Рендерится при нажатии "Оформить" в корзине
     */
    public function actionNewOrder()
    {
        $model = new Orders();

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save())
                return $this->redirect(['site/order-created']);
            else 
                print_r($model->getErrors());
        } else {
            $model->delivery_required = true;
            $records = Office::Find()->all();
            $offices = [];
            foreach ($records as $record){
                $offices[(int)$record['id']] = $record['address'];
            }
            return $this->render('new-order', [
                'model' => $model,
                'offices' => $offices,
            ]);
        }
    }

    /**
     * Открывается после успешного создания заказа,
     * т.е. перенаправляется сюда из new-order
     */
    public function actionOrderCreated()
    {
        return $this->render('order-created');
    }

    /**
     * Отправка формы "заказать звонок" происходит сюда
     */
    public function actionRequestCallSent()
    {
        $model = new RequestCallForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $modelRequest = new Requests();
            $modelRequest->name = $model->name;
            $modelRequest->phone = $model->phone;
            $modelRequest->email = $model->email;
            $modelRequest->comment = $model->comment;
            $modelRequest->request_type = $model->form_type;
            $modelRequest->created_at = Yii::$app->formatter->asTimestamp(new \DateTime());
            if ($modelRequest->save()){

            } else 
                return $this->renderContent(var_dump($modelRequest->getErrors()));
            $msg = '<h2>Имя - ' . $model->name . '</h2>';
            $msg .= '<h2>Телефон - ' . $model->phone . '</h2>';
            $msg .= '<h2>Комментарий - ' . $model->comment . '</h2>';
            return $this->render('successful-request',[
                'name' => $model->name,
            ]);
        } else {
            return $this->render(['site/index']);
        }
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Личный кабинет
     */
    public function actionCabinet()
    {
        if (Yii::$app->user->isGuest)
            return $this->renderContent(Html::tag('h1','Войдите или зарегистрируйтесь, чтобы просматривать эту страницу'));

        $orders = Orders::find()
            ->where("client_id=" . Yii::$app->user->identity->id)
            ->all();

        $discountVal = CommonUser::getDiscount();

        return $this->render('cabinet',[
            'model' => Yii::$app->user->identity,
            'orders' => $orders,
            'discountVal' => $discountVal,
        ]);
    }

    /**
     * Оплата и доставка
     */
    public function actionDostavka()
    {
        return $this->render('dostavka');
    }

    /**
     * Франшиза
     */
    public function actionFranchise()
    {
        return $this->render('franchise');
    }

    /**
     * Калькулятор
     */
    public function actionCalculator()
    {
        return $this->render('calculator');
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            Basket::saveToDb(Yii::$app->user->identity->id);
            return $this->goBack();
        } else {
            return $this->render('index', [
                'loginError' => 'Неверный логин или пароль',
            ]);
        }
    }

    /**
     * Register action.
     *
     * @return string
     */
    public function actionRegister()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new CommonUser();
        $model->scenario = CommonUser::CREATE_SCENARIO;

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post())) {
            $model->generatePasswordHash($model['password']);
            $model->generateAuthKey();
            if ($model->save())
                return $this->redirect(['site/register-success']);
            else 
                print_r($model->getErrors());
        } else {
            return $this->render('register', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        $user_id = Yii::$app->user->identity->id;
        Yii::$app->user->logout();
        Basket::saveToSession($user_id);

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionContacts()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending your message.');
            }

            return $this->refresh();
        } else {
            return $this->render('contacts', [
                'model' => $model,
            ]);
        }
    }

    public function actionRegisterSuccess()
    {
        return $this->render('register-success');
    }

    /**
     * Displays about page.
     *
     * @return mixed
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    return $this->goHome();
                }
            }
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for the provided email address.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($id)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }
}
