<?php

use yii\db\Migration;

class m170623_215552_stock_requests extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('stock_requests', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'office_id' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->addForeignKey('fk_stock_requests_user', 
            'stock_requests', 'user_id',
            'user', 'id',
            'CASCADE', 'CASCADE');

        $this->addForeignKey('fk_stock_requests_office', 
            'stock_requests', 'office_id',
            'office', 'id',
            'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropTable('stock_requests');
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
