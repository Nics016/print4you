<?php

namespace common\models;

use Yii;

class ConstructorAdditionalSides extends \yii\db\ActiveRecord
{
   
    public static function tableName()
    {
        return 'constructor_additional_sides';
    }

  
    public function rules()
    {
        return [
            [['name'], 'required',],
            [['name'], 'string', 'max' => 255],
        ];
    }


    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
        ];
    }

    public function beforeDelete()
    {
        parent::beforeDelete();
        set_time_limit(0);
        $models = ConstructorColorsSides::find()->where(['side_id' => $this->id])->all();
        foreach ($models as $model) {
            if (!$model->delete()) return false;
        }
        
        return true;
    }
}
