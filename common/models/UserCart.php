<?php

namespace common\models;

use Yii;

class UserCart extends \yii\db\ActiveRecord
{
    
    public static function tableName()
    {
        return 'user_cart';
    }

  
    public function rules()
    {
        return [
            [['user_id'], 'integer'],
            [['cart_data'], 'string'],
            [['expire_in'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'cart_data' => 'Cart Data',
            'expire_in' => 'Expire In',
        ];
    }
}
