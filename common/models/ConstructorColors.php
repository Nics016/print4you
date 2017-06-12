<?php

namespace common\models;


use Yii;
use yii\web\UploadedFile;

use yii\widgets\ActiveForm;

class ConstructorColors extends \yii\db\ActiveRecord
{

    public $backImage;
    public $frontImage;
    public $colorSizes;

    const STORAGE_FULL_SIZE_FRONT_DIR_TEMPLATE = '/constructor/colors/full-size/front';
    const STORAGE_FULL_SIZE_BACK_DIR_TEMPLATE = '/constructor/colors/full-size/back';
    const STORAGE_SMALL_SIZE_FRONT_DIR_TEMPLATE = '/constructor/colors/small-size/front';
    const STORAGE_SMALL_SIZE_BACK_DIR_TEMPLATE = '/constructor/colors/small-size/back';

    
    public static function tableName()
    {
        return 'constructor_colors';
    }

  
    public function rules()
    {
        return [
            [['name', 'color_value', 'colorSizes', 'product_id'], 'required'],
            [['name'], 'string', 'max' => 255],
            [['color_value'], 'string', 'max' => 50],
            [['full_front_image', 'full_back_image', 'small_front_image', 'small_back_image'], 'string', 'max' => 255],
            ['product_id', 'integer', 'min' => 1],
            ['backImage', 'file', 'extensions' => 'png, jpg', 
                    'skipOnEmpty' => true],
            ['frontImage', 'file', 'extensions' => 'png, jpg', 
                    'skipOnEmpty' => true],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Имя цвета',
            'color_value' => 'Знаение цвета',
            'frontImage' => 'Лицевая сторона',
            'backImage' => 'Задняя сторона',
            'colorSizes' => 'Размеры',
        ];
    }

    public function saveAll()
    {   
        if ($this->isNewRecord) {
            $this->uploadImages();
            if ($this->save()) {
                $this->changeSizes();
                return true;
            }
            return false;
        } else {
            $this->uploadImages();
            $this->changeSizes();
            return $this->save();
        }
        
    }


    public function uploadImages() {
        if ($this->checkDirs()) {

            $this->frontImage = UploadedFile::getInstance($this, 'frontImage');
            $this->backImage = UploadedFile::getInstance($this, 'backImage');
            dd(ActiveForm::validate($this));
            if ($this->validate()) {  

                // берем пути папок, куда будем загружать
                $full_front_path = self::getFullFrontImageDir();
                $full_back_path = self::getFullBackImageDir();
                $small_front_path = self::getSmallFrontImageDir();
                $small_back_path = self::getSmallBackImageDir();

                // генеририуем две полных картинок
                $full_front = $this->uploadFullImage($this->frontImage, $full_front_path);
                $full_back = $this->uploadFullImage($this->backImage, $full_back_path);

                // генерируем маленькую картинку
                $small_front = $this->uploadSmallImages($this->frontImage, $full_front , $full_front_path, $small_front_path);
                $small_back = $this->uploadSmallImages($this->backImage, $full_back , $full_back_path, $small_back_path);

                // удалим старые картнки
                if ($this->frontImage != null)
                    $this->removeFrontImages();
                if ($this->backImage != null)
                    $this->removeBackImages();

                if ($full_front != false) $this->full_front_image = $full_front; 
                if ($full_back != false) $this->full_back_image = $full_back; 
                if ($small_front != false) $this->small_front_image = $small_front; 
                if ($small_back != false) $this->small_back_image = $small_back; 

                $this->frontImage = null;
                $this->backImage = null;

                
            }

        }
    }

    public function changeSizes() 
    {
        $constructor_sizes = ConstructorSizes::find()->where(['id' => $this->colorSizes])->asArray()->all();

        ConstructorColorSizes::deleteAll(['color_id' => $this->id]);

        for ($i = 0; $i < count($constructor_sizes); $i++) {
            $model = new ConstructorColorSizes();
            $model->color_id = $this->id;
            $model->size_id = $constructor_sizes[$i]['id'];
            $model->save();
        }
    }

    // загружает полную картинку
    private function uploadFullImage($image, $dir) 
    {
        if ($image) {
            $full_image_name = time() . '.' . $image->extension;
            $image->saveAs("$dir/$full_image_name");
            return $full_image_name;
        }

        return false;
    }

    // загружает маленькую картинку
    private function uploadSmallImages($image, $image_name, $image_dir, $upload_dir) {

        if ($image) {
            $small_image = new \Imagick("$image_dir/$image_name");
            $small_image->adaptiveResizeImage(320,320);
            $small_image_name = time() . '.' . $image->extension;
            $small_image->writeImage("$upload_dir/$small_image_name");

            return $small_image_name;
        }

        return false;
    }

