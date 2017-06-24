<?php

namespace backend\models;

use Yii;
use common\models\Office;

/**
 * This is the model class for table "stock_requests".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $office_id
 *
 * @property Office $office
 * @property User $user
 * @property StockRequestsItems[] $stockRequestsItems
 */
class StockRequests extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'stock_requests';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['office_id'], 'required'],
            [['user_id', 'office_id'], 'integer'],
            [['office_id'], 'exist', 'skipOnError' => true, 'targetClass' => Office::className(), 'targetAttribute' => ['office_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'Имя пользователя',
            'office_id' => 'Офис',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOffice()
    {
        return $this->hasOne(Office::className(), ['id' => 'office_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStockRequestsItems()
    {
        return $this->hasMany(StockRequestsItems::className(), ['stock_request_id' => 'id']);
    }
}
