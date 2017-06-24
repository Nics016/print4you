<?php

namespace backend\models;

use Yii;
use common\models\Office;

/**
 * This is the model class for table "stock_colors".
 *
 * @property integer $id
 * @property string $name
 * @property string $liters
 * @property integer $office_id
 *
 * @property Office $office
 */
class StockColors extends \yii\db\ActiveRecord
{
    public $color_id;
    public $office_id;
    public $liters;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'stock_colors';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'office_id', 'liters'], 'required'],
            [['liters'], 'number'],
            [['office_id', 'color_id'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['office_id'], 'exist', 'skipOnError' => true, 'targetClass' => Office::className(), 'targetAttribute' => ['office_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Краска',
            'liters' => 'Литры',
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
}
