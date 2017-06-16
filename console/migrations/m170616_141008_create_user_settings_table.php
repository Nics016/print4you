<?php

use yii\db\Migration;

/**
 * Handles the creation of table `user_settings`.
 */
class m170616_141008_create_user_settings_table extends Migration
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

        $this->createTable('user_settings', [
            'id' => $this->primaryKey(),
            'email' => $this->string()->notNull(),
            'email_index' => $this->string(),
            'vk_link' => $this->string(),
            'insta_link' => $this->string(),
        ], $tableOptions);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('user_settings');
    }
}
