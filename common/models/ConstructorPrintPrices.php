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
            ['priceAttendances', 'safe'],
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
    public function setAttendances()
    {   

        ConstructorPrintPriceAttendance::deleteAll(['price_id' => $this->id]);

        if (!empty($this->priceAttendances)) {
            $attendances = ConstructorPrintAttendance::find()->where(['id' => $this->priceAttendances])
                            ->asArray()->all();

            for ($i = 0; $i < count($attendances); $i++) {
                $model = new ConstructorPrintPriceAttendance();
                $model->price_id = $this->id;
                $model->attendance_id = $attendances[$i]['id'];
                $model->save();
            }
        }
        
        return true;
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


    public static function getPriceData($material_id, $size_id, $count, $color = null)
    {
        if ($color == null) {
            $model = self::find()->where('material_id = :material_id AND size_id = :size_id AND min_count <= :count', [
                    ':material_id' => $material_id,
                    ':size_id' => $size_id,
                    ':count' => $count,
                ])->asArray()->one();
        } else {
            $model = self::find()
                ->where('material_id = :material_id AND size_id = :size_id AND min_count <= :count AND color = :color', [
                    ':material_id' => $material_id,
                    ':size_id' => $size_id,
                    ':count' => $count,
                    ':color' => $color,
                ])->asArray()->one();
        }

        return $model == false ? [] : $model;
    }

    // доступный тип печати для принта
    public static function getPrintType($material_id, $size_id, $count, $type_id) 
    {
        $sql = 'material_id = :material_id AND size_id = :size_id 
                AND min_count <= :count AND type_id = :type_id';
        $model = self::find()->where($sql, [
                                ':material_id' => $material_id,
                                ':size_id' => $size_id,
                                ':count' => $count,
                                ':type_id' => $type_id,
                            ])->asArray()->one();
        return $model;
    }

    // доступные цвет для принта
    public static function getPrintColor($material_id, $size_id, $count, $type_id, $color) 
    {
        $sql = 'material_id = :material_id AND size_id = :size_id 
                AND min_count <= :count AND type_id = :type_id AND color = :color';
        $model = self::find()->where($sql, [
                                ':material_id' => $material_id,
                                ':size_id' => $size_id,
                                ':count' => $count,
                                ':type_id' => $type_id,
                                ':color' => $color,
                            ])->asArray()->one();
        return $model;
    }

    // доступная услуга для принта
    public static function getPrintAttendance($material_id, $size_id, $count, $type_id, $color = null, $attendance_id)
    {   
        if ($color != null) {
            $sql = 'material_id = :material_id AND size_id = :size_id 
                AND min_count <= :count AND type_id = :type_id AND color = :color';
            $price = self::find()->where($sql, [
                                    ':material_id' => $material_id,
                                    ':size_id' => $size_id,
                                    ':count' => $count,
                                    ':type_id' => $type_id,
                                    ':color' => $color,
                                ])->limit(1)->one();
        } else {
            // поменяем sql запрос
            $sql = 'material_id = :material_id AND size_id = :size_id AND min_count <= :count 
                AND type_id = :type_id AND color is NULL';
            $price = self::find()->where($sql, [
                                        ':material_id' => $material_id,  
                                        ':size_id' => $size_id,  
                                        ':count' => $count,  
                                        ':type_id' => $type_id,  
                                    ])->limit(1)->one();
        }
        

        if ($price == false) return false;

        $expression = ConstructorPrintPriceAttendance::find()
                        ->where(['price_id' => $price->id, 'attendance_id' => $attendance_id])->exists();
        if ($expression)
            return ConstructorPrintAttendance::find()->where(['id' => $attendance_id])->asArray()->one();

        return false;
    }

    // находит доступные метода печати, цветности и услуги
    public static function getAvaliablePrices($material_id, $size_id,  $count, $type_id, $color = null) {
        $array = [
            'types' => [],
            'colors' => [],
            'attendances' => [],
        ];
        
        // запишем типы
        $array['types'] = self::getAvaliableTypes($material_id, $size_id, $count);
        if (empty($array['types'])) return $array;

        // запишем доступные цветности
        $array['colors'] = self::getAvaliableColors($material_id, $size_id, $count, $type_id, $color);

        // запишем доступные доп услуги
        $array['attendances'] = self::getAvaliableAttendances($material_id, $size_id, $count, $type_id, $color);


        return $array;
    }

    // ищет доступные типы
    public static function getAvaliableTypes($material_id, $size_id, $count) {
        $array = [];
        $types = self::find()->select('type_id')
                    ->where('material_id = :material_id AND size_id = :size_id AND min_count <= :count', [
                                    ':material_id' => $material_id,  
                                    ':size_id' => $size_id,  
                                    ':count' => $count,  
                                ])->groupBy('type_id')->asArray()->with('type')->all();
        // переформируем массив
        for ($i = 0; $i < count($types); $i++) {
            $array[] = [
                'id' => $types[$i]['type']['id'],
                'name' => $types[$i]['type']['name'],
            ];
        }

        return $array;
    }

    // ищет досутпные цветности
    public static function getAvaliableColors($material_id, $size_id, $count, $type_id, $color = null)
    {   
        $array = [];
        // если цветность не передана в функцию, значит их и нет
        if ($color !== null) {
            $sql = 'material_id = :material_id AND size_id = :size_id AND min_count <= :count AND type_id = :type_id';
            $colors = self::find()->where($sql, [
                                    ':material_id' => $material_id,  
                                    ':size_id' => $size_id,  
                                    ':count' => $count,  
                                    ':type_id' => $type_id,  
                                ])->asArray()->all();

            for ($i = 0; $i < count($colors); $i++) {
                $array[] = $colors[$i]['color'];
            }
        }

        return $array;
    }


    // запишем доступные доп услуги
    public static function getAvaliableAttendances($material_id, $size_id, $count, $type_id, $color = null)
    {   
        $array = [];

        if ($color !== null) {
            $sql = 'material_id = :material_id AND size_id = :size_id AND min_count <= :count 
                AND type_id = :type_id AND color = :color';
            $attendances = self::find()->where($sql, [
                                        ':material_id' => $material_id,  
                                        ':size_id' => $size_id,  
                                        ':count' => $count,  
                                        ':type_id' => $type_id,  
                                        ':color' => $color,  
                                    ])->with('attendances')->asArray()->limit(1)->one(); 
        } else {
            // поменяем sql запрос
            $sql = 'material_id = :material_id AND size_id = :size_id AND min_count <= :count 
                AND type_id = :type_id AND color is NULL';
            $attendances = self::find()->where($sql, [
                                        ':material_id' => $material_id,  
                                        ':size_id' => $size_id,  
                                        ':count' => $count,  
                                        ':type_id' => $type_id,  
                                    ])->with('attendances')->asArray()->limit(1)->one();
        }
        
        $attendances = $attendances['attendances']; // для удобства
       // dd($attendances);
        // переформируем массив
        for ($i = 0; $i < count($attendances); $i++) {
            $array[] = [
                'id' => $attendances[$i]['id'],
                'name' => $attendances[$i]['name'],
                'percent' => $attendances[$i]['percent'],
            ];
        }

        return $array;
    }

    public function beforeDelete() {
        parent::beforeDelete();
        ConstructorPrintPriceAttendance::deleteAll(['price_id' => $this->id]);

        return true;
    }
}
