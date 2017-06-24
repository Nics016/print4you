<?php

namespace common\models;

use Yii;

class ConstructorProductMaterials extends \yii\db\ActiveRecord
{
   
    public static function tableName()
    {
        return 'constructor_product_materials';
    }

   
    public function rules()
    {
        return [
            [['name'], 'required'],
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
}
