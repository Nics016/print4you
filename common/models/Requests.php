<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "requests".
 *
 * @property integer $id
 * @property integer $request_type
 * @property string $name
 * @property string $phone
 * @property string $email
 * @property string $comment
 * @property integer $created_at
 */
class Requests extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'requests';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['request_type', 'created_at'], 'integer'],
            [['name'], 'required'],
            [['name', 'phone', 'email', 'comment'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'request_type' => 'Тип заявки',
            'name' => 'Имя клиента',
            'phone' => 'Номер',
            'email' => 'Email',
            'comment' => 'Комментарий',
            'created_at' => 'Дата',
        ];
    }
}
