<?php

namespace common\models;

use Yii;


class ConstructorPrintSizes extends \yii\db\ActiveRecord
{
  
    public static function tableName()
    {
        return 'constructor_print_sizes';
    }

    public function rules()
    {
        return [
            [['name', 'percent'], 'required'],
            [['percent'], 'integer', 'min' => 1, 'max' => 100],
            [['name'], 'string', 'max' => 10],
        ];
    }

 
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название',
            'percent' => 'Процент от области принта',
        ];
    }
}
