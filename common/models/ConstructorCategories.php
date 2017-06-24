<?php

namespace common\models;

use Yii;

class ConstructorCategories extends \yii\db\ActiveRecord
{
   
    public static function tableName()
    {
        return 'constructor_categories';
    }

    public function rules()
    {
        return [
            [['sequence'], 'integer'],
            [['name'], 'string', 'max' => 255],
        ];
    }

 
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'sequence' => 'Sequence',
        ];
    }

    // перед удалением категории - удалим товар
    public function beforeDelete() {
        parent::beforeDelete();

        set_time_limit(0);
        $products = ConstructorProducts::find()->where(['category_id' => $this->id])->all();

        for ($i = 0; $i < count($products); $i++) 
            $products[$i]->delete();

        return true;
    }

    public static function getConstructorArray() 
    {
        $array = self::find()->with('products')->asArray()->orderBy('sequence')->all();

        return $array;
    }

    public function getProducts() 
    {   
        $link = ConstructorProducts::getSmallImagesLink();

        return $this->hasMany(ConstructorProducts::className(), ['category_id' => 'id'])
                    ->select("id, name, description, print_offset_x, print_offset_y, print_width, print_height, category_id, ('$link' || '/' || small_image) as image")
                    ->where(['is_published' => true])->with('constructorColors');
    }

    
}
