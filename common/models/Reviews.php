<?php

namespace common\models;

use Yii;

use yii\widgets\ActiveForm;


class Reviews extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'reviews';
    }

   
    public function rules()
    {
        return [
            [['user_id', 'text', 'created_at'], 'required'],
            [['user_id'], 'integer'],
            [['text'], 'string', 'min' => 4],
            [['is_like', 'is_published'], 'boolean'],
            [['created_at'], 'safe'],
        ];
    }


    public static function addReview($text, $is_like)
    {
        if (Yii::$app->user->isGuest) return ['status' => 'login'];

        // проверим, может у пользователя находится что то на мадериции
        $user_id = Yii::$app->user->identity->id;
        if (self::find()->where(['user_id' => $user_id, 'is_published' => false])->exists())
            return ['status' => 'moder'];

        // иначе все ок, создаем
        date_default_timezone_set('Europe/Moscow');

        $model = new self();
        $model->text = $text;
        $model->is_like = $is_like ? true : false;
        $model->user_id = $user_id;
        $model->is_published = false;
        $model->created_at = date('Y-m-d H:i:s', time());
        return $model->save() ? ['status' => 'ok'] : ['status' => 'fail', 'af' => ActiveForm::validate($model)];
    }

    public function getUser()
    {
        return $this->hasOne(CommonUser::className(), ['id' => 'user_id']);
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'text' => 'Текст',
            'is_like' => 'Оценка',
            'created_at' => 'Created At',
            'is_published' => 'Опубликовать?',
        ];
    }
}
