<?php

namespace common\models;

use Yii;

use yii\helpers\ArrayHelper;

class ConstructorColorSizes extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'constructor_color_sizes';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['color_id', 'size_id'], 'required'],
            [['color_id', 'size_id'], 'integer'],
        ];
    }

    // используется в backend для склада
    public static function getAvaliableSizes($color_id, $as_array = true)
    {   
        $avaliable_sizes = self::find()->select('size_id')->where(['color_id' => $color_id])
                            ->asArray()->all();
        $count = count($avaliable_sizes);
        if ($count > 0) {

            $ids = [];
            $result = false;

            for ($i = 0; $i < $count; $i++) {
                $ids[] = $avaliable_sizes[$i]['size_id'];
            }

            if ($as_array) 
                $result = ConstructorSizes::find()->where(['id' => $ids])->orderBy('sequence')->asArray()->all();
            else
                $result = ConstructorSizes::find()->where(['id' => $ids])->orderBy('sequence')->all();

            return $result;
        }

        return false;
    }

    public function attributeLabels()
    {
        return [
            'color_id' => 'Color ID',
            'size_id' => 'Size ID',
        ];
    }
}
