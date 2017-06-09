<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "constructor_products".
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property string $image
 * @property integer $price
 * @property integer $category_id
 */
class ConstructorProducts extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'constructor_products';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'image', 'price', 'category_id'], 'required'],
            [['description'], 'string'],
            [['price', 'category_id'], 'integer'],
            [['name', 'image'], 'string', 'max' => 255],
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
            'description' => 'Description',
            'image' => 'Image',
            'price' => 'Price',
            'category_id' => 'Category ID',
        ];
    }
}
