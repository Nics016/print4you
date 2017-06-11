<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "constructor_colors".
 *
 * @property integer $id
 * @property string $name
 * @property string $color_value
 * @property string $front_image
 * @property string $back_image
 * @property string $sizes
 */
class ConstructorColors extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'constructor_colors';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'color_value', 'front_image', 'back_image', 'sizes', 'product_id'], 'required'],
            [['name', 'front_image', 'back_image', 'sizes'], 'string', 'max' => 255],
            [['color_value'], 'string', 'max' => 50],
            ['product_id', 'integer', 'min' => 1],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'color_value' => 'Color Value',
            'front_image' => 'Front Image',
            'back_image' => 'Back Image',
            'sizes' => 'Sizes',
        ];
    }
}
