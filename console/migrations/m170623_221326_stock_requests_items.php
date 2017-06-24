<?php

use yii\db\Migration;

class m170623_221326_stock_requests_items extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('stock_requests_items', [
            'id' => $this->primaryKey(),
            'stock_request_id' => $this->integer()->notNull(),
            'applied' => $this->boolean()->notNull()->defaultValue(false),
            'office_id' => $this->integer()->notNull(),
            'stock_color_id' => $this->integer(),
            'stock_color_litres' => $this->decimal(),
            'constructor_storage_id' => $this->integer(),
            'constructor_storage_count' => $this->integer(),
        ], $tableOptions);

        $this->addForeignKey('fk_stock_items_stock_requests', 
            'stock_requests_items', 'stock_request_id',
            'stock_requests', 'id',
            'CASCADE', 'CASCADE'
        );
    }

    public function down()
    {
        $this->dropTable('stock_requests_items');
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
