<?php

use yii\db\Migration;

class m170611_160556_common_create_user_table extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('common_user', [
            'id' => $this->primaryKey(),

            'firstname' => $this->string()->notNull(),
            'address' => $this->string(),
            'phone' => $this->string()->notNull()->unique(),
            'profile_pic' => $this->text(),
            'sum_purchased_retail' => $this->integer()->defaultValue(0),
            'sum_purchased_gross' => $this->integer()->defaultValue(0),

            'auth_key' => $this->string(32)->notNull(),
            'password_hash' => $this->string()->notNull(),
            'password_reset_token' => $this->string()->unique(),
            'email' => $this->string()->unique(),
            'status' => $this->smallInteger()->notNull()->defaultValue(10),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);
    }

    public function down()
    {
         $this->dropTable('{{%common_user}}');
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
