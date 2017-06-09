<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "constructor_product_colors".
 *
 * @property integer $product_id
 * @property integer $color_id
 */
class ConstructorProductColors extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'constructor_product_colors';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_id', 'color_id'], 'required'],
            [['product_id', 'color_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'product_id' => 'Product ID',
            'color_id' => 'Color ID',
        ];
    }
}
