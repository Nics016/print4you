<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

use common\models\ConstructorProducts;
use common\models\ConstructorCategories;
use common\models\ConstructorColors;
use common\models\Orders;
use common\models\PagesSeo;

/**
 * Uslugi controller
 */
class UslugiController extends Controller
{   

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'load-more-assorty'  => ['post'],
                ],
            ],
        ];
    }

    /**
     * Услуги
     */
    public function actionIndex()
    {   
        $this->registerSeo(2, 'index');
        return $this->render('index', [
            'categories' => ConstructorCategories::getCats(), 
        ]);
    }

    /**
     * Ассортимент
     */
    public function actionAssorty()
    {   
        $offset = 0;
        $limit = 4;

        $content = ConstructorProducts::find()->orderBy('category_id')->limit($limit)
                        ->offset($offset)->asArray()->where(['is_published' => true])
                            ->with('colors')->all();

        $this->registerSeo(19);
        return $this->render("assorty", [
            'content' => $content,
            'groos_count' => Orders::GROSS_PRICE_PRODUCT_COUNT,
            'limit' => $limit,
            'offset' => $offset,
        ]);
    }

    public function actionConstructorCategory($cat_id = null)
    {   
        if ($cat_id === null) throw new NotFoundHttpException();
        $model = ConstructorCategories::find()->where(['id' => (int)$cat_id])
                ->with('categoryProducts')->asArray()->one();
        if (empty($model)) throw new NotFoundHttpException();
        return $this->render('constructor-category', ['model' => $model]);
    }

    public function actionLoadMoreAssorty()
    {   

        Yii::$app->response->format = Response::FORMAT_JSON;
        $limit = (int)Yii::$app->request->post('limit');
        $offset = (int)Yii::$app->request->post('offset');

        $content = ConstructorProducts::find()->orderBy('category_id')->limit($limit)
                        ->offset($offset)->asArray()->where(['is_published' => true])
                            ->with('colors')->all();

        if (count($content)) {
            $simple_html = '';
            $gross_html = '';
            $current_cat_id = null;

            for ($x = 0; $x < count($content); $x++) {
                $item = $content[$x];
                $colors = $item['colors'];
                $product_id = $item['id'];
                $name = $item['name'];
                $description = $item['description'];

                $id_tag = false;
                $cat_id = $item['category_id'];
                if ($current_cat_id === null || $cat_id != $current_cat_id) {
                    $current_cat_id = $cat_id;
                    $id_tag = 'cat-' . $cat_id;
                }

                for ($y = 0; $y < count($colors); $y++) {
                    $image = $colors[$y]['front_image'];
                    $price = $colors[$y]['price'];
                    $gross_price = json_decode($colors[$y]['gross_price'], true);
                    $gross_price = $gross_price[0]["price"];
                    $img_alt = $colors[$y]['img_alt'];

                    $simple_html .= $this->renderAjax('assorty_row', [
                        'product_id' => $product_id,
                        'name' => $name,
                        'image' => $image,
                        'count' => 1,
                        'price' => $price,
                        'description' => $description,
                        'id_tag' => $id_tag,
                        'alt' => $img_alt,
                    ]);

                    $gross_html .= $this->renderAjax('assorty_row', [
                        'product_id' => $product_id,
                        'name' => $name,
                        'image' => $image,
                        'count' => Orders::GROSS_PRICE_PRODUCT_COUNT,
                        'price' => $gross_price,
                        'description' => $description,
                        'id_tag' => $id_tag,
                        'alt' => $img_alt,
                    ]);
                }
            }

            return [
                'status' => 'ok',
                'gross_html' => $gross_html,
                'simple_html' => $simple_html,
            ];
        }
        return ['status' => 'fail']; 
    }

    /**
     * Термоперенос
     */
    public function actionTermoperenos()
    {
        $this->registerSeo(13, 'termoperenos');
        return $this->render('termoperenos');
    }

    /**
     * Сублимация
     */
    public function actionSublimation()
    {
        $this->registerSeo(15, 'sublimation');
        return $this->render('sublimation');
    }

    /**
     * Цифровая печать
     */
    public function actionCifrovaya()
    {
        $this->registerSeo(12, 'cifrovaya');
        return $this->render('cifrovaya');
    }

	/**
	 * Шелкография
	 */
	public function actionShelkography()
	{
        $this->registerSeo(14, 'shelkography');
		return $this->render('shelkography');
	}


    /* Технологии и цены */
    public function actionTechnologiiICeny()
    {
        $this->registerSeo(26, 'technologii-i-ceny');
        return $this->render('technologii-i-ceny');
    }

    public function actionTekstil()
    {
        $this->registerSeo(27, 'tekstil');
        return $this->render('tekstil', [
            'categories' => ConstructorCategories::getCats(),
            'skip_id' => 49, // айди категории кружек
        ]);
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