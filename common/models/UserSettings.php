<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "user_settings".
 *
 * @property integer $id
 * @property string $email
 * @property string $email_index
 * @property string $vk_link
 * @property string $insta_link
 */
class UserSettings extends \yii\db\ActiveRecord
{
    const CURRENT_SETTINGS_ID = 1;


    /**
     * Возвращает текущие настройки
     * 
     * @return array $settings
     */
    public static function getCurrentSettings()
    {
        return self::findOne([
            'id' => self::CURRENT_SETTINGS_ID,
        ]);
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_settings';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['email'], 'required'],
            [['email', 'email_index', 'vk_link', 'insta_link'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'email' => 'Email для отправки заявок',
            'email_index' => 'Email вверху на главной и др. страницах',
            'vk_link' => 'Ссылка на VK',
            'insta_link' => 'Ссылка на Instagram',
        ];
    }
}
