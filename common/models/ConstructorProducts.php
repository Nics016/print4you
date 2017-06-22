<?php

namespace common\models;

use Yii;

use yii\web\UploadedFile;


class ConstructorProducts extends \yii\db\ActiveRecord
{

    const STORAGE_FULL_SIZE_DIR_TEMPLATE = '/constructor/products/full-size';
    const STORAGE_SMALL_SIZE_DIR_TEMPLATE = '/constructor/products/small-size';
    
    public $imageFile;

    public static function tableName()
    {
        return 'constructor_products';
    }

    public function rules()
    {
        return [
            [['name', 'category_id', 'is_published', 'print_offset_x', 'print_offset_y', 'print_width', 'print_height'], 'required'],
            [['description'], 'string'],
            ['category_id', 'integer'],
            [['name', 'full_image', 'small_image'], 'string', 'max' => 255],
            ['is_published', 'boolean'],
            ['imageFile', 'file', 'extensions' => 'png, jpg', 
                    'skipOnEmpty' => true],

            [['print_width', 'print_height'], 'integer', 'min' => 1, 'max' => 100],
            [['print_offset_x', 'print_offset_y'], 'integer', 'min' => 0, 'max' => 100],

            [['print_width', 'print_height'], 'sumValidation'],
        ];
    }


    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Имя',
            'description' => 'Описание',
            'imageFile' => 'Картинка',
            'category_id' => 'Категория',
            'is_published' => 'Опубликовать?',
            'print_offset_x' => 'Отсутп принта слева',
            'print_offset_y' => 'Отсутп принта сверху',
            'print_width' => 'Ширина принта',
            'print_height' => 'Высота принта',
        ];
    }

    public function sumValidation()
    {
        if (($this->print_width + $this->print_offset_x) > 100) {
            $this->addError('print_width', 'Сумма отсутпа слева и ширины принта не может быть больше 100');
        } 

        if (($this->print_height + $this->print_offset_y) > 100) {
            $this->addError('print_height', 'Сумма отсутпа сверху и высоты принта не может быть больше 100');
        } 
    }

    public function uploadImage() {
        if ($this->checkDir()) {

            $this->imageFile = UploadedFile::getInstance($this, 'imageFile');

            if ($this->validate() && $this->imageFile != null) {

                // генеририуем полную картинку
                $full_image_name = time() . '.' . $this->imageFile->extension;
                $full_dir = self::getFullImagesDir();
                $this->imageFile->saveAs("$full_dir/$full_image_name");

                // генерируем маленькую картинку
                $small_image = new \Imagick("$full_dir/$full_image_name");
                $small_image->adaptiveResizeImage(320,320);
                $small_dir = self::getSmallImagesDir();
                $small_image_name = time() . '.' . $this->imageFile->extension;;
                $small_image->writeImage("$small_dir/$small_image_name");

                // удалим старые картнки
                $this->removeImages();

                $this->full_image = $full_image_name;
                $this->small_image = $small_image_name;

                $this->imageFile = null;

                
            }

        } else {
            throw new Exception("Cant't make upload dir!");
        }
    }

    // проверка папок загрузки файлов и создание, если нет
    private function checkDir() {
        $alias = Yii::getAlias('@storage');
        $full_folder_path =  $alias . self::STORAGE_FULL_SIZE_DIR_TEMPLATE;
        $small_folder_path =  $alias . self::STORAGE_SMALL_SIZE_DIR_TEMPLATE;

        if (!file_exists($full_folder_path) && !is_dir($full_folder_path)) 
            if (!mkdir($full_folder_path, 0755, true)) return false;
    
        elseif (!file_exists($small_folder_path) && !is_dir($small_folder_path)) 
            if (!mkdir($small_folder_path, 0755, true)) return false;

        return true;
    }   


    // методы возвращают папки и ссылки на директорию картинки
    public static function getFullImagesDir() {
        return Yii::getAlias('@storage') . self::STORAGE_FULL_SIZE_DIR_TEMPLATE;
    }

    public static function getSmallImagesDir() {
        return Yii::getAlias('@storage') . self::STORAGE_SMALL_SIZE_DIR_TEMPLATE;
    }

    public static function getFullImagesLink() {
        return Yii::getAlias('@storage_link') . self::STORAGE_FULL_SIZE_DIR_TEMPLATE;
    }

    public static function getSmallImagesLink() {
        return Yii::getAlias('@storage_link') . self::STORAGE_SMALL_SIZE_DIR_TEMPLATE;
    }



    public function getCategory() {
        return $this->hasOne(ConstructorCategories::className(), ['id' => 'category_id']);
    }

    // удаление картинок
    public function removeImages() {
        if ($this->full_image != '') {

            $full_image_name = $this->full_image;
            $full_dir = self::getFullImagesDir();

            $small_image_name = $this->small_image;
            $small_dir = self::getSmallImagesDir();

            @unlink("$full_dir/$full_image_name");
            @unlink("$small_dir/$small_image_name");
        }
    }

    // перед удалением записи - удалим картинки и цвета
    public function beforeDelete() {
        parent::beforeDelete();

        set_time_limit(0);

        $this->removeImages();
        $colors = ConstructorColors::find()->where(['product_id' => $this->id])->all();

        for ($i = 0; $i < count($colors); $i++) 
            $colors[$i]->delete();

        return true;
    }


    // для ассортимента
    public function getFirstColor()
    {   
         $front_full_link = ConstructorColors::getFullFrontImageLink();

        return $this->hasOne(ConstructorColors::className(), ['product_id' => 'id'])
                ->select(
                    "product_id, price, gross_price,
                    ('$front_full_link' || '/' || full_front_image) as image"
                )->orderBy('id');
    }

    // свзязь для вывода во фронтенд конструктора
    public function getConstructorColors()
    {   
        $front_small_link = ConstructorColors::getSmallFrontImageLink();
        $back_small_link = ConstructorColors::getSmallBackImageLink();

        $front_full_link = ConstructorColors::getFullFrontImageLink();
        $back_full_link = ConstructorColors::getFullBackImageLink();

        return $this->hasMany(ConstructorColors::className(), ['product_id' => 'id'])
                ->select("id, name, color_value, product_id, price,
                    ('$front_small_link' || '/' || small_front_image) as small_front_image, 
                    ('$back_small_link' || '/' || small_back_image) as small_back_image, 
                    ('$front_full_link' || '/' || full_front_image) as full_front_image, 
                    ('$back_full_link' || '/' || full_back_image) as full_back_image"
                )->with('constructorSizes');
    }
}
