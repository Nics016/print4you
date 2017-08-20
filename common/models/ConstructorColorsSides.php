<?php

namespace common\models;

use yii\web\UploadedFile;

use Yii;


class ConstructorColorsSides extends \yii\db\ActiveRecord
{
    
    public $imageFile = null;

    const STORAGE_FULL_SIZE_DIR_TEMPLATE = '/constructor/colors/full-size/addititional-sides';
    const STORAGE_SMALL_SIZE_DIR_TEMPLATE = '/constructor/colors/small-size/addititional-sides';

    public static function tableName()
    {
        return 'constructor_colors_sides';
    }

    public static function uploadFromAjax()
    {   
        

        $id = Yii::$app->request->post('id');
        $color_id = (int)Yii::$app->request->post('color_id');
        $side_id = (int)Yii::$app->request->post('side_id');

        // если новая запись, то созданим новую модель и проверим на картинку
        if ($id == 'new') {
            $model = new self();
            $model->color_id = $color_id;
            if (!isset($_FILES['image'])) return ['status' => 'fail', 'msg' => 'Неизвестный файл'];
        } else {
            $model = self::findOne((int)$id);
            if ($model == null) return ['status' => 'fail', 'msg' => 'Неизвестная запись'];
        }

        $model->side_id = $side_id;

        $model->imageFile = UploadedFile::getInstanceByName('image');

        if (!$model->validate()) {
            $errors = $model->getFirstErrors();
            return ['status' => 'fail', 'msg' => reset($errors)];
        }

        $model->upload();
        if ($model->save()) {
            return [
                'status' => 'ok', 
                'image' => self::getSmallImageLink() . '/' . $model->small_image,
                'id' => $model->getPrimaryKey(),
            ];
        } else {
            return [
                'status' => 'fail',
                'msg' => 'Не удалось обновить данные!',
            ];
        }
    }
   
    public function rules()
    {
        return [
            [['color_id', 'side_id'], 'required'],
            [['color_id', 'side_id'], 'integer'],
            [['color_id', 'side_id'], 'unique', 'targetAttribute' => ['color_id', 'side_id'], 'message' => 'Комбинация цвета и стороны уже существует!'],
            ['color_id', 'exist', 'targetClass' => ConstructorColors::className(), 'targetAttribute' => 'id', 'message' => 'Такого цвета не существует!'],
            ['side_id', 'exist', 'targetClass' => ConstructorAdditionalSides::className(), 'targetAttribute' => 'id', 'message' => 'Такой стороны не существует!'],
            [['small_image', 'full_image'], 'string', 'max' => 255],
            [['imageFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg', 'maxSize' => 1024 * 1024 * 2],
        ];
    }


    public function upload()
    {
        if ($this->checkDirs()) {

            $this->imageFile = UploadedFile::getInstanceByName('image');
            if ($this->validate()) {  

                // берем пути папок, куда будем загружать
                $full_path = self::getFullImageDir();
                $small_path = self::getSmallImageDir();

                // генеририуем две полных картинок
                $full = $this->uploadFullImage($this->imageFile, $full_path);

                // генерируем маленькую картинку
                $small = $this->uploadSmallImage($this->imageFile, $full , $full_path, $small_path);

                // удалим старые картнки
                if ($this->imageFile != null)
                    $this->removeOldImages();

                if ($full != false) $this->full_image = $full; 
                if ($small != false) $this->small_image = $small; 

                $this->imageFile = null;

            }

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
    private function uploadSmallImage($image, $image_name, $image_dir, $upload_dir) {

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
        $full_path =  $alias . self::STORAGE_FULL_SIZE_DIR_TEMPLATE;
        $small_path =  $alias . self::STORAGE_SMALL_SIZE_DIR_TEMPLATE;

        if (!$this->checkDir($full_path)) {
            throw new \Exception("Can't make upload dir for full front images!");
            return false;
        }
        if (!$this->checkDir($small_path)) {
            throw new \Exception("Can't make upload dir for full back images!");
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


    // удаление картинок
    private function removeOldImages() {
        if ($this->full_image != '') {

            $full_image_name = $this->full_image;
            $full_dir = self::getFullImageDir();

            @unlink("$full_dir/$full_image_name");
        }

        if ($this->small_image != '') {

            $small_image_name = $this->small_image;
            $small_dir = self::getSmallImageDir();

            @unlink("$small_dir/$small_image_name");
        }
    }

    // папки
    public static function getFullImageDir() 
    {
        return Yii::getAlias('@storage') . self::STORAGE_FULL_SIZE_DIR_TEMPLATE;
    }

    public static function getSmallImageDir()
    {
        return Yii::getAlias('@storage') . self::STORAGE_SMALL_SIZE_DIR_TEMPLATE;
    }

    // ссылки
    public static function getFullImageLink() {
        return Yii::getAlias('@storage_link') . self::STORAGE_FULL_SIZE_DIR_TEMPLATE;
    }

    public static function getSmallImageLink() {
        return Yii::getAlias('@storage_link') . self::STORAGE_SMALL_SIZE_DIR_TEMPLATE;
    }

    public function beforeDelete()
    {
        parent::beforeDelete();
        $this->removeOldImages();
        return true;
    }

    // используется в на fontend странице констурктора 
    public function getSide()
    {
        return $this->hasOne(ConstructorAdditionalSides::className(), ['id' => 'side_id']);
    }

    public function attributeLabels()
    {
        return [
            'color_id' => 'Цвет',
            'side_id' => 'Сторона',
            'small_image' => 'Маленькая картинка',
            'full_image' => 'Большая картинка',
        ];
    }
}