    // проверка папок загрузки файлов и создание, если нет
    private function checkDirs() {
        $alias = Yii::getAlias('@storage');
        $full_front_path =  $alias . self::STORAGE_FULL_SIZE_FRONT_DIR_TEMPLATE;
        $full_back_path =  $alias . self::STORAGE_FULL_SIZE_BACK_DIR_TEMPLATE;
        $small_front_path =  $alias . self::STORAGE_SMALL_SIZE_FRONT_DIR_TEMPLATE;
        $small_back_path =  $alias . self::STORAGE_SMALL_SIZE_BACK_DIR_TEMPLATE;

        if (!$this->checkDir($full_front_path)) {
            throw new \Exception("Can't make upload dir for full front images!");
            return false;
        }
        if (!$this->checkDir($full_back_path)) {
            throw new \Exception("Can't make upload dir for full back images!");
            return false;
        }
        if (!$this->checkDir($small_front_path)) {
            throw new \Exception("Can't make upload dir for small front images!");
            return false;
        }
        if (!$this->checkDir($small_back_path)) {
            throw new \Exception("Can't make upload dir for small back images!");
            return false;
        }

        return true;
    }

    // проверяет конкрутную папку
    private function checkDir($dir) {
        if (!file_exists($dir) && !is_dir($dir)) 
            if (!mkdir($dir, 0755, true)) return false;

        return true;
    } 


    // папки
    public static function getFullFrontImageDir() 
    {
        return Yii::getAlias('@storage') . self::STORAGE_FULL_SIZE_FRONT_DIR_TEMPLATE;
    }

    public static function getFullBackImageDir()
    {
        return Yii::getAlias('@storage') . self::STORAGE_FULL_SIZE_BACK_DIR_TEMPLATE;
    }

    public static function getSmallFrontImageDir()
    {
        return Yii::getAlias('@storage') . self::STORAGE_SMALL_SIZE_FRONT_DIR_TEMPLATE;
    }

    public static function getSmallBackImageDir() {
        return Yii::getAlias('@storage') . self::STORAGE_SMALL_SIZE_BACK_DIR_TEMPLATE;
    }

    // ссылки
    public static function getFullFrontImageLink() {
        return Yii::getAlias('@storage_link') . self::STORAGE_FULL_SIZE_FRONT_DIR_TEMPLATE;
    }

    public static function getFullBackImageLink() {
        return Yii::getAlias('@storage_link') . self::STORAGE_FULL_SIZE_BACK_DIR_TEMPLATE;
    }

    public static function getSmallFrontImageLink() {
        return Yii::getAlias('@storage_link') . self::STORAGE_SMALL_SIZE_FRONT_DIR_TEMPLATE;
    }

    public static function getSmallBackImageLink() {
        return Yii::getAlias('@storage_link') . self::STORAGE_SMALL_SIZE_BACK_DIR_TEMPLATE;
    }


    // удаление картинок
    public function removeFrontImages() {
        if ($this->full_front_image != '') {

            $full_image_name = $this->full_front_image;
            $full_dir = self::getFullFrontImageDir();

            $small_image_name = $this->small_front_image;
            $small_dir = self::getSmallFrontImageDir();

            @unlink("$full_dir/$full_image_name");
            @unlink("$small_dir/$small_image_name");
        }
    }

    public function removeBackImages() {
        if ($this->full_back_image != '') {

            $full_image_name = $this->full_back_image;
            $full_dir = self::getFullBackImageDir();

            $small_image_name = $this->small_back_image;
            $small_dir = self::getSmallBackImageDir();

            @unlink("$full_dir/$full_image_name");
            @unlink("$small_dir/$small_image_name");
        }
    }


    // для подсветки чекбоксов в редактировании цвета
    public function checkSizes() {
        $sizes = $this->sizes;
        $this->colorSizes = [];

        for ($i = 0; $i < count($sizes); $i++)
            $this->colorSizes[] =  $sizes[$i]->id;
    }

    public function getProduct() 
    {
        return $this->hasOne(ConstructorProducts::className(), ['id' => 'product_id']);
    }

    public function getSizes() 
    {   
        return $this->hasMany(ConstructorSizes::className(), ['id' => 'size_id'])
            ->viaTable('constructor_color_sizes', ['color_id' => 'id'])->orderBy('sequence');
    }

    // перед удалением записи - удалим картинки
    public function beforeDelete() {
        parent::beforeDelete();
        $this->removeFrontImages();
        $this->removeBackImages();

        return true;
    }


    // связь для вывода конструктора во фронтенд
    public function getConstructorSizes() {
        return $this->hasMany(ConstructorSizes::className(), ['id' => 'size_id'])
            ->viaTable('constructor_color_sizes', ['color_id' => 'id'])->orderBy('sequence');
    }
}
