<?php

use yii\db\Migration;

/**
 * Handles the creation of table `orders`.
 */
class m170415_161527_create_orders_table extends Migration
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

        $this->createTable('orders', [
            'id' => $this->primaryKey(),
            'order_status' => $this->string(32)->notNull()->defaultValue("new"),
            'price' => $this->integer()->notNull()->defaultValue(0),
            'manager_id' => $this->integer(),
            'comment' => $this->string(1000)->notNull()->defaultValue(""),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('orders');
    }
}
