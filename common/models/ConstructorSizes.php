<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "constructor_sizes".
 *
 * @property integer $id
 * @property string $size
 * @property integer $sequence
 */
class ConstructorSizes extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'constructor_sizes';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['size', 'sequence'], 'required'],
            [['sequence'], 'integer'],
            [['size'], 'string', 'max' => 10],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'size' => 'Size',
            'sequence' => 'Sequence',
        ];
    }

    // удлим данные со склада и из таблицы ConstructorColorSizes
    public function beforeDelete() {
        ConstructorStorage::deleteAll(['color_id' => $this->id]);
        ConstructorColorSizes::deleteAll(['color_id' => $this->id]);

        return true;
    }

}
