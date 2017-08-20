<?php 

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\web\NotFoundHttpException;

use common\models\PagesSeo;

class InfoController extends Controller 
{
	public function actionZa15Minut()
	{
		$view_name = 'za-15-minut';
		$this->registerSeo(20, $view_name);
		return $this->render($view_name);
	}

	public function actionFutbolkiOptom()
	{
		$view_name = 'futbolki-optom';
		$this->registerSeo(21, $view_name);
		return $this->render($view_name);
	}

	public function actionDeshevoPrint4you()
	{
		$view_name = 'deshevo-print4you';
		$this->registerSeo(22, $view_name);
		return $this->render($view_name);
	}

	public function actionNedorogo()
	{
		$view_name = 'nedorogo';
		$this->registerSeo(23, $view_name);
		return $this->render($view_name);
	}

	public function actionSertifikat()
	{
		$view_name = 'sertifikat';
		$this->registerSeo(23, $view_name);
		return $this->render($view_name);
	}

	public function actionOborudovanie()
	{
		$view_name = 'oborudovanie';
		$this->registerSeo(24, $view_name);
		return $this->render($view_name);
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