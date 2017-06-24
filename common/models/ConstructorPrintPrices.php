<?php

namespace common\models;

use Yii;


class ConstructorPrintPrices extends \yii\db\ActiveRecord
{
    
    public $priceAttendances;

    public static function tableName()
    {
        return 'constructor_print_prices';
    }

   
    public function rules()
    {
        return [
            [['type_id', 'material_id', 'price', 'min_count'], 'required'],
            [['type_id', 'material_id', 'size_id', 'price', 'min_count', 'color'], 'integer'],
            ['gross_price', 'string'],
            ['gross_price', 'grossPriceValidate'],
            ['priceAttendances', 'attendancesValidate'],
        ];
    }

    // сохрание оптовой цени из json (приходит из админки)
    public function grossPriceValidate()
    {
        $array = json_decode($this->gross_price, true);
        $count = count($array);
        if ($count > 0) {

            for ($i = 0; $i < $count; $i++ ) {
                $item = $array[$i];
                $from = (int)$item['from'];
                $to = (int)$item['to'];
                $price = (int)$item['price'];

                if ($from < 1 || $to < 1 || $price < 1) {
                    $this->addError('gross_price', 'Одно из полей заполнено не верно');
                    return false;
                }
            }

            return true;
        }

        $this->addError('gross_price', 'Нет ни 1 цены');
        return false;
    }

    // валидация оптовой цены
    public function attendancesValidate()
    {
        $attendances = ConstructorPrintAttendance::find()->where(['id' => $this->priceAttendances])
                            ->asArray()->all();

        ConstructorPrintPriceAttendance::deleteAll(['price_id' => $this->id]);

        for ($i = 0; $i < count($attendances); $i++) {
            $model = new ConstructorPrintPriceAttendance();
            $model->price_id = $this->id;
            $model->attendance_id = $attendances[$i]['id'];
            $model->save();
        }
    }
    
    // для подсветки чекбоксов в редактировании цвета
    public function checkAttendaces() {
        $attendances = $this->attendances;
        $this->priceAttendances = [];

        for ($i = 0; $i < count($attendances); $i++)
            $this->priceAttendances[] =  $attendances[$i]->id;
    }


    public function getAttendances() 
    {   
        return $this->hasMany(ConstructorPrintAttendance::className(), ['id' => 'attendance_id'])
            ->viaTable('constructor_print_price_attendance', ['price_id' => 'id']);
    }

    public function getType()
    {
        return $this->hasOne(ConstructorPrintTypes::className(), ['id' => 'type_id']);
    }

    public function getMaterial()
    {
        return $this->hasOne(ConstructorProductMaterials::className(), ['id' => 'material_id']);
    }

    public function getSize()
    {
        return $this->hasOne(ConstructorPrintSizes::className(), ['id' => 'size_id']);
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type_id' => 'Тип печати',
            'material_id' => 'Материал товара',
            'size_id' => 'Размер печати',
            'price' => 'Розничная цена',
            'gross_price' => 'Оптовая цена',
            'min_count' => 'Минимальное количество товара',
            'color' => 'Цветность',
            'priceAttendances' => 'Дополнительные услуги',
        ];
    }


    public function beforeDelete() {
        parent::beforeDelete();
        ConstructorPrintPriceAttendance::deleteAll(['price_id' => $this->id]);

        return true;
    }
}
