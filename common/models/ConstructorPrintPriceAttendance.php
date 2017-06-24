<?php

namespace common\models;

use Yii;


class ConstructorPrintPriceAttendance extends \yii\db\ActiveRecord
{
    
    public static function tableName()
    {
        return 'constructor_print_price_attendance';
    }

    public function rules()
    {
        return [
            [['price_id', 'attendance_id'], 'required'],
            [['price_id', 'attendance_id'], 'integer'],
        ];
    }

   
    public function attributeLabels()
    {
        return [
            'price_id' => 'Price ID',
            'attendance_id' => 'Attendance ID',
        ];
    }
}
