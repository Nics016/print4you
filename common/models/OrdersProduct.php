<?php

namespace common\models;

use Yii;

class OrdersProduct extends \yii\db\ActiveRecord
{
   
    const STORAGE_CONSTRUCTOR_ORDER_DIR = '/constructor/orders/production';

    public static function tableName()
    {
        return 'orders_product';
    }

   
    public function rules()
    {
        return [
            [['order_id', 'product_id', 'price', 'count', 'size_id', 'color_id'], 'integer'],
            [['is_constructor'], 'boolean'],
            [['name', 'front_image', 'back_image'], 'string', 'max' => 255],
        ];
    }

    public static function moveFileToProduction($oldfile) {
        $dir_path = self::generateProductionDir();

        // возьмем имя файла
        $filename = basename($oldfile);
        if (@rename($oldfile, $dir_path . '/' . $filename))
            return $filename;
        else
            return false;
    }

    private static function generateProductionDir() {

        // проверим есть ли временная папка, если нет, то создадим
        $alias = Yii::getAlias('@storage');
        $dir_path = $alias . self::STORAGE_CONSTRUCTOR_ORDER_DIR;

        if (!file_exists($dir_path) && !is_dir($dir_path)) 
                if (!mkdir($dir_path, 0755, true)) return false;

        return $dir_path;
    }
   
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_id' => 'Order ID',
            'product_id' => 'Product ID',
            'name' => 'Name',
            'price' => 'Price',
            'is_constructor' => 'Is Constructor',
            'count' => 'Count',
            'size_id' => 'Size ID',
            'color_id' => 'Color ID',
            'front_image' => 'Front Image',
            'back_image' => 'Back Image',
        ];
    }

    public static function getImagesLink()
    {
        $alias = Yii::getAlias('@storage_link');
        return $alias . '/' . self::STORAGE_CONSTRUCTOR_ORDER_DIR;
    }
}
