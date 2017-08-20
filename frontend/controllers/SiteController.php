<?php
namespace frontend\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use frontend\models\LoginForm;
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
use common\models\ConstructorCategories;
use common\models\PagesSeo;
use frontend\models\RequestCallForm;
use frontend\models\ForgotPassword;
use frontend\components\basket\Basket;
use frontend\components\Sms;

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
            if ($modelRequest->save()) {
                $msg = '<h2>Имя - ' . $model->name . '</h2>';
                $msg .= '<h2>Телефон - ' . $model->phone . '</h2>';
                $msg .= '<h2>Комментарий - ' . $model->comment . '</h2>';
                return $this->render('successful-request',[
                    'name' => $model->name,
                ]);
            }
            
        }

        $this->registerSeo(1);
        return $this->render(['site/index']);
        
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {   
        $this->registerSeo(1, 'index');

        return $this->render('index', [
            'categories' => ConstructorCategories::getCats(),
        ]);
    }

    /**
     * Личный кабинет
     */
    public function actionCabinet()
    {   
        $this->registerSeo(18);
        if (Yii::$app->user->isGuest) {
            return $this->renderContent(Html::tag('h1','Войдите или зарегистрируйтесь, чтобы просматривать эту страницу'));
        }

        $orders = Orders::find()
            ->where("client_id=" . Yii::$app->user->identity->id)
            ->all();

        $discountVal = CommonUser::getDiscount();
        $discountGrossVal = CommonUser::getDiscount(20);

        return $this->render('cabinet',[
            'model' => Yii::$app->user->identity,
            'orders' => $orders,
            'discountVal' => $discountVal,
            'discountGrossVal' => $discountGrossVal,
        ]);
    }

    /**
     * Проверка email или телефона в редакторе личного кабинета
     */
    public function actionCheckUserData()
    {
        if (Yii::$app->request->isPost && Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $action = Yii::$app->request->post('action');
            $value = Yii::$app->request->post('value');

            $check = false;
            if ($action == 'email')
                $check = CommonUser::checkEmail($value);
            elseif ($action == 'phone')
                $check = CommonUser::checkPhone($value);

            return [
                'status' => $check ? 'ok' : 'fail',
            ];
        }

        throw new NotFoundHttpException();
    }

    /**
     * Изменение пароля в личном кабинете
     */

    public function actionChangeUserPassword() 
    {
        if (Yii::$app->request->isPost && Yii::$app->request->isAjax && !Yii::$app->user->isGuest) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $old = Yii::$app->request->post('old_password'); 
            $new = Yii::$app->request->post('new_password'); 

            $model = &Yii::$app->user->identity;

            if (!$model->validatePassword($old)) {
                return [
                    'status' => 'fail',
                    'field' => 'old',
                    'message' => 'Неверный пароль!',
                ];
            }

            $model->password = $new;

            if (!$model->validate(['password'])) {
                return [
                    'status' => 'fail',
                    'field' => 'new',
                    'message' => 'Неверный пароль!',
                ];
            }
            $model->generatePasswordHash($new);
            if (!$model->save(false)) {
                return [
                    'status' => 'fail',
                    'field' => 'new',
                    'message' => 'Не удалось сохранить пароль!',
                ];
            }
            return ['status' => 'ok'];

        }
        
        throw new NotFoundHttpException();
    }

    /**
     * Изменяет данные в редакторе кабинета
     */
    public function actionChangeUserData()
    {
        if (Yii::$app->request->isPost && Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'status' => CommonUser::ajaxChangeUserData() ? 'ok' : 'fail',
            ];
        }

        throw new NotFoundHttpException();
    }

    /**
     * Оплата и доставка
     */
    public function actionDostavka()
    {   
        $this->registerSeo(3, 'dostavka');
        return $this->render('dostavka');
    }

    /**
     * Франшиза
     */
    public function actionFranchise()
    {   
        $this->registerSeo(6, 'franchise');
        return $this->render('franchise');
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {   

        if (Yii::$app->request->isAjax && Yii::$app->request->isPost && Yii::$app->user->isGuest) {

            Yii::$app->response->format = Response::FORMAT_JSON;

            $model = new LoginForm();
            $model->phone = Yii::$app->request->post('phone');
            $model->password = Yii::$app->request->post('password');
            $model->rememberMe = (boolean)Yii::$app->request->post('rememberMe');

            if ($model->login()) {
                Basket::saveToDb(Yii::$app->user->identity->id);
                return ['status' => 'ok'];
            }

            return ['status' => 'fail'];
        }

        throw new NotFoundHttpException();

        
    }

    /**
     * Register action.
     *
     * @return string
     */
    public function actionRegister($redirect = false)
    {   
        $this->registerSeo(16, 'register');
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        
        $model = new CommonUser();
        $model->scenario = CommonUser::CREATE_SCENARIO;

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post()) && $model->calculatePhone()) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post())) {
            $model->generatePasswordHash($model['password']);
            $model->generateAuthKey();

            if ($model->calculatePhone() && $model->save()) {
                if ($redirect === false) 
                    return $this->redirect(['site/register-success']);
                else
                    return $this->redirect($redirect);
            }

        } else {
            return $this->render('register', [
                'model' => $model,
            ]);
        }
    }

    // воостановление пароля
    public function actionForgotPassword()
    {
        if (!Yii::$app->user->isGuest) return $this->goHome();

        if (Yii::$app->request->isAjax && Yii::$app->request->isPost) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ForgotPassword::ajaxLoad();
        }

        return $this->render('forgot-password');
    }

    // редактирование профиля
    /*public function actionEditProfile($id = -1)
    {
        if (Yii::$app->user->isGuest || $id < 0) {
            return $this->goHome();
        }

        $model = CommonUser::findOne(['id' => $id]);

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post())) {
            if ($model['password'] != ''){
                $model->generatePasswordHash($model['password']);
                $model->generateAuthKey();
            }
            if ($model->save())
                return $this->redirect(['site/cabinet']);
            else 
                print_r($model->getErrors());
        } else {
            return $this->render('edit-profile', [
                'model' => $model,
            ]);
        }
    }*/

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
        $this->registerSeo(7, 'contacts');
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
        $this->registerSeo(10, 'about');
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

    // страница акций
    public function actionSale()
    {   
        $this->registerSeo(8, 'sale');
        return $this->render('sale');
    }

    // страница наших гостей
    public function actionNashiGosti()
    {   
        $this->registerSeo(9, 'nashi-gosti');
        return $this->render('nashi-gosti');
    }

    public function actionNashiClienty()
    {   
        $this->registerSeo(25, 'nashi-clienty');
        return $this->render('nashi-clienty');
    }

    // регистрирует сео пола
    private function registerSeo($page_id, $view_name = null) 
    {
        $seo = PagesSeo::findOne(['page_id' => $page_id]);
        Yii::$app->view->title = $seo->title ?? '';

        Yii::$app->view->registerMetaTag([
            'name' => 'title',
            'content' => $seo->title ?? '',
        ]);

        Yii::$app->view->registerMetaTag([
            'name' => 'description',
            'content' => $seo->description ?? '',
        ]);

        Yii::$app->view->registerMetaTag([
            'name' => 'keywords',
            'content' => $seo->keywords ?? '',
        ]);

        // добавим заголовк последней модификации исходя из редактирования файлов
        if ($view_name === null) return;
        
        $path = $this->getViewPath() . '/' . $view_name . '.php';
        if (file_exists($path)) {

            $mt = filemtime($path);
            $mt_str = gmdate('D, d M Y H:i:s', $mt).' GMT';
            if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) 
                    && strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']) >= $mt)
                header('HTTP/1.1 304 Not Modified');
            else
                header('Last-Modified: '.$mt_str);

        } 
    }
}
