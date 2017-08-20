<?php

namespace common\models;

use Yii;
use frontend\components\Sms;

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

    // переносит файл из path в prodution папку folder_name
    public static function moveFile($folder_name, $path) {

        // возьмем имя файла
        $filename = basename($path);
        if (copy ($path, Yii::getAlias('@storage') . $folder_name . '/' . $filename))
            return $filename;
        else
            return null;
    }

    public static function generateProductionDir() {

        // проверим есть ли папка 
        $alias = Yii::getAlias('@storage');
        $folder_name = self::STORAGE_CONSTRUCTOR_ORDER_DIR . '/' . time();
        $dir_path = $alias . $folder_name;

        if (!file_exists($dir_path) && !is_dir($dir_path)) 
                if (!mkdir($dir_path, 0755, true)) return false;

        return $folder_name;
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

    /* Изменяет данные товара через аякс */ 
    public static function ajaxChangeProductData()
    {   
        $full_side_name = '';

        $id = (int)Yii::$app->request->post('id');
        $model = self::findOne(['id' => $id]);
        if ($model == null) 
            return ['status' => 'fail', 'message' => 'Не удалось найти товар!'];

        $total_price = (int)Yii::$app->request->post('total_price');
        if ($total_price <= 0) 
            return ['status' => 'fail', 'message' => 'Цена меньше или равна нулю!'];

        $side_name = Yii::$app->request->post('side_name');
        $type_id = Yii::$app->request->post('type_id');
        $side_id = (int)Yii::$app->request->post('side_id');

        // проверим, если тип печати, но тип печати не наден, значит ошиька
        $type = null;
        if ($type_id != null) {
            $type = ConstructorPrintTypes::findOne(['id' => (int)$type_id]);
            if ($type == null)
                return ['status' => 'fail', 'message' => 'Тип печати не найден!'];
        }
        

        // проверим, что по указанной стороне
        switch ($side_name) {
            case 'front':
                if ($type == null) break;
                $full_side_name = 'Лицевая сторона';

                $data = $model->front_print_data;
                if ($data == null || strlen($data) == 0)
                    return ['status' => 'fail', 'message' => 'Невозможно указать тип печати!'];

                $data = json_decode($data, true);
                $data['type_id'] = $type_id;
                $model->front_print_data = json_encode($data);

                break;
            
            case 'back':
                if ($type == null) break;
                $full_side_name = 'Обратная сторона';

                $data = $model->back_print_data;
                if ($data == null || strlen($data) == 0)
                    return ['status' => 'fail', 'message' => 'Невозможно указать тип печати!'];

                $data = json_decode($data, true);
                $data['type_id'] = $type_id;
                $model->back_print_data = json_encode($data);

                break;

            case 'additional':
                if ($type == null) break;

                $data = $model->additional_print_data;
                if ($data == null || strlen($data) == 0)
                    return ['status' => 'fail', 'message' => 'Невозможно указать тип печати!'];

                $data = json_decode($data, true);
                $was_found = false;

                for ($i = 0; $i < count($data); $i++) {
                    if (!isset($data[$i]['side_id'])) 
                        continue;
                    if ($data[$i]['side_id'] == $side_id) {
                        $was_found = true;
                        $data[$i]['type_id'] = $type_id;
                        $side_data = ConstructorAdditionalSides::findOne(['id' => (int)$side_id]);
                        $full_side_name = $side_data ? $side_data->name : 'Неизвестная сторона';
                    }
                }

                if (!$was_found)
                    return ['status' => 'fail', 'message' => 'Неизвестная сторона!'];

                break;

            default:
                // если указали неправильную сторону
                return ['status' => 'fail', 'message' => 'Неизвестная сторона!']; 
                break;
        }

        $model->price = ceil($total_price / $model->count);

        if ($model->save()) {
            self::sendSuccessChangeSms($model, $full_side_name, $type);
            return [
                'status' => 'ok',
                'current_type' => $type == null ? '' : $type->name,
                'price' => $model->price,
            ];
        }

        return ['status' => 'fail', 'message' => 'Что то пошло не так, попробуйте позже!'];

    }

    private static function sendSuccessChangeSms($product, $side_name, $type)
    {   
        $order = Orders::findOne(['id' => $product->order_id]);
        if ($order == null) return false;
        $phone = $order->phone;
        $message = '';

        $message .= 'Цена за товар "' . $product->name . '", ';
        $message .= 'в заказе №' . $order->id;
        $message .= ', была изменена на ' . $product->price . 'руб. за шт.';
        
        if ($type != null) {
            $message .= "\r\n";
            $message .= 'Метод печати стороны "' . $side_name . '"';
            $message .= ' был изменен на "' . $type->name . '".'; 
        }

        Sms::message($phone, $message);
    }

    public static function getImagesLink($folder_name)
    {
        $alias = Yii::getAlias('@storage_link');
        return $alias . $folder_name;
    }
}
