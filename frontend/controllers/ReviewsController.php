<?php 

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\web\NotFoundHttpException;

use yii\data\Pagination;

use common\models\Reviews;

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
}