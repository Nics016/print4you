<?php

use yii\db\Migration;

use frontend\models\RequestCallForm;

/**
 * Handles the creation of table `requests`.
 */
class m170617_173111_create_requests_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('requests', [
            'id' => $this->primaryKey(),
            'request_type' => $this->integer()->defaultValue(RequestCallForm::FORM_TYPE_CALL),
            'name' => $this->string()->notNull(),
            'phone' => $this->string(),
            'email' => $this->string(),
            'comment' => $this->string(),
            'created_at' => $this->integer()->notNull(),
        ], $tableOptions);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('requests');
    }
}
