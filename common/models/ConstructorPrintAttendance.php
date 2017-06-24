<?php

namespace common\models;

use Yii;


class ConstructorPrintAttendance extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return 'constructor_print_attendance';
    }

    public function rules()
    {
        return [
            [['name', 'percent'], 'required'],
            [['percent'], 'integer', 'min' => 1, 'max' => 100],
            [['name'], 'string', 'max' => 255],
        ];
    }

  
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'percent' => 'Percent',
        ];
    }

    public function beforeDelete() {
        parent::beforeDelete();
        ConstructorPrintPriceAttendance::deleteAll(['attendance_id' => $this->id]);

        return true;
    }
}
