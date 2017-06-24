<?php

use yii\db\Migration;

class m170623_211235_stock_colors extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('stock_colors', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'liters' => $this->decimal()->notNull()->defaultValue(0),
            'office_id' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->addForeignKey('fk_stock_colors_office', 
            'stock_colors', 'office_id',
            'office', 'id',
            'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropTable('stock_colors');
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
