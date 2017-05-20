<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * Uslugi controller
 */
class UslugiController extends Controller
{
    /**
     * Услуги
     */
    public function actionIndex()
    {
        return $this->render("index");
    }

    /**
     * Ассортимент
     */
    public function actionAssorty()
    {
        return $this->render("assorty");
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