<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

use common\models\ConstructorProducts;
use common\models\ConstructorCategories;
use common\models\ConstructorColors;
use common\models\Orders;

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

        return $this->render("index", [
            'categories' => ConstructorCategories::getCats(), 
        ]);
    }

    /**
     * Ассортимент
     */
    public function actionAssorty()
    {   
        $offset = 0;
        $limit = 10;

        $content = ConstructorProducts::find()->orderBy('category_id')->limit($limit)
                        ->offset($offset)->asArray()->with('firstColor')->all();

        return $this->render("assorty", [
            'content' => $content,
            'groos_count' => Orders::GROSS_PRICE_PRODUCT_COUNT,
            'limit' => $limit,
            'offset' => $offset,
        ]);
    }

    public function actionLoadMoreAssorty()
    {   

        Yii::$app->response->format = Response::FORMAT_JSON;
        $limit = (int)Yii::$app->request->post('limit');
        $offset = (int)Yii::$app->request->post('offset');

        $content = ConstructorProducts::find()->orderBy('category_id')->limit($limit)
                        ->offset($offset)->asArray()->with('firstColor')->all();

        if (count($content)) {
            $simple_html = '';
            $gross_html = '';
            for ($i = 0; $i < count($content); $i++) {
                $item = $content[$i];
                $simple_html .= $this->renderAjax('assorty_row', [
                    'product_id' => $item['id'],
                    'name' => $item['name'],
                    'image' => $item['firstColor']['image'],
                    'count' => 1,
                    'price' => $item['firstColor']['price'],
                    'description' => $item['description'],
                ]);

                $gross_price = json_decode($item['firstColor']['gross_price'], true);
                $gross_price = $gross_price[0]["price"];
                $gross_html .= $this->renderAjax('assorty_row', [
                    'product_id' => $item['id'],
                    'name' => $item['name'],
                    'image' => $item['firstColor']['image'],
                    'count' => 20,
                    'price' => $gross_price,
                    'description' => $item['description'],
                ]);
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
        return $this->render("termoperenos");
    }

    /**
     * Сублимация
     */
    public function actionSublimation()
    {
        return $this->render("sublimation");
    }

    /**
     * Цифровая печать
     */
    public function actionCifrovaya()
    {
        return $this->render("cifrovaya");
    }

	/**
	 * Шелкография
	 */
	public function actionShelkography()
	{
		return $this->render("shelkography");
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
}