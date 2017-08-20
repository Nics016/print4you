<?php 

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\web\NotFoundHttpException;

use yii\data\Pagination;

use common\models\Reviews;
use common\models\PagesSeo;

class ReviewsController extends Controller
{
	public function actionIndex()
	{	
		$query = Reviews::find()->where(['is_published' => true])->orderBy('id DESC')->asArray()->with('user');
		$clone = clone $query;
		$pages = new Pagination([
			'totalCount' => $clone->count(),
			'pageSize' => 10,
			'pageSizeParam' => false,
			'forcePageParam' => false,
		]);
		$reviews = $query->offset($pages->offset)->limit($pages->limit)->all();
		$this->registerSeo(5, 'index');
		return $this->render('index', compact('pages', 'reviews'));
	}

	public function actionAddReview()
	{
		if (Yii::$app->request->isPost && Yii::$app->request->isAjax) {
			Yii::$app->response->format = Response::FORMAT_JSON;

			$is_like = (int)Yii::$app->request->post('is_like');
			$text = Yii::$app->request->post('text');
			
			return Reviews::addReview($text, $is_like);
		}

		throw new NotFoundHttpException();
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