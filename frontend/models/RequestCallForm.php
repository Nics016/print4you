<?php
namespace frontend\models;

use Yii;
use yii\base\Model;

/**
 * Login form
 */
class RequestCallForm extends Model
{
    // constants
    const FORM_TYPE_CALL = 10;
    const FORM_TYPE_CONTACTS = 20;
    const FORM_TYPE_FRANCHISE = 30;

    // fields
    public $name;
    public $phone;
    public $email;
    public $comment;
    public $form_type;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'phone'], 'required'],
            [['name', 'phone', 'comment', 'email'], 'string'],
            ['form_type', 'integer'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => 'Имя',
            'phone' => 'Телефон',
            'comment' => 'Примечание',
        ];
    }

    public function sendEmail($email, $subject, $body)
    {
        // $headers = "MIME-Version: 1.0" . "\r\n";
        // $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        mail($email, $subject, $body);
    }
}
