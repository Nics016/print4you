<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "constructor_categories".
 *
 * @property integer $id
 * @property string $name
 * @property integer $sequence
 */
class ConstructorCategories extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'constructor_categories';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sequence'], 'integer'],
            [['name'], 'string', 'max' => 255],
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
            'sequence' => 'Sequence',
        ];
    }
}
