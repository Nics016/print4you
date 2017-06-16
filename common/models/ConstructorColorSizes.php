<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "constructor_color_sizes".
 *
 * @property integer $color_id
 * @property integer $size_id
 */
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

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'color_id' => 'Color ID',
            'size_id' => 'Size ID',
        ];
    }
}
