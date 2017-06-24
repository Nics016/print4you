<?php

namespace common\models;

use Yii;

class ConstructorStorage extends \yii\db\ActiveRecord
{
   
    public static function tableName()
    {
        return 'constructor_storage';
    }


    public function rules()
    {
        return [
            [['color_id', 'size_id', 'office_id'], 'required'],
            [['color_id', 'size_id', 'office_id', 'count'], 'integer'],
        ];
    }

    // используется в backend при редактирвоании склада конструктора
    public static function setData($color_id, $size_id, $office_id, $count) 
    {   
        if ($count < 0) return false;

        // провверим, существует ли такая связка цвета и размера
        $condition = ConstructorColorSizes::find()->where(['size_id' => $size_id, 'color_id' => $color_id])->exists();

        if (!$condition) return false;

        $model = self::find()->where(['color_id' => $color_id, 'size_id' => $size_id,'office_id' => $office_id])
                        ->limit(1)->one();

        if ($model == null) {
            $model = new self();
            $model->color_id = $color_id;
            $model->size_id = $size_id;
            $model->office_id = $office_id;
        }

        $model->count = $count;

        return $model->save();
    }


    public function getOffice()
    {
        return $this->hasOne(Office::className(), ['id' => 'office_id']);
    }

    public function getSize()
    {
        return $this->hasOne(ConstructorSizes::className(), ['id' => 'size_id']);
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'color_id' => 'Цвет товара',
            'size_id' => 'Размер',
            'office_id' => 'ID Офиса',
            'count' => 'Количество',
        ];
    }
}
