<?php

namespace common\models;

use Yii;

use yii\web\UploadedFile;

class ConstructorCategories extends \yii\db\ActiveRecord
{   
    const STORAGE_IMAGE_DIR_TEMPLATE = '/constructor/categories';
    
    public $imageFile;

    public static function tableName()
    {
        return 'constructor_categories';
    }

    public function rules()
    {
        return [
            [['sequence'], 'integer'],
            [['h1_tag_title', 'menu_title'], 'required'],
            [['name', 'img', 'seo_title', 'seo_description', 'seo_keywords', 'h1_tag_title', 'menu_title', 'img_alt'], 'string', 'max' => 255],
            [['description'], 'string'],
            ['imageFile', 'file', 'extensions' => 'png, jpg', 
                    'skipOnEmpty' => true],
        ];
    }

 
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'sequence' => 'Sequence',
            'description' => 'Описание',
            'imageFile' => 'Картинка',
            'seo_title' => 'SEO Title',
            'seo_description' => 'SEO Description',
            'seo_keywords' => 'SEO Keywords',
            'h1_tag_title' => 'Тэг H1',
            'menu_title' => 'Название пункта меню',
            'img_alt' => 'Alt Картинки',
        ];
    }

    public function uploadImage() {
        if ($this->checkDir()) {

            $this->imageFile = UploadedFile::getInstance($this, 'imageFile');

            if ($this->validate() && $this->imageFile != null) {

                // генеририуем полную картинку
                $image = time() . '.' . $this->imageFile->extension;
                $dir = self::getImagesDir();
                $this->imageFile->saveAs("$dir/$image");


                // удалим старые картнки
                $this->removeImages();
                $this->img = $image;

                $this->imageFile = null;
                
            }

        } else {
            throw new Exception("Cant't make upload dir!");
        }
    }

    // проверка папок загрузки файлов и создание, если нет
    private function checkDir() {
        $alias = Yii::getAlias('@storage');
        $dir =  $alias . self::STORAGE_IMAGE_DIR_TEMPLATE;

        if (!file_exists($dir) && !is_dir($dir)) 
            if (!mkdir($dir, 0755, true)) return false;
    

        return true;
    }   


    // методы возвращают папки и ссылки на директорию картинки
    public static function getImagesDir() {
        return Yii::getAlias('@storage') . self::STORAGE_IMAGE_DIR_TEMPLATE;
    }

    public static function getImagesLink() {
        return Yii::getAlias('@storage_link') . self::STORAGE_IMAGE_DIR_TEMPLATE;
    }

    // удаление картинок
    public function removeImages() {
        if ($this->img != '') {

            $img = $this->img;
            $dir = self::getImagesDir();

            @unlink("$dir/$img");
        }
    }


    // перед сохранинем сделаем у новых категорию послежовательность
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {

            if ($this->isNewRecord) {
                $count = self::find()->count();
                $this->sequence = $count + 1;
            }

            return true;
        }
        return false;
    }

    // перед удалением категории - удалим товар
    public function beforeDelete() {
        parent::beforeDelete();

        set_time_limit(0);
        $this->removeImages();
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

    public function getCategoryProducts()
    {   

        return $this->hasMany(ConstructorProducts::className(), ['category_id' => 'id'])
                    ->with('colors'); 
    }

    public function getProducts() 
    {   
        $link = ConstructorProducts::getSmallImagesLink();

        return $this->hasMany(ConstructorProducts::className(), ['category_id' => 'id'])
                    ->select("id, name, description, print_offset_x, print_offset_y, print_width, print_height, category_id, ('$link' || '/' || small_image) as image, img_alt")
                    ->where(['is_published' => true])->with('constructorColors');
    }

    
    // для страницы услуги
    public static function getCats()
    {   
        $link = self::getImagesLink();
        return self::find()
                ->select("id, name, description, ('$link' || '/' || img) as img, img_alt")
                ->asArray()->orderBy('sequence')->all();
    }
}
