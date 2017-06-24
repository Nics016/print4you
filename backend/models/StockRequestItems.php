<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "stock_requests_items".
 *
 * @property integer $id
 * @property integer $stock_request_id
 * @property boolean $applied
 * @property integer $office_id
 * @property integer $stock_color_id
 * @property string $stock_color_litres
 * @property integer $constructor_storage_id
 * @property integer $constructor_storage_count
 *
 * @property StockRequests $stockRequest
 */
class StockRequestItems extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'stock_requests_items';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['stock_request_id', 'office_id'], 'required'],
            [['stock_request_id', 'office_id', 'stock_color_id', 'constructor_storage_id', 'constructor_storage_count'], 'integer'],
            [['applied'], 'boolean'],
            [['stock_color_litres'], 'number'],
            [['stock_request_id'], 'exist', 'skipOnError' => true, 'targetClass' => StockRequests::className(), 'targetAttribute' => ['stock_request_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'stock_request_id' => 'Stock Request ID',
            'applied' => 'Applied',
            'office_id' => 'Office ID',
            'stock_color_id' => 'Stock Color ID',
            'stock_color_litres' => 'Stock Color Litres',
            'constructor_storage_id' => 'Constructor Storage ID',
            'constructor_storage_count' => 'Constructor Storage Count',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStockRequest()
    {
        return $this->hasOne(StockRequests::className(), ['id' => 'stock_request_id']);
    }
}
